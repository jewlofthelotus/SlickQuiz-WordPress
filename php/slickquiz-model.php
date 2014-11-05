<?php

// Stop direct call
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
    die( 'You are not allowed to call this page directly.' );
}

if ( !class_exists( 'SlickQuizModel' ) ) {
    class SlickQuizModel extends SlickQuizHelper
    {

        var $data = array();


        function get_all_quizzes( $select = '' )
        {
            global $wpdb;
            $db_name = $wpdb->prefix . 'plugin_slickquiz';

            $select = $select ? $select : 'id, name, workingQCount, publishedQCount, publishedJson, publishedDate, lastUpdatedDate';

            $quizResults = $wpdb->get_results("SELECT $select FROM $db_name ORDER BY lastUpdatedDate DESC");

            return $quizResults;
        }

        function get_quiz_by_id( $id )
        {
            global $wpdb;
            $db_name = $wpdb->prefix . 'plugin_slickquiz';

            $quizResult = $wpdb->get_row( "SELECT * FROM $db_name WHERE id = $id" );

            return $quizResult;
        }

        function get_last_quiz_by_user( $user_id )
        {
            global $wpdb;
            $db_name = $wpdb->prefix . 'plugin_slickquiz';

            $quizResult = $wpdb->get_row( "SELECT * FROM $db_name WHERE createdBy = $user_id ORDER BY createdDate DESC" );

            return $quizResult;
        }

        function get_all_scores( $quiz_id, $order_by = '', $limit = '' )
        {
            global $wpdb;
            $db_name = $wpdb->prefix . 'plugin_slickquiz_scores';

            $order_by = $order_by ? $order_by : 'createdDate DESC';
            $limit = $limit ? $limit : '';

            $scoreResults = $wpdb->get_results( "SELECT id, name, email, score, quiz_id, createdDate FROM $db_name WHERE quiz_id = " . $quiz_id . " ORDER BY $order_by $limit" );

            return $scoreResults;
        }


        /**
         * Helper Methods
         */

        function get_quiz_status( $quiz )
        {
            $status = null;

            if ( $quiz->publishedJson ) {
                if ( $quiz->publishedDate == $quiz->lastUpdatedDate ) {
                    $status = self::PUBLISHED;
                } else {
                    $status = self::UNPUBLISHED_CHANGES;
                }
            } else {
                $status = self::NOT_PUBLISHED;
            }

            return $status;
        }

        function get_quiz_status_button( $quiz, $text = false )
        {
            $status = $this->get_quiz_status( $quiz );
            $button = '';

            if ( $status == self::NOT_PUBLISHED ) {
                $button = "<img title='" . self::NOT_PUBLISHED .
                    "' src='" . plugins_url( '/images/suspend.png' , dirname( __FILE__ ) ) . "'>";
            } else if ( $status == self::UNPUBLISHED_CHANGES ) {
                $button = "<img title='" . self::UNPUBLISHED_CHANGES .
                    "' src='" . plugins_url( '/images/alert.png' , dirname( __FILE__ ) ) . "'>";
            } else if ( $status == self::PUBLISHED ) {
                $button = "<img title='" . self::PUBLISHED .
                    "' src='" . plugins_url( '/images/activate.png' , dirname( __FILE__ ) ) . "'>";
            }

            if ( $text ) {
                $button .= " $status &nbsp;&nbsp;&nbsp;";
            }

            return $button;
        }

        function get_name()
        {
            global $data;
            return $data->info->name;
        }

        function get_question_count()
        {
            global $data;
            return count( $data->questions );
        }

        /**
         * Apply WordPress filters to SlickQuiz JSON on output.
         *
         * @link http://codex.wordpress.org/Data_Validation
         * @link http://codex.wordpress.org/Plugin_API/Filter_Reference
         */
        protected function filter_quiz( $quiz_json )
        {
            // Double negative!
            if ( $this->get_admin_option( 'no_filter_quizzes' ) != 1 ) {
                return $quiz_json;
            }

            $quiz = json_decode( $quiz_json );
            $this->filter_short( $quiz->info->name );
            $this->filter_body( $quiz->info->main );
            $this->filter_body( $quiz->info->results );

            foreach ( $quiz->questions as $question ) {
                $this->filter_body( $question->q );
                $this->filter_body( $question->correct );
                $this->filter_body( $question->incorrect );

                foreach ( $question->a as $answer ) {
                    $this->filter_short( $answer->option );
                }
            }

            return json_encode( $quiz );
        }

        // Filter "body"-type/ <textarea> content in a quiz.
        protected function filter_body( & $data )
        {
            $data = wp_kses_post( $data );
            $data = apply_filters( 'the_content', $data );
            return $data;
        }

        // Filter "title"/ short content in a quiz.
        protected function filter_short( & $data )
        {
            $data = wp_kses_data( $data );
        }


        /**
         * Database Methods
         */

        function create_draft( $json, $user_id = null )
        {
            global $wpdb, $data;
            $db_name = $wpdb->prefix . 'plugin_slickquiz';

            $data    = json_decode( stripcslashes( $json ) );
            $set     = array();
            $now     = date( 'Y-m-d H:i:s' );
            $user_id = $user_id ? $user_id : get_current_user_id();

            $set['createdDate']     = $now;
            $set['createdBy']       = $user_id;
            $set['lastUpdatedDate'] = $now;
            $set['lastUpdatedBy']   = $user_id;
            $set['name']            = $this->get_name();
            $set['workingQCount']   = $this->get_question_count();
            $set['workingJson']     = json_encode( $data );

            $wpdb->insert( $db_name, $set );
        }

        function update_draft( $json, $id )
        {
            global $wpdb, $data;
            $db_name = $wpdb->prefix . 'plugin_slickquiz';

            $data = json_decode( stripcslashes( $json ) );
            $set  = array();

            $set['lastUpdatedDate'] = date( 'Y-m-d H:i:s' );
            $set['lastUpdatedBy']   = get_current_user_id();
            $set['name']            = $this->get_name();
            $set['workingQCount']   = $this->get_question_count();
            $set['workingJson']     = json_encode( $data );

            $wpdb->update( $db_name, $set, array( 'id' => $id ) );
        }

        function create_published( $json )
        {
            global $wpdb, $data;
            $db_name = $wpdb->prefix . 'plugin_slickquiz';

            $data    = json_decode( stripcslashes( $json ) );
            $set     = array();
            $now     = date( 'Y-m-d H:i:s' );
            $user_id = get_current_user_id();

            $set['createdDate']      = $now;
            $set['createdBy']        = $user_id;
            $set['lastUpdatedDate']  = $now;
            $set['lastUpdatedBy']    = $user_id;
            $set['publishedDate']    = $now;
            $set['publishedBy']      = $user_id;
            $set['name']             = $this->get_name();
            $set['workingQCount']    = $this->get_question_count();
            $set['publishedQCount']  = $set['workingQCount'];
            $set['workingJson']      = json_encode( $data );
            $set['publishedJson']    = $set['workingJson'];
            $set['hasBeenPublished'] = 1;

            $wpdb->insert( $db_name, $set );
        }

        function update_published( $json, $id )
        {
            global $wpdb, $data;
            $db_name = $wpdb->prefix . 'plugin_slickquiz';

            $data    = json_decode( stripcslashes( $json ) );
            $set     = array();
            $now     = date( 'Y-m-d H:i:s' );
            $user_id = get_current_user_id();

            $set['lastUpdatedDate']  = $now;
            $set['lastUpdatedBy']    = $user_id;
            $set['publishedDate']    = $now;
            $set['publishedBy']      = $user_id;
            $set['name']             = $this->get_name();
            $set['workingQCount']    = $this->get_question_count();
            $set['publishedQCount']  = $set['workingQCount'];
            $set['workingJson']      = json_encode( $data );
            $set['publishedJson']    = $set['workingJson'];
            $set['hasBeenPublished'] = 1;

            $wpdb->update( $db_name, $set, array( 'id' => $id ) );
        }

        function discard_draft( $json, $id, $updatedOn )
        {
            global $wpdb, $data;
            $db_name = $wpdb->prefix . 'plugin_slickquiz';

            $data = json_decode( stripcslashes( $json ) );
            $set  = array();

            $set['lastUpdatedDate'] = $updatedOn;
            $set['workingQCount']   = $this->get_question_count();
            $set['workingJson']     = json_encode( $data );

            $wpdb->update( $db_name, $set, array( 'id' => $id ) );
        }

        function unpublish( $id )
        {
            global $wpdb, $data;
            $db_name = $wpdb->prefix . 'plugin_slickquiz';

            $set = array();
            $set['lastUpdatedDate']  = date( 'Y-m-d H:i:s' );
            $set['lastUpdatedBy']    = get_current_user_id();

            $wpdb->query( $wpdb->prepare( "
                UPDATE `$db_name`
                SET
                  `lastUpdatedDate` = %s,
                  `lastUpdatedBy` = %d,
                  `publishedQCount` = NULL,
                  `publishedJson` = NULL
                WHERE id = %d",
              $set['lastUpdatedDate'], $set['lastUpdatedBy'], $id ) );
        }

        function delete( $id )
        {
            global $wpdb, $data;
            $db_name = $wpdb->prefix . 'plugin_slickquiz';
            $wpdb->query( $wpdb->prepare(
                "DELETE FROM `$db_name` WHERE `id` = %d", $id ) );
        }


        // Score Methods

        function save_score( $json, $user_id = null )
        {
            global $wpdb, $data;
            $db_name = $wpdb->prefix . 'plugin_slickquiz_scores';

            $data    = json_decode( stripcslashes( $json ) );
            $set     = array();
            $now     = date( 'Y-m-d H:i:s' );
            $user_id = $user_id ? $user_id : get_current_user_id();

            $set['name']        = $data->name;
            $set['email']       = $data->email;
            $set['score']       = $data->score;
            $set['quiz_id']     = $data->quiz_id;
            $set['createdBy']   = $user_id;
            $set['createdDate'] = $now;

            $wpdb->insert( $db_name, $set );
        }

        function delete_score( $id )
        {
            global $wpdb, $data;
            $db_name = $wpdb->prefix . 'plugin_slickquiz_scores';
            $wpdb->query( $wpdb->prepare(
                "DELETE FROM $db_name WHERE id = %d", $id ) );
        }
    }
}

?>

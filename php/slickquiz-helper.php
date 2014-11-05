<?php

// Stop direct call
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
    die( 'You are not allowed to call this page directly.' );
}

if ( !class_exists( 'SlickQuizHelper' ) ) {
    class SlickQuizHelper
    {

        /**
         * Statuses
         */
        const PUBLISHED           = 'Published';
        const UNPUBLISHED_CHANGES = 'Unpublished Changes';
        const NOT_PUBLISHED       = 'Not Published';

        var $adminOptionsName = "slick_quiz_options";
        var $adminOptions     = array();


        // Set and return Admin Options
        function get_admin_options()
        {
            $this->adminOptions = apply_filters( 'slickquiz_admin_options', array(
                'disabled_quiz_message'        => '<strong>Sorry.</strong> The requested quiz has been disabled.',
                'missing_quiz_message'         => '<strong>Sorry.</strong> The requested quiz could not be found.',
                'start_button_text'            => 'Get Started!',
                'check_answer_text'            => 'Check My Answer!',
                'next_question_text'           => 'Next &raquo;',
                'complete_button_text'         => '',
                'back_button_text'             => '',
                'try_again_text'               => '',
                'your_score_text'              => 'Your Score:',
                'your_ranking_text'            => 'Your Ranking:',
                'score_template_text'          => '%score / %total',
                'question_count_text'          => 'Question %current of %total',
                'question_template_text'       =>  '%count. %text',
                'skip_start_button'            => '0',
                'number_of_questions'          => '',
                'random_sort_questions'        => '0',
                'random_sort_answers'          => '0',
                'random_sort'                  => '0',
                'disable_next'                 => '0',
                'perquestion_responses'        => '1',
                'perquestion_response_answers' => '0',
                'completion_responses'         => '0',
                'question_count'               => '1',
                'question_number'              => '1',
                'score_as_percentage'          => '0',
                'disable_score'                => '0',
                'disable_ranking'              => '0',
                'save_scores'                  => '0',
                'name_label'                   => 'Your Name:',
                'email_label'                  => '',
                'share_links'                  => '0',
                'share_message'                => 'I\'m a [RANK]! I just scored [SCORE] on the [NAME] quiz!',
                'twitter_account'              => '',
                'no_filter_quizzes'            => '1'
            ) );

            $pluginOptions = get_option( $this->adminOptionsName );

            // If options have been set, override defaults
            if ( !empty( $pluginOptions ) ) {
                foreach ( $pluginOptions as $key => $option )
                    $this->adminOptions[$key] = $option;
            }

            update_option( $this->adminOptionsName, $this->adminOptions );

            return $this->adminOptions;
        }

        // Get requested admin option
        function get_admin_option( $option, $echo = false )
        {
            $value = isset($this->adminOptions[$option])
                ? $this->adminOptions[$option] : NULL;
            if ( $echo ) {
                echo $value;
            } else {
                return $value;
            }
        }

    }
}

?>

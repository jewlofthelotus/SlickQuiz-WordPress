<?php

// Stop direct call
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
    die( 'You are not allowed to call this page directly.' );
}

if ( !class_exists( 'SlickQuizFunctions' ) ) {
    class SlickQuizFunctions extends SlickQuizModel
    {

        // Constructor
        function __construct()
        {
            // Admin
            add_action( 'wp_ajax_create_quiz', array( &$this, 'create_quiz' ) );
            add_action( 'wp_ajax_update_quiz', array( &$this, 'update_quiz' ) );
            add_action( 'wp_ajax_revert_quiz', array( &$this, 'revert_quiz' ) );
            add_action( 'wp_ajax_publish_quiz', array( &$this, 'publish_quiz' ) );
            add_action( 'wp_ajax_unpublish_quiz', array( &$this, 'unpublish_quiz' ) );
            add_action( 'wp_ajax_delete_quiz', array( &$this, 'delete_quiz' ) );

            // Front End
            add_action( 'wp_ajax_save_quiz_score', array( &$this, 'save_quiz_score' ) );
            add_action( 'wp_ajax_nopriv_save_quiz_score', array( &$this, 'save_quiz_score' ) );
        }

        function create_quiz()
        {
            if ( isset( $_POST['json'] ) ) {
                $this->save_working_copy( $_POST['json'] );
                $quiz = $this->get_last_quiz_by_user( get_current_user_id() );
                echo $quiz->id;
            } else {
                echo 'Something went wrong, please try again.';
            }
            die(); // this is required to return a proper result
        }

        function update_quiz()
        {
            if ( isset( $_POST['json'] ) ) {
                $quiz      = $this->get_quiz_by_id( $_GET['id'] );
                $published = $this->get_quiz_status( $quiz ) == self::NOT_PUBLISHED ? false : true;
                $this->update_working_copy( $_POST['json'], $quiz->id, $published );
                echo $quiz->id;
            } else {
                echo 'Something went wrong, please try again.';
            }
            die(); // this is required to return a proper result
        }

        function revert_quiz()
        {
            $quiz = $this->get_quiz_by_id( $_GET['id'] );
            $this->revert_to_published_copy( $quiz->publishedJson, $quiz->id, $quiz->publishedDate );
            die();
        }

        function publish_quiz()
        {
            $quiz = $this->get_quiz_by_id( $_GET['id'] );
            $this->update_published_copy( $quiz->workingJson, $quiz->id );
            die();
        }

        function unpublish_quiz()
        {
            $this->unpublish( $_GET['id'] );
            die();
        }

        function delete_quiz()
        {
            $this->delete( $_GET['id'] );
            die();
        }

        function save_quiz_score()
        {
            if ( isset( $_POST['json'] ) ) {
                $this->save_score( $_POST['json'] );
            } else {
                echo 'Something went wrong, please try again.';
            }
            die(); // this is required to return a proper result
        }

    }
}

?>

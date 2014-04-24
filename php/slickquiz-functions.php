<?php

// Stop direct call
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
    die( 'You are not allowed to call this page directly.' );
}

if ( !class_exists( 'SlickQuizFunctions' ) ) {
    class SlickQuizFunctions extends SlickQuizModel
    {

        const SAVE_ACTION = 'slickquiz_save_quiz';


        // Constructor
        function __construct()
        {
            // Admin
            add_action( 'wp_ajax_create_draft_quiz', array( &$this, 'create_draft_quiz' ) );
            add_action( 'wp_ajax_create_published_quiz', array( &$this, 'create_published_quiz' ) );
            add_action( 'wp_ajax_update_draft_quiz', array( &$this, 'update_draft_quiz' ) );
            add_action( 'wp_ajax_update_published_quiz', array( &$this, 'update_published_quiz' ) );
            add_action( 'wp_ajax_discard_draft_quiz', array( &$this, 'discard_draft_quiz' ) );
            add_action( 'wp_ajax_unpublish_quiz', array( &$this, 'unpublish_quiz' ) );
            add_action( 'wp_ajax_delete_quiz', array( &$this, 'delete_quiz' ) );
            add_action( 'wp_ajax_delete_quiz_score', array( &$this, 'delete_quiz_score' ) );

            // Front End
            add_action( 'wp_ajax_save_quiz_score', array( &$this, 'save_quiz_score' ) );
            add_action( 'wp_ajax_nopriv_save_quiz_score', array( &$this, 'save_quiz_score' ) );
        }

        function create_draft_quiz()
        {
            if ( isset( $_POST['json'] ) ) {
                $this->create_draft( $_POST['json'] );
                $quiz = $this->get_last_quiz_by_user( get_current_user_id() );

                // #58: Action for each of the four create/ update Ajax responders.
                do_action(self::SAVE_ACTION, $quiz, 'create_draft');
                echo $quiz->id;
            } else {
                echo 'Something went wrong, please try again.';
            }
            die(); // this is required to return a proper result
        }

        function create_published_quiz()
        {
            if ( isset( $_POST['json'] ) ) {
                $this->create_published( $_POST['json'] );
                $quiz = $this->get_last_quiz_by_user( get_current_user_id() );

                do_action(self::SAVE_ACTION, $quiz, 'create_published');
                echo $quiz->id;
            } else {
                echo 'Something went wrong, please try again.';
            }
            die(); // this is required to return a proper result
        }

        function update_draft_quiz()
        {
            if ( isset( $_POST['json'] ) ) {
                $quiz = $this->get_quiz_by_id( $_GET['id'] );
                $this->update_draft( $_POST['json'], $quiz->id );

                do_action(self::SAVE_ACTION, $quiz, 'update_draft');
                echo $quiz->id;
            } else {
                echo 'Something went wrong, please try again.';
            }
            die(); // this is required to return a proper result
        }

        function update_published_quiz()
        {
            if ( isset( $_POST['json'] ) ) {
                $quiz = $this->get_quiz_by_id( $_GET['id'] );
                $this->update_published( $_POST['json'], $quiz->id );

                do_action(self::SAVE_ACTION, $quiz, 'update_published');
                echo $quiz->id;
            } else {
                echo 'Something went wrong, please try again.';
            }
            die(); // this is required to return a proper result
        }

        function discard_draft_quiz()
        {
            $quiz = $this->get_quiz_by_id( $_GET['id'] );
            $this->discard_draft( $quiz->publishedJson, $quiz->id, $quiz->publishedDate );
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

        function delete_quiz_score()
        {
            $this->delete_score( $_GET['id'] );
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

<?php

// Stop direct call
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
    die( 'You are not allowed to call this page directly.' );
}

if ( !class_exists( 'SlickQuizPreview' ) ) {
    class SlickQuizPreview extends SlickQuizModel
    {

        function __construct()
        {
            // Get Plugin Options
            global $pluginOptions;
            $this->get_admin_options();

            // Load Resources
            $mainPluginFile = dirname(dirname(__FILE__)) . '/slickquiz.php';
            wp_enqueue_script( 'slickquiz_js', plugins_url( '/slickquiz/js/slickQuiz.js', $mainPluginFile ) );
            wp_enqueue_style( 'slickquiz_css', plugins_url( '/slickquiz/css/slickQuiz.css', $mainPluginFile ) );
        }

        function get_quiz_json()
        {
            $quiz = $this->get_quiz_by_id( $_GET['id'] );
            $published = $this->get_quiz_status( $quiz ) == self::NOT_PUBLISHED ? false : true;
            echo !isset( $_GET['readOnly'] ) || !$published ? $quiz->workingJson : $quiz->publishedJson;
        }

    }
}

if ( class_exists( 'SlickQuizPreview' ) ) {
    global $slickQuizPreview;
    $slickQuizPreview = new SlickQuizPreview();
}

?>

<div id="preview" class="quizPreview SlickQuiz slickQuizWrapper">
    <h1>Preview Quiz</h1>
    <p class="previewNote"><strong>Note:</strong> Your styles may very.</p>

    <div class="top_button_bar">
        <a class="button reload" href="#" title="Reload">
            <img alt="Reload" src="<?php echo plugins_url( '/images/arrow_refresh.png' , dirname( __FILE__ ) ); ?>" width="16" height="16" /> Reload
        </a>
        <?php if ( !isset( $_GET['readOnly'] ) ) { ?>
        <a class="button continueEditing" href="#" title="Continue Editing">
            <img alt="Continue Editing" src="<?php echo plugins_url( '/images/remove.png' , dirname( __FILE__ ) ); ?>" width="16" height="16" /> Continue Editing
        </a>
        <?php } else { ?>
        <a class="button continueEditing" href="#" title="Close">
            <img alt="Close" src="<?php echo plugins_url( '/images/remove.png' , dirname( __FILE__ ) ); ?>" width="16" height="16" /> Close
        </a>
        <?php } ?>
    </div>

    <h2 class="quizName"></h2>

    <div class="quizArea">
        <div class="quizHeader">
            <div class="buttonWrapper"><a class="button startQuiz"><?php $slickQuizPreview->get_admin_option( 'start_button_text', true ); ?></a></div>
        </div>
    </div>

    <div class="quizResults">
        <div class="quizResultsCopy">
            <h3 class="quizScore"><?php $slickQuizPreview->get_admin_option( 'your_score_text', true ); ?> <span>&nbsp;</span></h3>
            <h3 class="quizLevel"><?php $slickQuizPreview->get_admin_option( 'your_ranking_text', true ); ?> <span>&nbsp;</span></h3>
        </div>
    </div>

    <?php if ( !isset( $_GET['readOnly'] ) ) { ?>
    <div class="bottom_button_bar">
        <button class="button saveClose publish" title="Save this quiz and publish it." value="Publish">
            <img alt="Publish" height="16" src="<?php echo plugins_url( '/images/save.png' , dirname( __FILE__ ) ); ?>" width="16"> Publish
        </button>
    </div>
    <?php } ?>

    <script type="text/javascript">
        jQuery(document).ready(function($) {
            $('.SlickQuiz').slickQuiz({
                json:                        <?php $slickQuizPreview->get_quiz_json(); ?>,
                checkAnswerText:             "<?php $slickQuizPreview->get_admin_option( 'check_answer_text', true ) ?>",
                nextQuestionText:            "<?php $slickQuizPreview->get_admin_option( 'next_question_text', true ) ?>",
                backButtonText:              "<?php $slickQuizPreview->get_admin_option( 'back_button_text', true ) ?>",
                randomSortQuestions:         <?php echo( $slickQuizPreview->get_admin_option( 'random_sort_questions' ) == '1' ? 'true' : 'false' ) ?>,
                randomSortAnswers:           <?php echo( $slickQuizPreview->get_admin_option( 'random_sort_answers' ) == '1' ? 'true' : 'false' ) ?>,
                randomSort:                  <?php echo( $slickQuizPreview->get_admin_option( 'random_sort' ) == '1' ? 'true' : 'false' ) ?>,
                preventUnanswered:           <?php echo( $slickQuizPreview->get_admin_option( 'disable_next' ) == '1' ? 'true' : 'false' ) ?>,
                disableResponseMessaging:    <?php echo( $slickQuizPreview->get_admin_option( 'disable_responses' ) == '1' ? 'true' : 'false' ) ?>,
                completionResponseMessaging: <?php echo( $slickQuizPreview->get_admin_option( 'completion_responses' ) == '1' ? 'true' : 'false' ) ?>
            });
        });
    </script>
</div>

<?php

// Stop direct call
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
    die( 'You are not allowed to call this page directly.' );
}

if ( !class_exists( 'SlickQuizEdit' ) ) {
    class SlickQuizEdit extends SlickQuizModel
    {

        var $quiz = '';
        var $statusBtn = '';


        // Constructor
        function __construct()
        {
            global $quiz, $pluginOptions;

            $this->get_admin_options();

            $quiz = $this->get_quiz_by_id( $_GET['id'] );

            // Add Form JS
            // wp_enqueue_script( 'tiny_mce' );
            // the_editor('', 'quizContent');
        }

        function get_quiz_status_info()
        {
            global $quiz, $statusBtn;
            $statusBtn = $this->get_quiz_status_button( $quiz, true );
            echo $statusBtn;
        }

        function get_quiz_json()
        {
            global $quiz;
            echo $quiz->workingJson;
        }

        function show_discard_option()
        {
            global $statusBtn;
            $pos = strpos( $statusBtn, "title='" . self::UNPUBLISHED_CHANGES . "'" );
            return ( $pos === false ? false : true );
        }

        function show_alert_messages()
        {
            if ( isset( $_GET['success'] ) )
                echo '<div id="message" class="updated"><p>Your changes were saved.</p></div>';
        }

    }
}

if ( class_exists( 'SlickQuizEdit' ) ) {
    global $slickQuizEdit;
    $slickQuizEdit = new SlickQuizEdit();
}

?>
<div class="wrap slickQuiz slickQuizWrapper">
    <?php $slickQuizEdit->show_alert_messages(); ?>

    <h2 class="notPublished">Edit Quiz</h2>

    <div class="floatLeft">
        <p class="statusLegend">
            <strong>Status:</strong> &nbsp;&nbsp;&nbsp; <?php $slickQuizEdit->get_quiz_status_info(); ?>
        </p>

        <p class="required">All fields marked with <img alt="*" height="16" src="<?php echo plugins_url( '/images/required.png' , dirname( __FILE__ ) ); ?>" width="16" /> are required.</p>
    </div>

    <div class="floatRight">
        <div class="top_button_bar">
            <?php if ( $slickQuizEdit->show_discard_option() ) { ?>
            <button class="button discard" title="Discard drafted changes" value="Discard drafted changes">
                <img alt="Discard Draft" height="16" src="<?php echo plugins_url( '/images/arrow_undo.png' , dirname( __FILE__ ) ); ?>" width="16" /> Discard Draft
            </button>
            <?php } ?>
            <a class="button" href="<?php echo admin_url( 'admin.php?page=slickquiz' ); ?>" title="Cancel this action">
                <img alt="Cancel" src="<?php echo plugins_url( '/images/remove.png' , dirname( __FILE__ ) ); ?>" width="16" height="16" /> Cancel
            </a>
        </div>
    </div>

    <div class="quizFormWrapper">
    </div>

    <div class="bottom_button_bar">
        <button class="button publish" title="Save this quiz and publish it." value="Publish">
            <img alt="Publish" height="16" src="<?php echo plugins_url( '/images/save.png' , dirname( __FILE__ ) ); ?>" width="16"> Publish
        </button>
        <?php if ( $slickQuizEdit->show_discard_option() ) { ?>
        <button class="button discard" title="Discard drafted changes" value="Discard drafted changes">
            <img alt="Discard Draft" height="16" src="<?php echo plugins_url( '/images/arrow_undo.png' , dirname( __FILE__ ) ); ?>" width="16" /> Discard Draft
        </button>
        <?php } ?>
        <button class="button draft" title="Save this quiz as a draft." value="Draft">
            <img alt="Save Draft" height="16" src="<?php echo plugins_url( '/images/save.png' , dirname( __FILE__ ) ); ?>" width="16"> Save Draft
        </button>
        <button class="button preview" title="Save a draft and preview it." value="Preview">
            <img alt="Preview" height="16" src="<?php echo plugins_url( '/images/view.png' , dirname( __FILE__ ) ); ?>" width="16" /> Preview
        </button>
        <p class="previewNote"><em>Previewing will save changes to a draft version.</em></p>
    </div>
</div>

<script type="text/javascript">
    var quizJSON = <?php $slickQuizEdit->get_quiz_json(); ?>;
    var disableRanking = <?php echo( $slickQuizEdit->get_admin_option( 'disable_ranking' ) == '1' ? 'true' : 'false' ); ?>;
</script>

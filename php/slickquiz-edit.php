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
            global $quiz;
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

        function show_revert_option()
        {
            global $statusBtn;
            $pos = strpos( $statusBtn, "title='" . self::UNPUBLISHED_CHANGES . "'" );
            return ( $pos === false ? false : true );
        }

    }
}

if ( class_exists( 'SlickQuizEdit' ) ) {
    global $slickQuizEdit;
    $slickQuizEdit = new SlickQuizEdit();
}

?>

<div class="wrap slickQuizWrapper">
    <div class="floatLeft">
        <h2 class="notPublished">Edit Quiz</h2>

        <p class="statusLegend">
            <strong>Status:</strong> &nbsp;&nbsp;&nbsp; <?php $slickQuizEdit->get_quiz_status_info(); ?>
        </p>
    </div>

    <div class="floatRight">
        <div class="top_button_bar">
            <?php if ( $slickQuizEdit->show_revert_option() ) { ?>
            <button class="button revert" title="Revert to previously published copy of the quiz (what is currently in Prod)" value="Revert to Published Copy">
                <img alt="Revert" height="16" src="<?php echo plugins_url( '/images/arrow_undo.png' , dirname( __FILE__ ) ); ?>" width="16" /> Revert to Published Copy
            </button>
            <?php } ?>
            <a class="button" href="<?php echo admin_url( 'admin.php?page=slickquiz' ); ?>" title="Cancel this action">
                <img alt="Cancel" src="<?php echo plugins_url( '/images/remove.png' , dirname( __FILE__ ) ); ?>" width="16" height="16" /> Cancel
            </a>
        </div>

        <p class="required">All fields marked with <img alt="*" height="16" src="<?php echo plugins_url( '/images/required.png' , dirname( __FILE__ ) ); ?>" width="16" /> are required.</p>
    </div>

    <div class="quizFormWrapper">
    </div>

    <div class="bottom_button_bar">
        <button class="button preview" title="Don't save this quiz, but open a new window with a preview of the page using this content." value="Preview">
            <img alt="Preview" height="16" src="<?php echo plugins_url( '/images/save.png' , dirname( __FILE__ ) ); ?>" width="16" /> Preview
        </button>
        <p class="previewNote">Previewing will save all changes to the working copy.<br/><strong>You must <em>Preview</em> your changes in order to <em>Publish</em>.</strong></p>
    </div>
</div>

<script type="text/javascript">
    var quizJSON = <?php $slickQuizEdit->get_quiz_json(); ?>;
</script>

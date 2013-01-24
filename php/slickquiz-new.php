<?php

// Stop direct call
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
    die( 'You are not allowed to call this page directly.' );
}

if ( !class_exists( 'SlickQuizNew' ) ) {
    class SlickQuizNew extends SlickQuizModel
    {

        // Constructor
        function __construct()
        {
            // Add Form JS
            // wp_enqueue_script( 'tiny_mce' );
            // the_editor('', 'quizContent');
        }

    }
}

if ( class_exists( 'SlickQuizNew' ) ) {
    global $slickQuizNew;
    $slickQuizNew = new SlickQuizNew();
}

?>

<div class="wrap slickQuizWrapper">
    <div class="floatLeft">
        <h2 class="notPublished">Create A New Quiz</h2>
    </div>

    <div class="floatRight">
        <div class="top_button_bar">
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

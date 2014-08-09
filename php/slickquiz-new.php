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
            global $pluginOptions;

            $this->get_admin_options();

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

<div class="wrap slickQuiz slickQuizWrapper">
    <div class="floatLeft">
        <h2 class="notPublished">Create A New Quiz</h2>
        <p class="required">All fields marked with <img alt="*" height="16" src="<?php echo plugins_url( '/images/required.png' , dirname( __FILE__ ) ); ?>" width="16" /> are required.</p>
    </div>

    <div class="floatRight">
        <div class="top_button_bar">
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
        <button class="button draft" title="Save this quiz as a draft." value="Draft">
            <img alt="Draft" height="16" src="<?php echo plugins_url( '/images/save.png' , dirname( __FILE__ ) ); ?>" width="16"> Save Draft
        </button>
        <button class="button preview" title="Save a draft and preview it." value="Preview">
            <img alt="Preview" height="16" src="<?php echo plugins_url( '/images/view.png' , dirname( __FILE__ ) ); ?>" width="16" /> Preview
        </button>
        <p class="previewNote"><em>Previewing will save changes to a draft version.</em></p>
    </div>
</div>

<script type="text/javascript">
    var disableRanking = <?php echo( $slickQuizNew->get_admin_option( 'disable_ranking' ) == '1' ? 'true' : 'false' ); ?>;
</script>

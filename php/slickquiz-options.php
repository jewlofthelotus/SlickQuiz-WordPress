<?php

// Stop direct call
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
    die( 'You are not allowed to call this page directly.' );
}

if ( !class_exists( 'SlickQuizOptions' ) ) {
    class SlickQuizOptions extends SlickQuiz
    {

        var $updated = false;


        function __construct()
        {
            global $updated;

            $this->get_admin_options();

            if ( isset( $_POST['slickQuizOptions'] ) ) {
                $this->adminOptions = array_merge( $this->adminOptions, $_POST['slickQuizOptions'] );
                update_option( $this->adminOptionsName, $this->adminOptions );
                $updated = true;
            }
        }

        function show_alert_messages()
        {
            global $updated;
            if ( $updated )
                echo '<p class="success">Your quiz options have been updated.</p>';
        }

    }
}

if ( class_exists( 'SlickQuizOptions' ) ) {
    global $slickQuizOptions;
    $slickQuizOptions = new SlickQuizOptions();
}

?>

<div class="wrap quizOptions">
    <?php $slickQuizOptions->show_alert_messages(); ?>

    <h2>SlickQuiz Options</h2>

    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
        <div>
            <label>Text to display on the Quiz <em>START</em> button:</label>
            <input type="text" name="slickQuizOptions[start_button_text]"
                value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'start_button_text' ) ), 'SlickQuizPlugin' ); ?>" />
        </div>

        <div>
            <label>Text to display on the <em>CHECK ANSWER</em> buttons:</label>
            <input type="text" name="slickQuizOptions[check_answer_text]"
                value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'check_answer_text' ) ), 'SlickQuizPlugin' ); ?>" />
        </div>

        <div>
            <label>Text to display on the <em>NEXT QUESTION</em> buttons:</label>
            <input type="text" name="slickQuizOptions[next_question_text]"
                value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'next_question_text' ) ), 'SlickQuizPlugin' ); ?>" />
        </div>

        <div>
            <label>Text to display on the <em>BACK</em> buttons:<br />
                <small><em>(If left blank, no BACK buttons will be displayed.)</em></small></label>
            <input type="text" name="slickQuizOptions[back_button_text]"
                value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'back_button_text' ) ), 'SlickQuizPlugin' ); ?>" />
        </div>

        <div>
            <label>Randomly sort questions and answers?</label>
            <input type="radio" name="slickQuizOptions[random_sort]" value="0"
                <?php $slickQuizOptions->get_admin_option( 'random_sort' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
            <input type="radio" name="slickQuizOptions[random_sort]" value="1"
                <?php $slickQuizOptions->get_admin_option( 'random_sort' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
        </div>

        <div>
            <label>Text to display next to <em>SCORE</em> result:</label>
            <input type="text" name="slickQuizOptions[your_score_text]"
                value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'your_score_text' ) ), 'SlickQuizPlugin' ); ?>" />
        </div>

        <div>
            <label>Text to display next to <em>RANKING</em> result:</label>
            <input type="text" name="slickQuizOptions[your_ranking_text]"
                value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'your_ranking_text' ) ), 'SlickQuizPlugin' ); ?>" />
        </div>

        <div>
            <label>Message to display if requested quiz is <em>DISABLED</em>:</label>
            <input type="text" name="slickQuizOptions[disabled_quiz_message]"
                value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'disabled_quiz_message' ) ), 'SlickQuizPlugin' ); ?>" />
        </div>

        <div>
            <label>Message to display if requested quiz is <em>MISSING</em>:</label>
            <input type="text" name="slickQuizOptions[missing_quiz_message]"
                value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'missing_quiz_message' ) ), 'SlickQuizPlugin' ); ?>" />
        </div>

        <div class="submit">
            <input type="submit" name="slickQuizOptionsSubmit" value="<?php _e('Update Options', 'SlickQuizPlugin') ?>" />
        </div>
    </form>
</div>

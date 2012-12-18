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
        <h3 class="title">Default Text Copy</h3>

        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[start_button_text]"><em>START</em> button text</label>
                    </th>
                    <td>
                        <input  type="text" name="slickQuizOptions[start_button_text]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'start_button_text' ) ), 'SlickQuizPlugin' ); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[check_answer_text]"><em>CHECK ANSWER</em> button text</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[check_answer_text]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'check_answer_text' ) ), 'SlickQuizPlugin' ); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[next_question_text]"><em>NEXT QUESTION</em> button text</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[next_question_text]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'next_question_text' ) ), 'SlickQuizPlugin' ); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[back_button_text]"><em>BACK</em> button text</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[back_button_text]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'back_button_text' ) ), 'SlickQuizPlugin' ); ?>" /><br />
                        <small><em>(If left blank, no BACK buttons will be displayed.)</em></small></label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[your_score_text]"><em>SCORE</em> result text</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[your_score_text]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'your_score_text' ) ), 'SlickQuizPlugin' ); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[your_ranking_text]"><em>RANKING</em> result text</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[your_ranking_text]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'your_ranking_text' ) ), 'SlickQuizPlugin' ); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[disabled_quiz_message]">Message to display if quiz is <em>DISABLED</em>:</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[disabled_quiz_message]" class="large-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'disabled_quiz_message' ) ), 'SlickQuizPlugin' ); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[missing_quiz_message]">Message to display if quiz is <em>MISSING</em>:</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[missing_quiz_message]" class="large-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'missing_quiz_message' ) ), 'SlickQuizPlugin' ); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[name_label]"><em>USER NAME</em> label text (if saving scores is enabled below)</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[name_label]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'name_label' ) ), 'SlickQuizPlugin' ); ?>" />
                    </td>
                </tr>
            </tbody>
        </table>

        <h3 class="title">Additional Options</h3>

        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[random_sort_questions]">Randomly sort questions ONLY?</label>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[random_sort_questions]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'random_sort_questions' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[random_sort_questions]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'random_sort_questions' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[random_sort_answers]">Randomly sort answers ONLY?</label>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[random_sort_answers]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'random_sort_answers' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[random_sort_answers]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'random_sort_answers' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[random_sort]">Randomly sort questions AND answers?</label>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[random_sort]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'random_sort' ) == '0' ? print_r('checked="checked"') : ''; ?> /> Default to above selections &nbsp;
                        <input type="radio" name="slickQuizOptions[random_sort]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'random_sort' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                        <br /><small><em>(<strong>NOTE:</strong> Selecting "Yes" will override the above selections to randomly sort ONLY questions or answers.)</em></small>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[disable_next]">Prevent submitting a question if no answers have been selected?</label>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[disable_next]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'disable_next' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[disable_next]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'disable_next' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[completion_responses]">Display correct / incorrect response messaging <em>upon quiz completion</em> (rather than after each question)?</label>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[completion_responses]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'completion_responses' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[completion_responses]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'completion_responses' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[disable_responses]">Disable correct / incorrect response messaging <em>entirely</em>?</label>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[disable_responses]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'disable_responses' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[disable_responses]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'disable_responses' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                        <br /><small><em>(<strong>NOTE:</strong> Selecting "Yes" will override the above selection to display messaging upon quiz completion.<br />
                            It will also prevent messaging from displaying after each question.)</em></small>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[save_scores]">Save user scores?</label>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[save_scores]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'save_scores' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[save_scores]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'save_scores' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                        <br /><small><em>(<strong>NOTE:</strong> Selecting "Yes" will require the user to enter their name before proceeding with the quiz.)</em></small>
                    </td>
                </tr>
            </tbody>
        </table>

        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Update Options', 'SlickQuizPlugin') ?>" />
        </p>
    </form>
</div>

<?php

// Stop direct call
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
    die( 'You are not allowed to call this page directly.' );
}

if ( !class_exists( 'SlickQuizOptions' ) ) {
    class SlickQuizOptions extends SlickQuizHelper
    {

        var $updated = false;


        function __construct()
        {
            global $updated, $current_user;

            $this->get_admin_options();

            if ( isset( $_POST['slickQuizOptions'] ) ) {
                $this->adminOptions = array_merge( $this->adminOptions, stripslashes_deep( $_POST['slickQuizOptions'] ) );
                update_option( $this->adminOptionsName, $this->adminOptions );

                add_user_meta( $current_user->ID, 'slickquiz_ignore_notice_disabled', 'true', true );

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

    <h2>SlickQuiz Default Options</h2>

    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
        <h3 class="title">Copy Settings</h3>
        <p>Use the fields below to setup button, label, and messaging copy.</p>
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
                        <label for="slickQuizOptions[try_again_text]"><em>TRY AGAIN</em> button text</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[try_again_text]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'try_again_text' ) ), 'SlickQuizPlugin' ); ?>" /><br />
                        <small><em>(If left blank, no TRY AGAIN buttons will be displayed.)</em></small></label>
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
                        <label for="slickQuizOptions[missing_quiz_message]">Message to display if quiz has been <em>DELETED</em>:</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[missing_quiz_message]" class="large-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'missing_quiz_message' ) ), 'SlickQuizPlugin' ); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[name_label]"><em>USER NAME</em> label text (if saving scores is enabled below and user is not logged in)</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[name_label]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'name_label' ) ), 'SlickQuizPlugin' ); ?>" />
                    </td>
                </tr>
            </tbody>
        </table>

        <h3 class="title">Functionality Settings</h3>
        <p>Adjust the following options to change the way your quizzes behave.</p>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[number_of_questions]">Number of questions to display?</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[number_of_questions]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'number_of_questions' ) ), 'SlickQuizPlugin' ); ?>" />
                        <br /><small><em>(<strong>NOTE:</strong> Leave blank to load all questions.<br />
                            If set, you may want to also enable random (question) sorting to ensure that you get a mixed set of questions each page load.)</em></small>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[skip_start_button]">Skip the "Start" button?</label>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[skip_start_button]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'skip_start_button' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[skip_start_button]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'skip_start_button' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[random_sort_questions]">Randomly sort questions?</label>
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
                        <label for="slickQuizOptions[random_sort_answers]">Randomly sort answers?</label>
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
                        <label for="slickQuizOptions[disable_next]">Prevent submitting a question if no answers have been selected?</label>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[disable_next]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'disable_next' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[disable_next]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'disable_next' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                    </td>
                </tr>
                <tr id="responses" valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[completion_responses]">Display correct / incorrect response messaging <em>after each question</em>?</label>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[perquestion_responses]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'perquestion_responses' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[perquestion_responses]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'perquestion_responses' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[completion_responses]">Display correct / incorrect response messaging <em>upon quiz completion</em>?</label>
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
                        <label for="slickQuizOptions[save_scores]">Save user scores?</label>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[save_scores]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'save_scores' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[save_scores]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'save_scores' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                        <br /><small><em>(<strong>NOTE:</strong> Selecting "Yes" will require users who are not logged in to enter their name before proceeding with the quiz.)</em></small>
                    </td>
                </tr>
            </tbody>
        </table>

        <h3 class="title">Sharing Options</h3>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[share_links]">Enable sharing (twitter and facebook) buttons?</label>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[share_links]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'share_links' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[share_links]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'share_links' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[share_message]">Share message:</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[share_message]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'share_message' ) ), 'SlickQuizPlugin' ); ?>" />
                        <br /><small><em>(<strong>NOTE:</strong> You can use the following shortcodes to insert the quiz name [NAME], score [SCORE], and rank [RANK].</em></small>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[twitter_account]">Twitter username to use with sharing button:</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[twitter_account]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'twitter_account' ) ), 'SlickQuizPlugin' ); ?>" />
                    </td>
                </tr>
            </tbody>
        </table>

		<?php do_action( 'slickquiz_after_options', $slickQuizOptions ); ?>

        <p class="submit">
            <input type="submit" name="submit" id="submit" class="button-primary" value="<?php _e('Update Options', 'SlickQuizPlugin') ?>" />
        </p>
    </form>
</div>

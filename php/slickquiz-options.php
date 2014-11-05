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
                // Handle checkboxes
                if ( isset( $_POST['slickQuizOptions']['perquestion_response_answers'] ) != true ) {
                    $_POST['slickQuizOptions']['perquestion_response_answers'] = '0';
                }
                if ( isset( $_POST['slickQuizOptions']['score_as_percentage'] ) != true ) {
                    $_POST['slickQuizOptions']['score_as_percentage'] = '0';
                }

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
                echo '<div id="message" class="updated"><p>Your quiz options have been updated.</p></div>';
        }

    }
}

if ( class_exists( 'SlickQuizOptions' ) ) {
    global $slickQuizOptions;
    $slickQuizOptions = new SlickQuizOptions();
}

?>

<div class="wrap slickQuiz quizOptions">
    <?php $slickQuizOptions->show_alert_messages(); ?>

    <h2>SlickQuiz Default Options</h2>

    <form method="post" action="<?php echo $_SERVER["REQUEST_URI"]; ?>">
        <h3 class="title">Copy Settings</h3>
        <p>Use the fields below to setup button, label, and messaging copy.</p>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row" style="width: 250px;">
                        <label for="slickQuizOptions[start_button_text]"><em>START</em> button text</label>
                    </th>
                    <td>
                        <input  type="text" name="slickQuizOptions[start_button_text]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'start_button_text' ) ), 'SlickQuizPlugin' ); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row" style="width: 250px;">
                        <label for="slickQuizOptions[question_count_text]"><em>QUESTION COUNT</em> label</label>
                    </th>
                    <td>
                        <input  type="text" name="slickQuizOptions[question_count_text]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'question_count_text' ) ), 'SlickQuizPlugin' ); ?>" />
                            <br /><small><em>The format of the question number and question. <code>%current</code> and <code>%total</code> are placeholders that will output the appropriate values.
                            <br />Will only display if "Display the question count?" is enabled.</em></small>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row" style="width: 250px;">
                        <label for="slickQuizOptions[question_template_text]"><em>QUESTION TEMPLATE</em> text</label>
                    </th>
                    <td>
                        <input  type="text" name="slickQuizOptions[question_template_text]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'question_template_text' ) ), 'SlickQuizPlugin' ); ?>" />
                            <br /><small><em>The format of the question number and question. <code>%count</code> and <code>%text</code> are placeholders that will output the appropriate values.
                            <br /><code>%count</code> will only be available if "Display the question number?" is enabled</em></small>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[check_answer_text]"><em>CHECK ANSWER</em> button text</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[check_answer_text]" class="regular-text" required
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'check_answer_text' ) ), 'SlickQuizPlugin' ); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[next_question_text]"><em>NEXT QUESTION</em> button text</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[next_question_text]" class="regular-text" required
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
                        <small><em>If left blank, no BACK buttons will be displayed.</em></small></label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[complete_button_text]"><em>COMPLETE QUIZ</em> button text</label><br/>
                        <small><em>ex. "Get Your Results!"</em></small></label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[complete_button_text]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'complete_button_text' ) ), 'SlickQuizPlugin' ); ?>" /><br />
                        <small><em>If left blank, the "NEXT QUESTION" value will be used.</em></small></label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[try_again_text]"><em>TRY AGAIN</em> button text</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[try_again_text]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'try_again_text' ) ), 'SlickQuizPlugin' ); ?>" /><br />
                        <small><em>If left blank, no TRY AGAIN buttons will be displayed.</em></small></label>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[your_score_text]"><em>SCORE</em> result label</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[your_score_text]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'your_score_text' ) ), 'SlickQuizPlugin' ); ?>" />
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[score_template_text]"><em>SCORE TEMPLATE</em> text</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[score_template_text]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'score_template_text' ) ), 'SlickQuizPlugin' ); ?>" />
                            &nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="slickQuizOptions[score_as_percentage]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'score_as_percentage' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Display score as percentage
                            <br /><small><em>The format of the final score text. <code>%score</code> and <code>%total</code> are placeholders that will output the appropriate values.<br/>
                            If you enable "Display score as percentage," you will likely want to adjust the SCORE TEMPLATE text appropriately.</em></small>
</em></small>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[your_ranking_text]"><em>RANKING</em> result label</label>
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
            </tbody>
        </table>

        <h3 class="title">Functionality Settings</h3>
        <p>Adjust the following options to change the way your quizzes behave.</p>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row" style="width: 250px;">
                        <label for="slickQuizOptions[number_of_questions]">Number of questions to display?</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[number_of_questions]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'number_of_questions' ) ), 'SlickQuizPlugin' ); ?>" />
                        <br /><small><em>Leave blank to load all questions.<br />
                            If set, you may want to also enable random (question) sorting to ensure that you get a mixed set of questions each page load.</em></small>
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
                        <label for="slickQuizOptions[perquestion_responses]">Display correct / incorrect response messaging <em>after each question</em>?</label>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[perquestion_responses]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'perquestion_responses' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[perquestion_responses]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'perquestion_responses' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                        &nbsp;&nbsp;&nbsp;&nbsp; <input type="checkbox" name="slickQuizOptions[perquestion_response_answers]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'perquestion_response_answers' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Also display answer options
                        <br /><small><em>Selecting "Yes" will display result messaging after submitting each answer.<br />
                            When enabled, additionally enabling "Also display answer options" will include the answer options with results.</em></small>
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
                        <br /><small><em>Selecting "Yes" will display the answer options along with result messaging at the end of the quiz.</em></small>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[question_count]">Display the question count?</label>
                        <br /><small><em>ie. "Question 3 of 10"</em></small>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[question_count]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'question_count' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[question_count]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'question_count' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[question_number]">Display the question number?</label>
                        <br /><small><em>ie. the "1." in "1. What color is displayed?"</em></small>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[question_number]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'question_number' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[question_number]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'question_number' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[disable_score]">Prevent the score from displaying with the results?</label>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[disable_score]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'disable_score' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[disable_score]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'disable_score' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                        <br /><small><em>Note: Scores will still be saved, if "Save user scores?" is enabled.</em></small>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[disable_ranking]">Prevent the ranking level from displaying with the results?</label>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[disable_ranking]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'disable_ranking' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[disable_ranking]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'disable_ranking' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                        <br /><small><em>Note: If disabled, ranking levels will no longer be required during quiz creation.</em></small>
                    </td>
                </tr>
            </tbody>
        </table>

        <h3 class="title">Score Saving Options</h3>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row" style="width: 250px;">
                        <label for="slickQuizOptions[save_scores]">Save user scores?</label>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[save_scores]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'save_scores' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[save_scores]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'save_scores' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                        <br /><small><em>Selecting "Yes" will require users who are not logged in to enter their name before proceeding with the quiz.</em></small>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[name_label]"><em>User NAME</em> label text</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[name_label]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'name_label' ) ), 'SlickQuizPlugin' ); ?>" />
                        <br /><small><em>This field will only display if saving scores is enabled and the user is not logged in.<br />
                            User names will ALWAYS be saved for logged in users if score saving is enabled.</em></small>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[email_label]"><em>User EMAIL</em> label text</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[email_label]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'email_label' ) ), 'SlickQuizPlugin' ); ?>" />
                        <br /><small><em>This field will only display if saving scores is enabled and the user is not logged in.<br />
                            Email addresses will ALWAYS be saved for logged in users if score saving is enabled.</em></small>
                    </td>
                </tr>
            </tbody>
        </table>

        <h3 class="title">Sharing Options</h3>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row" style="width: 250px;">
                        <label for="slickQuizOptions[share_links]">Enable sharing (twitter, facebook, and email) buttons?</label>
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
                        <br /><small><em>You can use the following shortcodes to insert the quiz name [NAME], score [SCORE], and rank [RANK].</em></small>
                    </td>
                </tr>
                <tr valign="top">
                    <th scope="row">
                        <label for="slickQuizOptions[twitter_account]">Twitter username to use with sharing button:</label>
                    </th>
                    <td>
                        <input type="text" name="slickQuizOptions[twitter_account]" class="regular-text"
                            value="<?php _e( apply_filters( 'format_to_edit', $slickQuizOptions->get_admin_option( 'twitter_account' ) ), 'SlickQuizPlugin' ); ?>" />
                        <br /><small><em>You do NOT need to include the @ symbol.</em></small>
                    </td>
                </tr>
            </tbody>
        </table>

        <h3 class="title">Other</h3>
        <table class="form-table">
            <tbody>
                <tr valign="top">
                    <th scope="row" style="width: 250px;">
                        <label for="slickQuizOptions[no_filter_quizzes]">Enable WordPress Filters on quiz content?</label>
                    </th>
                    <td>
                        <input type="radio" name="slickQuizOptions[no_filter_quizzes]" value="0"
                            <?php $slickQuizOptions->get_admin_option( 'no_filter_quizzes' ) == '0' ? print_r('checked="checked"') : ''; ?> /> No &nbsp;
                        <input type="radio" name="slickQuizOptions[no_filter_quizzes]" value="1"
                            <?php $slickQuizOptions->get_admin_option( 'no_filter_quizzes' ) == '1' ? print_r('checked="checked"') : ''; ?> /> Yes
                        <br /><small><em>Enabling fitlers allows for the inclusion of shortcodes and other plugin hooks.<br/>
                                        You should disable this if you're seeing strange output in your quiz.</em></small>
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

<?php

// Stop direct call
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
    die( 'You are not allowed to call this page directly.' );
}

if ( !class_exists( 'SlickQuizFront' ) ) {
    class SlickQuizFront extends SlickQuizModel
    {

        var $quiz = null;
        var $status = null;
        var $pageQuizzes = array();


        // Constructor
        function __construct()
        {
            global $pluginOptions;

            $this->get_admin_options();

            // Add Shortcodes
            add_shortcode( 'slickquiz', array( &$this, 'show_slickquiz_handler' ) );

            // Filter the post/page/widget content for the shortcode, load resources ONLY if present
            add_filter( 'the_content', array( &$this, 'load_resources' ) );
            add_filter( 'widget_text', array( &$this, 'load_resources' ) );

            // Make sure dynamic quiz scripts gets loaded below jQuery
            add_filter( 'wp_footer', array( &$this, 'load_quiz_script' ), 5000 );
        }

        // Add Admin JS and styles
        function load_resources( $content )
        {
            // Only load resources when a shortcode is on the page
            if ( strpos( $content, '[slickquiz' ) === false ) {
                return $content;
            }

            $mainPluginFile = dirname(dirname(__FILE__)) . '/slickquiz.php';

            // Scripts
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'slickquiz_js', plugins_url( '/slickquiz/js/slickQuiz.js', $mainPluginFile ) );

            if ( $this->get_admin_option( 'share_links' ) == '1' ) {
                wp_enqueue_script( 'twitter-widget', 'http://platform.twitter.com/widgets.js' );
            }

            // Styles
            wp_enqueue_style( 'slickquiz_css', plugins_url( '/slickquiz/css/slickQuiz.css', $mainPluginFile ) );
            wp_enqueue_style( 'slickquiz_front_css', plugins_url( '/css/front.css', $mainPluginFile ) );

            return $content;
        }

        function load_quiz_script()
        {
            global $pageQuizzes;

            $out = '';

            if ( count( $pageQuizzes ) ) {

                foreach ( $pageQuizzes as $id => $quizStat ) {
                    $quiz   = $quizStat[0];
                    $status = $quizStat[1];

                    if ( $status && $status != self::NOT_PUBLISHED ) {
                        $out .='
                            <script type="text/javascript">
                                jQuery(document).ready(function($) {';

                        $out .= '
                                    $("#slickQuiz' . $quiz->id . '").slickQuiz({
                                        json:                        ' . $quiz->publishedJson . ',
                                        checkAnswerText:             "' . $this->get_admin_option( 'check_answer_text' ) . '",
                                        nextQuestionText:            "' . $this->get_admin_option( 'next_question_text' ) . '",
                                        backButtonText:              "' . $this->get_admin_option( 'back_button_text' ) . '",
                                        tryAgainText:                "' . $this->get_admin_option( 'try_again_text' ) . '",
                                        numberOfQuestions:           ' . ( $this->get_admin_option( 'number_of_questions' ) != '' ? $this->get_admin_option( 'number_of_questions' ) : 'null' ) . ',
                                        skipStartButton:             ' . ( $this->get_admin_option( 'skip_start_button' ) == '1' ? 'true' : 'false' ) . ',
                                        randomSortQuestions:         ' . ( $this->get_admin_option( 'random_sort_questions' ) == '1' ? 'true' : 'false' ) . ',
                                        randomSortAnswers:           ' . ( $this->get_admin_option( 'random_sort_answers' ) == '1' ? 'true' : 'false' ) . ',
                                        randomSort:                  ' . ( $this->get_admin_option( 'random_sort' ) == '1' ? 'true' : 'false' ) . ',
                                        preventUnanswered:           ' . ( $this->get_admin_option( 'disable_next' ) == '1' ? 'true' : 'false' ) . ',
                                        disableResponseMessaging:    ' . ( $this->get_admin_option( 'disable_responses' ) == '1' ? 'true' : 'false' ) . ',
                                        completionResponseMessaging: ' . ( $this->get_admin_option( 'completion_responses' ) == '1' ? 'true' : 'false' ) . '
                                    });';

                        if ( $this->get_admin_option( 'save_scores' ) == '1' ) {
                            $current_user = wp_get_current_user();
                            $name = '';

                            if ( ( $current_user instanceof WP_User ) ) {
                                $username = $current_user->user_login;
                                $fullname = trim($current_user->user_firstname . ' ' . $current_user->user_lastname);
                                $name = $fullname ? $fullname : $username;
                            }

                            $out .= '
                                    // get the start button
                                    var button' . $quiz->id . ' = $("#slickQuiz' . $quiz->id . ' .buttonWrapper a");';

                            // add username input if not logged in
                            if ( !$name ) {
                                $out .= '
                                        // disable the start button
                                        button' . $quiz->id . '.removeClass("startQuiz").addClass("disabled");

                                        // when name is entered, enable start button
                                        $("#slickQuiz' . $quiz->id . ' .nameLabel input").on("change keyup", function() {
                                            if ($(this).val() !== "") {
                                                button' . $quiz->id . '.addClass("startQuiz").removeClass("disabled");
                                            } else {
                                                button' . $quiz->id . '.removeClass("startQuiz").addClass("disabled");
                                            }
                                        });';
                            }

                            // hide the name field if a user is logged in
                            $display = $name ? 'style=\"display: none;\"' : '';

                            $out .= '
                                    // insert a name field before the button
                                    $("#slickQuiz' . $quiz->id . ' .buttonWrapper").before(
                                        "<div class=\"nameLabel\" ' . $display . '>"
                                        + "<label>' . $this->get_admin_option( 'name_label' ) . '</label>"
                                        + "<input type=\"text\" value=\"' . $name . '\" /></div>"
                                    );

                                    // when starting quiz, hide name field
                                    $("#slickQuiz' . $quiz->id . ' .button.startQuiz").live("click", function() {
                                        if ($(this).hasClass("disabled") === false) {
                                            $("#slickQuiz' . $quiz->id . ' .nameLabel").hide();
                                        }
                                    });

                                    // watch final check answer button and wait for result calculation before submitting
                                    $("#slickQuiz' . $quiz->id . ' .button.checkAnswer").last().on("click", function() {
                                        setTimeout(submitScore' . $quiz->id . ', 2000);
                                    });

                                    // submit scores to wordpress db
                                    function submitScore' . $quiz->id . '() {
                                        var json = {
                                            name: $("#slickQuiz' . $quiz->id . ' .nameLabel input").val(),
                                            score: $("#slickQuiz' . $quiz->id . ' .correctResponse").length + " / " + $("#slickQuiz' . $quiz->id . ' .question").length,
                                            quiz_id: ' . $quiz->id . '
                                        };

                                        $.ajax({
                                            type: "POST",
                                            url: "' . esc_url( wp_nonce_url( site_url( "wp-admin/admin-ajax.php" ), "wp-admin/admin-ajax.php" ) ) . '",
                                            data: {action: "save_quiz_score", json: JSON.stringify(json)}
                                        });
                                    }';
                        }

                        if ( $this->get_admin_option( 'share_links' ) == '1' ) {
                            $out .= '
                                    // watch final check answer button and wait for result calculation before submitting
                                    $("#slickQuiz' . $quiz->id . ' .button.checkAnswer").last().on("click", function() {
                                        setTimeout(addShareButtons' . $quiz->id . ', 2000);
                                    });

                                    // updates the share buttons with score, rank, and quiz name details
                                    function addShareButtons' . $quiz->id . '() {
                                        var shareDiv = $("#slickQuiz' . $quiz->id . ' .quizShare");

                                        if (shareDiv.length > 0) {
                                            shareDiv.empty(); // in case Try Again is enabled, reset buttons / messages

                                            var twitterButton = "<a href=\'https://twitter.com/share\'"
                                                + " class=\'twitter-share-button\'"
                                                + " data-url=\'' . $this->current_page_url() . '\'"
                                                + " data-text=\\"' . $this->get_admin_option( 'share_message' ) . '\\""
                                                + " data-via=\'' . $this->get_admin_option( 'twitter_account' ) . '\'>Tweet</a>";

                                            twitterButton = twitterButton
                                                .replace(/\[NAME\]/, $("#slickQuiz' . $quiz->id . ' .quizName").html())
                                                .replace(/\[SCORE\]/, $("#slickQuiz' . $quiz->id . ' .quizScore span").html())
                                                .replace(/\[RANK\]/, $("#slickQuiz' . $quiz->id . ' .quizLevel span").html());

                                            shareDiv.append($(twitterButton));

                                            var facebookButton = "<iframe"
                                                + " src=\'//www.facebook.com/plugins/like.php?href=' . urlencode( $this->current_page_url() ) . '&amp;send=false&amp;layout=button_count&amp;width=450&amp;show_faces=false&amp;font&amp;colorscheme=light&amp;action=like&amp;height=21&amp\'"
                                                + " scrolling=\'no\'"
                                                + " frameborder=\'0\'"
                                                + " style=\'border:none; overflow:hidden; width:450px; height:21px;\'"
                                                + " allowTransparency=\'true\'></iframe>";

                                            shareDiv.append($(facebookButton));

                                            twttr.widgets.load();
                                        }
                                    }
                            ';
                        }

                        $out .= '
                                });
                            </script>';
                    }
                }
            }

            echo $out;
        }

        function show_slickquiz_handler( $atts )
        {
            extract( shortcode_atts( array(
                'id' => 0,
            ), $atts ) );

            $out = $this->show_slickquiz( $id );

            return $out;
        }

        function show_slickquiz( $id )
        {
            global $quiz, $status, $pageQuizzes;

            $quizModel = new SlickQuizModel;
            $quiz = $quizModel->get_quiz_by_id( $id );
            $out  = '';

            if ( $quiz ) {
                $status = $quizModel->get_quiz_status( $quiz );

                $pageQuizzes[$id] = array( $quiz, $status );

                $out = '';

                if ( $status == self::NOT_PUBLISHED ) {
                    $out .= "<p class='quiz-$id notPublished'>" . $this->get_admin_option( 'disabled_quiz_message' ) . "</p>";
                } else {
                    $out .= '
                        <div class="slickQuizWrapper" id="slickQuiz' . $quiz->id . '">
                            <h2 class="quizName"></h2>

                            <div class="quizArea">
                                <div class="quizHeader">
                                    <div class="buttonWrapper"><a class="button startQuiz" href="#">' . $this->get_admin_option( 'start_button_text' ) . '</a></div>
                                </div>
                            </div>

                            <div class="quizResults">
                                <div class="quizResultsCopy">
                                    <h3 class="quizScore">' . $this->get_admin_option( 'your_score_text' ) . ' <span>&nbsp;</span></h3>
                                    <h3 class="quizLevel">' . $this->get_admin_option( 'your_ranking_text' ) . ' <span>&nbsp;</span></h3>';

                    if ( $this->get_admin_option( 'share_links' ) == '1' ) {
                        $out .= '
                                    <div class="quizShare"></div>';
                    }

                    $out .= '
                                </div>
                            </div>
                        </div>';
                }
            } else {
                $out .= "<p class='quiz-$id notFound'>" . $this->get_admin_option( 'missing_quiz_message' ) . "</p>";
            }

            return $out;
        }

        function current_page_url()
        {
            $pageURL = 'http';

            if( isset($_SERVER["HTTPS"]) ) {
                if ($_SERVER["HTTPS"] == "on") {$pageURL .= "s";}
            }

            $pageURL .= "://";

            if ($_SERVER["SERVER_PORT"] != "80") {
                $pageURL .= $_SERVER["SERVER_NAME"].":".$_SERVER["SERVER_PORT"].$_SERVER["REQUEST_URI"];
            } else {
                $pageURL .= $_SERVER["SERVER_NAME"].$_SERVER["REQUEST_URI"];
            }

            return $pageURL;
        }

    }
}

?>

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
        public function __construct()
        {
            global $pluginOptions;

            $this->get_admin_options();

            // Add Shortcodes
            add_shortcode( 'slickquiz', array( &$this, 'show_slickquiz_handler' ) );

            // We don't know where the quiz is going to end up, so just always load the resources :()
            add_filter( 'wp_footer', array( &$this, 'load_resources' ) );

            // Make sure dynamic quiz scripts gets loaded below jQuery
            add_filter( 'wp_footer', array( &$this, 'load_quiz_script' ), 5000 );
        }

        // WP action. Add Admin JS and styles
        public function load_resources()
        {
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
        }

        // WP action (wp_footer)
        public function load_quiz_script()
        {
            global $pageQuizzes;

            $out = '';

            if ( count( $pageQuizzes ) ) {

                foreach ( $pageQuizzes as $id => $quizStat ) {
                    $quiz   = $quizStat[0];
                    $status = $quizStat[1];

                    if ( $status && $status != self::NOT_PUBLISHED ) {
                        $out .= '
                            <script type="text/javascript">
                                jQuery(document).ready(function($) {';

                        $out .= '
                                    $("#slickQuiz' . $quiz->id . '").slickQuiz({
                                        json:                         ' . $this->filter_quiz( $quiz->publishedJson ) . ',
                                        questionCountText:            "' . $this->get_admin_option( 'question_count_text' ) . '",
                                        questionTemplateText:         "' . $this->get_admin_option( 'question_template_text' ) . '",
                                        scoreTemplateText:            "' . $this->get_admin_option( 'score_template_text' ) . '",
                                        checkAnswerText:              "' . $this->get_admin_option( 'check_answer_text' ) . '",
                                        nextQuestionText:             "' . $this->get_admin_option( 'next_question_text' ) . '",
                                        completeQuizText:             "' . $this->get_admin_option( 'complete_button_text' ) . '",
                                        backButtonText:               "' . $this->get_admin_option( 'back_button_text' ) . '",
                                        tryAgainText:                 "' . $this->get_admin_option( 'try_again_text' ) . '",
                                        numberOfQuestions:            ' . ( $this->get_admin_option( 'number_of_questions' ) != '' ? $this->get_admin_option( 'number_of_questions' ) : 'null' ) . ',
                                        skipStartButton:              ' . ( $this->get_admin_option( 'skip_start_button' ) == '1' ? 'true' : 'false' ) . ',
                                        randomSortQuestions:          ' . ( $this->get_admin_option( 'random_sort_questions' ) == '1' ? 'true' : 'false' ) . ',
                                        randomSortAnswers:            ' . ( $this->get_admin_option( 'random_sort_answers' ) == '1' ? 'true' : 'false' ) . ',
                                        preventUnanswered:            ' . ( $this->get_admin_option( 'disable_next' ) == '1' ? 'true' : 'false' ) . ',
                                        perQuestionResponseMessaging: ' . ( $this->get_admin_option( 'perquestion_responses' ) == '1' ? 'true' : 'false' ) . ',
                                        perQuestionResponseAnswers:   ' . ( $this->get_admin_option( 'perquestion_response_answers' ) == '1' ? 'true' : 'false' ) . ',
                                        completionResponseMessaging:  ' . ( $this->get_admin_option( 'completion_responses' ) == '1' ? 'true' : 'false' ) . ',
                                        displayQuestionCount:         ' . ( $this->get_admin_option( 'question_count' ) == '1' ? 'true' : 'false' ) . ',
                                        displayQuestionNumber:        ' . ( $this->get_admin_option( 'question_number' ) == '1' ? 'true' : 'false' ) . ',
                                        disableScore:                 ' . ( $this->get_admin_option( 'disable_score' ) == '1' ? 'true' : 'false' ) . ',
                                        disableRanking:               ' . ( $this->get_admin_option( 'disable_ranking' ) == '1' ? 'true' : 'false' ) . ',
                                        scoreAsPercentage:            ' . ( $this->get_admin_option( 'score_as_percentage' ) == '1' ? 'true' : 'false' ) . '
                                    });';

                        if ( $this->get_admin_option( 'save_scores' ) == '1' ) {
                            $current_user = wp_get_current_user();
                            $name = '';
                            $email = '';

                            if ( ( $current_user instanceof WP_User ) ) {
                                $username = $current_user->user_login;
                                $fullname = trim($current_user->user_firstname . ' ' . $current_user->user_lastname);
                                $name = $fullname ? $fullname : $username;
                                $email = $current_user->user_email;
                            }

                            $out .= '
                                    // get the start button
                                    var button' . $quiz->id . ' = $("#slickQuiz' . $quiz->id . ' .buttonWrapper a");';

                            // hide the user fields if a user is logged in
                            $display = $name ? 'style=\"display: none;\"' : '';

                            $out .= '
                                    // insert a name field before the button
                                    $("#slickQuiz' . $quiz->id . ' .buttonWrapper").before(
                                        "<div class=\"nameLabel\" ' . $display . '>"
                                        + "<label>' . $this->get_admin_option( 'name_label' ) . '</label>"
                                        + "<input type=\"text\" value=\"' . $name . '\" /></div>"
                                    );';

                            if ( $name || $this->get_admin_option( 'email_label' ) != '') {
                                $display = $name ? 'style=\"display: none;\"' : '';

                                $out .= '
                                    // insert a email field before the button
                                    $("#slickQuiz' . $quiz->id . ' .buttonWrapper").before(
                                        "<div class=\"emailLabel\" ' . $display . '>"
                                        + "<label>' . $this->get_admin_option( 'email_label' ) . '</label>"
                                        + "<input type=\"email\" value=\"' . $email . '\" /></div>"
                                    );';
                            }

                            $out .='
                                    var nameLabel = $("#slickQuiz' . $quiz->id . ' .nameLabel input");
                                    var emailLabel = $("#slickQuiz' . $quiz->id . ' .emailLabel input");

                                    // when starting quiz, hide name field
                                    $("#slickQuiz' . $quiz->id . ' .button.startQuiz").on("click", function(e) {
                                        var start = $(this);

                                        if (start.hasClass("disabled")) {
                                            e.stopPropagation();
                                        } else {
                                            nameLabel.parent().hide();
                                            emailLabel.parent().hide();
                                            start.hide();
                                        }
                                    });

                                    // watch final check answer button and wait for result calculation before submitting
                                    $("#slickQuiz' . $quiz->id . ' .button.checkAnswer").last().on("click", function() {
                                        setTimeout(submitScore' . $quiz->id . ', 2000);
                                    });

                                    // submit scores to wordpress db
                                    function submitScore' . $quiz->id . '() {
                                        var json = {
                                            name: nameLabel.val(),
                                            email: emailLabel ? emailLabel.val() : "",
                                            score: $("#slickQuiz' . $quiz->id . ' .correctResponse").length + " / " + $("#slickQuiz' . $quiz->id . ' .question").length,
                                            quiz_id: ' . $quiz->id . '
                                        };

                                        $.ajax({
                                            type: "POST",
                                            url: "' . esc_url( wp_nonce_url( site_url( "wp-admin/admin-ajax.php" ), "wp-admin/admin-ajax.php" ) ) . '",
                                            data: {action: "save_quiz_score", json: JSON.stringify(json)}
                                        });
                                    }';

                            // add username input if not logged in
                            if ( !$name ) {
                                $out .= '
                                        // disable the start button
                                        button' . $quiz->id . '.addClass("disabled");

                                        // when name is entered, enable start button
                                        $("#slickQuiz' . $quiz->id . '").on("change keyup", ".nameLabel input, .emailLabel input", function() {
                                            var namePass = nameLabel.val() !== "";
                                            var emailPass = true;

                                            if (emailLabel.length > 0) {
                                              emailPass = false;
                                              emailPass = emailLabel.val() !== "";
                                              emailPass = /\S+@\S+\.\S+/.test(emailLabel.val());
                                            }

                                            if (namePass && emailPass) {
                                                button' . $quiz->id . '.removeClass("disabled");
                                            } else {
                                                button' . $quiz->id . '.addClass("disabled");
                                            }
                                        });';
                            }
                        }

                        if ( $this->get_admin_option( 'share_links' ) == '1' ) {
                            $out .= '
                                    // watch final check answer button and wait for result calculation before submitting
                                    $("#slickQuiz' . $quiz->id . ' .button.nextQuestion").last().on("click", function() {
                                        setTimeout(addShareButtons' . $quiz->id . ', 2000);
                                    });

                                    var sTop = window.screen.height/2-(218);
                                    var sLeft = window.screen.width/2-(313);
                                    var sqShareOptions = "height=400,width=580,toolbar=0,status=0,location=0,menubar=0,directories=0,scrollbars=0,top=" + sTop + ",left=" + sLeft;
                                    var pageUrl = "' . $this->current_page_url() . '";
                                    var pageUrlEncoded = encodeURIComponent(pageUrl);

                                    // updates the share buttons with score, rank, and quiz name details
                                    function addShareButtons' . $quiz->id . '() {
                                        var shareDiv = $("#slickQuiz' . $quiz->id . ' .quizShare");

                                        if (shareDiv.length > 0) {
                                            shareDiv.empty(); // in case Try Again is enabled, reset buttons / messages

                                            var sharePrep = "<style type=\'text/css\'>"
                                                + ".slickQuizWrapper .quizShare .facebook { background: url(' . plugins_url( '/images/facebook.png' , dirname( __FILE__ ) ) . ') left top no-repeat; } "
                                                + ".slickQuizWrapper .quizShare .twitter { background: url(' . plugins_url( '/images/twitter.png' , dirname( __FILE__ ) ) . ') left top no-repeat; } "
                                                + ".slickQuizWrapper .quizShare .email { background: url(' . plugins_url( '/images/email.png' , dirname( __FILE__ ) ) . ') left top no-repeat; } "
                                                + "</style>"
                                            shareDiv.append($(sharePrep));

                                            var name  = $("#slickQuiz' . $quiz->id . ' .quizName").text();
                                            var desc  = $("#slickQuiz' . $quiz->id . ' .quizDescription").text();
                                            var score = $("#slickQuiz' . $quiz->id . ' .quizScore span").text();
                                            var rank  = $("#slickQuiz' . $quiz->id . ' .quizLevel span").text();

                                            var copy = encodeURIComponent("' . $this->get_admin_option( 'share_message' ) . '"
                                                            .replace(/\[NAME\]/, name)
                                                            .replace(/\[SCORE\]/, score)
                                                            .replace(/\[RANK\]/, rank)
                                                        );

                                            var twitterButton = "<a class=\'twitterButton\' href=\'#\'><i class=\'twitter\'></i><span>Tweet</span></a>";
                                            shareDiv.append($(twitterButton).css("opacity",0).animate({opacity:1}));
                                            $("#slickQuiz' . $quiz->id . ' .twitterButton").on("click", function(e) {
                                                e.preventDefault();
                                                slickQuizTwitter(copy);
                                            });

                                            var facebookButton = "<a class=\'facebookButton\' href=\'#\'><i class=\'facebook\'></i><span>Facebook</span></a>";
                                            shareDiv.append($(facebookButton).css("opacity",0).animate({opacity:1}));
                                            $("#slickQuiz' . $quiz->id . ' .facebookButton").on("click", function(e) {
                                                e.preventDefault();
                                                slickQuizFacebook(copy, encodeURIComponent(desc));
                                            });

                                            var emailCopy = copy + encodeURIComponent("\n\n" + pageUrl);
                                            var emailUrl  = "mailto:?subject=" + encodeURIComponent(name) + "&amp;body=" + emailCopy;
                                            var emailButton = "<a class=\"emailButton\" href=\"" + emailUrl + "\"><i class=\"email\"></i><span>Email</span></a>";
                                            shareDiv.append($(emailButton).css("opacity",0).animate({opacity:1}));
                                        }
                                    }

                                    function slickQuizTwitter(copy) {
                                        var name = "' . $this->get_admin_option( 'twitter_account' ) . '".replace(/@/, \'\');

                                        var twitterUrl = "https://twitter.com/intent/tweet?"
                                            + "url=" + pageUrlEncoded + "&"
                                            + "text=" + copy + "&"
                                            + "via=" + name;

                                        window.open(twitterUrl, "twitterShare", sqShareOptions);
                                        return false;
                                    }

                                    function slickQuizFacebook(copy, desc) {
                                        var facebookUrl = "https://www.facebook.com/dialog/feed?"
                                            + "display=popup&"
                                            + "app_id=225454720972262&"
                                            + "link=" + pageUrlEncoded + "&"
                                            + "name=" + copy + "&"
                                            + "description=" + desc + "&"
                                            + "redirect_uri=http://slickquiz.com/close.html";

                                        window.open(facebookUrl, "facebookShare", sqShareOptions);
                                        return false;
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

        // WP shortcode [slickquiz]
        public function show_slickquiz_handler( $atts )
        {
            extract( shortcode_atts( array(
                'id' => 0,
            ), $atts ) );

            // Optionally, extract quiz ID from the URL [Ticket #2180]
            if ( $id == 'url' || $id == 'uri' || $id == -1 ) {
                $regex = '@'. basename( get_permalink() ) .'\/(\d+)\/?@';

                if ( preg_match($regex, $_SERVER['REQUEST_URI'], $matches) ) {
                    $id = $matches[1];
                }
            }
            // Guard against mis-spellings (plus security).
            $id = intval( $id );

            $out = $this->show_slickquiz( $id );

            return $out;
        }

        protected function show_slickquiz( $id )
        {
            global $quiz, $status, $pageQuizzes;

            $quizModel = new SlickQuizModel;
            $quiz = $quizModel->get_quiz_by_id( $id );
            $out  = '';

            if ( $quiz ) {
                $status = $quizModel->get_quiz_status( $quiz );

                $pageQuizzes[$id] = array( $quiz, $status );

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
                                </div>';

                    if ( has_action( 'slickquiz_after_result' ) ) {
                        $out .= apply_filters( 'slickquiz_after_result', $this );
                    }

                    $out .= '
                            </div>
                        </div>';
                }
            } else {
                $out .= "<p class='quiz-$id notFound'>" . $this->get_admin_option( 'missing_quiz_message' ) . "</p>";
            }

            return $out;
        }

        protected function current_page_url()
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

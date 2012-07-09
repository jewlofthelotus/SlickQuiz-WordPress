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

            // Make sure dynamic quiz script gets loaded below jQuery
            add_filter( 'wp_footer', array( &$this, 'load_quiz_script' ), 5000 );
        }

        // Add Admin JS and styles
        function load_resources( $content )
        {
            // Only load resources when a shortcode is on the page
            preg_match( '/\[slickquiz[^\]]*\]/is', $content, $matches );
            if ( count( $matches) == 0 ) return $content;

            $mainPluginFile = dirname(dirname(__FILE__)) . '/slickquiz.php';

            // Scripts
            wp_enqueue_script( 'jquery' );
            wp_enqueue_script( 'slickquiz_js', plugins_url( '/slickquiz/js/slickQuiz.js', $mainPluginFile ) );

            // Styles
            wp_enqueue_style( 'slickquiz_css', plugins_url( '/slickquiz/css/slickQuiz.css', $mainPluginFile ) );
            wp_enqueue_style( 'slickquiz_front_css', plugins_url( '/css/front.css', $mainPluginFile ) );

            return $content;
        }

        function load_quiz_script()
        {
            global $quiz, $status;

            $out = '';

            if ( $quiz ) {
                if ( $status && $status != self::NOT_PUBLISHED ) {
                    $out = '
                        <script type="text/javascript">
                            jQuery(document).ready(function($) {
                                $("#slickQuiz' . $quiz->id . '").slickQuiz({
                                    json:             ' . $quiz->publishedJson . ',
                                    checkAnswerText:  "' . $this->get_admin_option( 'check_answer_text' ) . '",
                                    nextQuestionText: "' . $this->get_admin_option( 'next_question_text' ) . '",
                                    backButtonText:   "' . $this->get_admin_option( 'back_button_text' ) . '",
                                    randomSort:       ' . ( $this->get_admin_option( 'random_sort' ) == '1' ? 'true' : 'false' ) . '
                                });
                            });
                        </script>';
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
            global $quiz, $status;

            $quiz = $this->get_quiz_by_id( $id );

            if ( $quiz ) {
                $status = $this->get_quiz_status( $quiz );

                if ( $status == self::NOT_PUBLISHED ) {
                    $out = "<p class='quiz-$id notPublished'>" . $this->get_admin_option( 'disabled_quiz_message' ) . "</p>";
                } else {
                    $out = '
                        <div class="slickQuizWrapper" id="slickQuiz' . $quiz->id . '">
                            <h2 class="quizName"></h2>

                            <div class="quizArea">
                                <div class="quizHeader">
                                    <div class="buttonWrapper"><a class="button startQuiz">' . $this->get_admin_option( 'start_button_text' ) . '</a></div>
                                </div>
                            </div>

                            <div class="quizResults">
                                <div class="quizResultsCopy">
                                    <h3 class="quizScore">' . $this->get_admin_option( 'your_score_text' ) . ' <span>&nbsp;</span></h3>
                                    <h3 class="quizLevel">' . $this->get_admin_option( 'your_ranking_text' ) . ' <span>&nbsp;</span></h3>
                                </div>
                            </div>
                        </div>';
                }
            } else {
                $out = "<p class='quiz-$id notFound'>" . $this->get_admin_option( 'missing_quiz_message' ) . "</p>";
            }

            return $out;
        }

    }
}

?>

<?php

// Stop direct call
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
    die( 'You are not allowed to call this page directly.' );
}

if ( !class_exists( 'SlickQuizScoreWidget' ) ) {
    class SlickQuizScoreWidget extends WP_Widget
    {

        private $model;


        // Constructor
        function __construct()
        {
            parent::__construct(
                'slickquiz_topscores', // Base ID
                'SlickQuiz Top Scores', // Name
                array( 'description' => __( 'Lists top scores for a designated SlickQuiz.', 'text_domain' ) ) // Args
            );
        }

        // Outputs widget content
        public function widget( $args, $instance )
        {
            $title = apply_filters( 'widget_title', $instance['title'] );

            // Before Widget Content (wrapper / title)
            echo $args['before_widget'];
            if ( ! empty( $title ) ) {
                echo $args['before_title'] . $title . $args['after_title'];
            }

            // Main Widget Content
            if ( $instance['quiz_id'] ) {
                $quizModel = new SlickQuizModel;
                $scores    = $quizModel->get_all_scores( $instance['quiz_id'], 'convert(score, decimal) DESC, createdDate ASC', "LIMIT " . $instance['score_count'] );

                if (count($scores) > 0) {
                    ?>
                    <ol>
                    <?php
                    foreach ( $scores as $score ) {
                        ?>
                        <li><?php echo $score->score; ?> - <?php echo $score->name; ?></li>
                        <?php
                    }
                    ?>
                    </ol>
                    <?php
                } else {
                    ?>
                    <p>No scores yet.</p>
                    <?php
                }
            } else {
                ?><p>Quiz not found.</p><?php
            }

            // After Widget Content (closing tags)
            echo $args['after_widget'];
        }

        // Outputs admin widget form
        public function form( $instance )
        {
            $title = isset( $instance[ 'title' ] ) ? $instance[ 'title' ] : 'Top Quiz Scores';
            ?>
            <p>
            <label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
            </p>
            <?php

            $quiz_id = isset( $instance[ 'quiz_id' ] ) ? $instance[ 'quiz_id' ] : '';

            $quizModel = new SlickQuizModel;
            $quizzes = $quizModel->get_all_quizzes('id, name');

            if (count($quizzes) > 0) {
                ?>
                <p>
                <label for="<?php echo $this->get_field_name( 'quiz_id' ); ?>"><?php _e( 'Quiz:' ); ?></label>
                <select class="widefat" id="<?php echo $this->get_field_id( 'quiz_id' ); ?>" name="<?php echo $this->get_field_name( 'quiz_id' ); ?>">
                <option value=""></option>
                <?php
                foreach ($quizzes as $quiz) {
                    ?>
                    <option value="<?php echo $quiz->id; ?>" <?php if ( $quiz_id == $quiz->id ) { echo 'selected'; } ?>><?php echo $quiz->name; ?></option>
                    <?php
                }
                ?>
                </select>
                </p>
                <?php
            } else {
                ?>
                <p>You must create a quiz before you can select one to show scores for.</p>
                <input type="hidden" id="<?php echo $this->get_field_id( 'quiz_id' ); ?>" name="<?php echo $this->get_field_name( 'quiz_id' ); ?>" value="<?php echo $quiz_id; ?>" />
                <?php
            }

            $score_count = isset( $instance[ 'score_count' ] ) ? $instance[ 'score_count' ] : '10';
            ?>
            <p>
            <label for="<?php echo $this->get_field_name( 'score_count' ); ?>"><?php _e( 'Number of Scores to Display:' ); ?></label>
            <input class="widefat" id="<?php echo $this->get_field_id( 'score_count' ); ?>" name="<?php echo $this->get_field_name( 'score_count' ); ?>" type="num" value="<?php echo esc_attr( $score_count ); ?>" />
            </p>
            <?php
        }

        // Processes widget options for saving
        public function update( $new_instance, $old_instance )
        {
            $instance = array();
            $instance['title'] = ( ! empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
            $instance['quiz_id'] = ( ! empty( $new_instance['quiz_id'] ) ) ? strip_tags( $new_instance['quiz_id'] ) : '';
            $instance['score_count'] = ( ! empty( $new_instance['score_count'] ) ) ? strip_tags( $new_instance['score_count'] ) : '';

            return $instance;
        }
    }

    function register_slickquiz_score_widget() {
        register_widget( 'SlickQuizScoreWidget' );
    }
    add_action( 'widgets_init',  'register_slickquiz_score_widget' );
}


// Add / register additional widgets here

?>

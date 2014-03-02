<?php

// Stop direct call
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
    die( 'You are not allowed to call this page directly.' );
}

if ( !class_exists( 'SlickQuizScores' ) ) {
    class SlickQuizScores extends SlickQuizModel
    {

        var $scores = array();
        var $quiz = '';


        // Constructor
        function __construct()
        {
            global $quiz;
            $quiz = $this->get_quiz_by_id( $_GET['id'] );
        }

        function list_all_scores()
        {
            global $quiz, $scores;

            $scores = $this->get_all_scores( $quiz->id );

            if (count($scores) > 0) {
                foreach ($scores as $score) {
                    echo  $this->generate_score_row($score);
                }
            } else {
                echo "<tr><td colspan=7>No scores have been saved yet.</td></tr>";
            }
        }

        function generate_score_row( $score )
        {
            $scoreRow = '';

            $scoreRow .= '<tr>';
            $scoreRow .= '<td class="table_id">' . $score->id . '</td>';
            $scoreRow .= '<td class="table_name">' . $score->name . '</td>';
            $scoreRow .= '<td class="table_email">' . $score->email . '</td>';
            $scoreRow .= '<td class="table_score">' . $score->score . '</td>';
            $scoreRow .= '<td class="table_created">' . $score->createdDate . '</td>';
            $scoreRow .= '<td class="table_actions">' . $this->get_score_actions( $score->id ) . '</td>';
            $scoreRow .= '</tr>';

            return $scoreRow;
        }

        function get_score_actions( $id )
        {
            $actions = '';

            // Editor or Admin Options
            if ( current_user_can( 'publish_pages' ) ) {
                $actions .= '<a class="delete_score delete" title="Delete Score" '
                         . 'href="' . admin_url( 'admin-ajax.php?id=' ) . $id . '">'
                         . '<img id="deletescore-' . $id . '" '
                         . 'src="' . plugins_url( '/images/bin_closed.png' , dirname( __FILE__ ) ) . '"'
                         . ' width="16" height="16" alt="Delete Score" /></a> ';
            }

            return $actions;
        }

        function quiz_name()
        {
            global $quiz;
            echo $quiz->name;
        }

        function get_score_count()
        {
            global $scores;
            echo count( $scores );
        }

        function show_alert_messages()
        {
            if ( isset( $_GET['success'] ) )
                echo '<div id="message" class="updated"><p>The score has been deleted.</p></div>';
        }

    }
}

if ( class_exists( 'SlickQuizScores' ) ) {
    global $slickQuizScores;
    $slickQuizScores = new SlickQuizScores();
}

?>

<div class="wrap slickQuiz scoreList">
    <h2>SlickQuiz Scores for "<?php $slickQuizScores->quiz_name(); ?>"</h2>

    <?php $slickQuizScores->show_alert_messages(); ?>

    <table id="record_view" class="wp-list-table widefat quiz_scores">
        <thead>
            <tr>
                <th scope="col" class="table_id">ID</th>
                <th scope="col" class="table_name">Name</th>
                <th scope="col" class="table_email">Email</th>
                <th scope="col" class="table_score">Score</th>
                <th scope="col" class="table_createdDate">Created On</th>
                <th scope="col" class="table_actions"></th>
            </tr>
        </thead>
         <tbody>
            <?php $slickQuizScores->list_all_scores(); ?>
        </tbody>
    </table>
    <div class="tablenav bottom">
        <div class="tablenav-pages one-page"><span class="displaying-num"><?php $slickQuizScores->get_score_count() ?> item</span>
    </div>
</div>

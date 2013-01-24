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
            $scoreRow .= '<td class="table_score">' . $score->score . '</td>';
            $scoreRow .= '<td class="table_created">' . $score->createdDate . '</td>';
            $scoreRow .= '</tr>';

            return $scoreRow;
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

    }
}

if ( class_exists( 'SlickQuizScores' ) ) {
    global $slickQuizScores;
    $slickQuizScores = new SlickQuizScores();
}

?>

<div class="wrap scoreList">
    <h2>SlickQuiz Scores for "<?php $slickQuizScores->quiz_name(); ?>"</h2>

    <table id="record_view" class="wp-list-table widefat quiz_scores">
        <thead>
            <tr>
                <th scope="col" class="table_id">ID</th>
                <th scope="col" class="table_name">Name</th>
                <th scope="col" class="table_score">Score</th>
                <th scope="col" class="table_createdDate">Created On</th>
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

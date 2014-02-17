<?php

// Stop direct call
if ( preg_match( '#' . basename( __FILE__ ) . '#', $_SERVER['PHP_SELF'] ) ) {
    die( 'You are not allowed to call this page directly.' );
}

if ( !class_exists( 'SlickQuizAdmin' ) ) {
    class SlickQuizAdmin extends SlickQuizModel
    {

        var $quizzes = array();


        function list_all_quizzes()
        {
            global $quizzes;

            $quizzes = $this->get_all_quizzes();

            if (count($quizzes) > 0) {
                foreach ($quizzes as $quiz) {
                    echo  $this->generate_quiz_row($quiz);
                }
            } else {
                echo "<tr><td colspan=7>You have not created any quizzes yet.</td></tr>";
            }
        }

        function generate_quiz_row( $quiz )
        {
            $id           = $quiz->id;
            $status       = $this->get_quiz_status( $quiz );
            $statusButton = $this->get_quiz_status_button( $quiz );
            $qCount       = $status == self::PUBLISHED ? $quiz->publishedQCount : $quiz->workingQCount;
            $actions      = '';
            $quizRow      = '';

            // Editor or Admin Options
            if ( current_user_can( 'publish_pages' ) ) {
                if ( $status == self::PUBLISHED ) {
                    $actions .= '<a class="unpublish_quiz unpublish" title="Unpublish Quiz" '
                             . 'href="' . admin_url( 'admin-ajax.php?id=' ) . $id . '">'
                             . '<img id="unpublishquiz-' . $id . '" '
                             . 'src="' . plugins_url( '/images/remove.png' , dirname( __FILE__ ) ) . '"'
                             . ' width="16" height="16" alt="Unpublish Quiz" /></a> ';
                }
                $actions .= '<a class="delete_quiz delete" title="Delete Quiz" '
                         . 'href="' . admin_url( 'admin-ajax.php?id=' ) . $id . '">'
                         . '<img id="deletequiz-' . $id . '" '
                         . 'src="' . plugins_url( '/images/bin_closed.png' , dirname( __FILE__ ) ) . '"'
                         . ' width="16" height="16" alt="Delete Quiz" /></a> ';
                $actions .= '<a class="edit_quiz" title="Edit Quiz" '
                         . 'href="' . admin_url( 'admin.php?page=slickquiz-edit&id=' ) . $id . '">'
                         . '<img id="editquiz-' . $id . '" '
                         . 'src="' . plugins_url( '/images/edit.png' , dirname( __FILE__ ) ) . '"'
                         . ' width="16" height="16" alt="Edit Quiz" /></a> ';
            }

            $actions .= '<a class="preview_quiz preview" title="Preview Quiz" '
                     . 'href="' . admin_url( 'admin.php?page=slickquiz-preview&id=' ) . $id . '&readOnly">'
                     . '<img id="previewquiz-' . $id . '" '
                     . 'src="' . plugins_url( '/images/view.png' , dirname( __FILE__ ) ) . '"'
                     . ' width="16" height="16" alt="Preview Quiz" /></a> &nbsp; ';

            $scoreLink = '<a href="' . admin_url( 'admin.php?page=slickquiz-scores&id=' . $quiz->id ) . '">'
                       . '<img src="' . plugins_url( '/images/user_comment.png' , dirname( __FILE__ ) ) . '"'
                       . ' alt="Quiz Scores" /></a>';

            $quizRow .= '<tr>';
            $quizRow .= '<td class="table_id">' . $quiz->id . '</td>';
            $quizRow .= '<td class="table_name">' . $quiz->name . '</td>';
            $quizRow .= '<td class="table_count">' . $qCount . '</td>';
            $quizRow .= '<td class="table_updated">' . $quiz->lastUpdatedDate . '</td>';
            $quizRow .= '<td class="table_pubDate">' . ($status == self::PUBLISHED ? $quiz->publishedDate : '') . '</td>';
            $quizRow .= '<td class="table_status">' . $statusButton . '</td>';
            $quizRow .= '<td class="table_scores">' . $scoreLink . '</td>';
            $quizRow .= '<td class="table_actions">' . $actions . '</td>';
            $quizRow .= '</tr>';

            return $quizRow;
        }

        function get_quiz_count( $ret = false )
        {
            global $quizzes;
            if ( $ret )
                return count( $quizzes );
            else
                echo count( $quizzes );
        }

        function show_alert_messages()
        {
            if ( isset( $_GET['success'] ) )
                echo '<div id="message" class="updated"><p>Your quiz has been published.</p></div>';

            if ( isset( $_GET['unpublish'] ) )
                echo '<div id="message" class="updated"><p>Your quiz has been unpublished.</p></div>';
        }

    }
}

if ( class_exists( 'SlickQuizAdmin' ) ) {
    global $slickQuizAdmin;
    $slickQuizAdmin = new SlickQuizAdmin();
}

?>

<div class="wrap slickQuiz quizList">
    <h2>SlickQuiz Management <?php if ( current_user_can( 'publish_pages' ) ) { // Editor or Admin Only  ?><a href="<?php echo admin_url( 'admin.php?page=slickquiz-new' ); ?>" class="add-new-h2" title="Create a new Quiz">Add New Quiz</a><?php } ?></h2>

    <?php $slickQuizAdmin->show_alert_messages(); ?>

    <p>To place a quiz on a post, page, or in the sidebar text widget - insert the following into the content, where "X" is the ID of the quiz you want to display.</p>

    <code>[slickquiz id=X]</code>

    <p class="statusLegend">
        <strong>Statuses:</strong> &nbsp;&nbsp;&nbsp;
        <img title="Published" src="<?php echo plugins_url( '/images/activate.png' , dirname( __FILE__ ) ); ?>"> Published &nbsp;&nbsp;&nbsp;
        <img title="Unpublished Changes" src="<?php echo plugins_url( '/images/alert.png' , dirname( __FILE__ ) ); ?>"> Unpublished Changes &nbsp;&nbsp;&nbsp;
        <img title="Not Published" src="<?php echo plugins_url( '/images/suspend.png' , dirname( __FILE__ ) ); ?>"> Not Published &nbsp;&nbsp;&nbsp;
    </p>

    <table id="record_view" class="wp-list-table widefat quizzes">
        <thead>
            <tr>
                <th scope="col" class="table_id">ID</th>
                <th scope="col" class="table_name">Name</th>
                <th scope="col" class="table_count">Question Count</th>
                <th scope="col" class="table_updated">Last Updated On</th>
                <th scope="col" class="table_pubDate">Published On</th>
                <th scope="col" class="table_status">Status</th>
                <th scope="col" class="table_scores">Scores</th>
                <th scope="col" class="table_actions"></th>
            </tr>
        </thead>
         <tbody>
            <?php $slickQuizAdmin->list_all_quizzes(); ?>
        </tbody>
    </table>
    <div class="tablenav bottom">
        <div class="tablenav-pages one-page"><span class="displaying-num"><?php $slickQuizAdmin->get_quiz_count() ?> <?php if ( $slickQuizAdmin->get_quiz_count(true) == 1 ) { ?>quiz<?php } else { ?>quizzes<?php } ?></span>
    </div>
</div>

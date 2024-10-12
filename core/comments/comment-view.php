<?php
require_once '../../config.php';

$mysqli = $_DB; 
if ($_SERVER['REQUEST_METHOD'] === "GET") {
    $sql = sprintf("SELECT c.*, u.username 
    FROM comments c
    JOIN users u ON c.commenter_id = u.id 
    WHERE c.post_id = '%s'", htmlspecialchars($post['id']) );
    
    $result = $mysqli->query($sql);

    $comments = [];
    while ($comment = $result->fetch_assoc()) {
        $comments[] = $comment; 
    }


}

    echo '<h3>Comments: ('. $post['comment_count'] . ')</h3>';

    if ($result) {

        foreach ($comments as $comment) {
            echo '<div class="post"> <p>';
            echo '<span><strong><a href="../users/user.php?user_id=' . $comment['commenter_id'] . '">' . get_user_name($comment['commenter_id']) . ': </a></strong></span>';
            echo '<span>' . htmlspecialchars($comment['comment']) . ' - </span>';
            echo '<span>' . date("d/m/y h:i:s a", $comment['created_at']) . '</span>';
            if (isset($_SESSION['user_id'])) {
                if ($_SESSION['user_id'] === $comment['commenter_id'] || is_admin($_SESSION['user_id'])) {
                echo '
                        <form action="../comments/delete-comment.php" method="post" onsubmit="return confirm(\'Delete comment?\');"">
                            <input type="hidden" name="user_id" value="' . htmlspecialchars($comment['commenter_id']) . '">
                            <input type="hidden" name="post_id" value="' . htmlspecialchars($comment['post_id']) . '">
                            <input type="hidden" name="comment_id" value="' . htmlspecialchars($comment['id']) . '">
                            <button>Delete</button>
                        </form>';
                }
            }
    
            echo '</p></div>';
        }

        if ((count($comments)) == 0) {
            echo '<p>No comments to display!</p>';
        }
    } else {
        echo "<p>Error: " . htmlspecialchars($mysqli->error) . "</p>";
    }

?>

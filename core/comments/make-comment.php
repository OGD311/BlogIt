<?php
require_once '../../config.php';

$mysqli = $_DB; 
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    
    $sql = "INSERT INTO comments (post_id, commenter_id, comment, created_at, reply_id) VALUES (?, ?, ?, ?, ?)";


    $stmt = $mysqli->stmt_init();

    if (!$stmt->prepare($sql)) {
        die("SQL Error: " . $mysqli->error);
    }

    $post_id = (int)$_POST['post_id'];
    $user_id = (int)$_POST['user_id'];
    $comment = $mysqli->real_escape_string($_POST['comment']);
    $created_at = time();

    if (isset($_POST['reply_id'])) {
        $reply_id = (int)$_POST['reply_id'];
    } else {
        $reply_id = null;
    }

    $stmt->bind_param("iisii", $post_id, $user_id, $comment, $created_at, $reply_id);


    if ($stmt->execute()) {

        header("Location: /core/posts/view.php?post_id=$post_id");
        exit(); 
    } else {
        die("Error updating post: " . $stmt->error);
    }
} else {
    header('Location: /core/main.php');
    exit();
}
?>

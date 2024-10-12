<?php
require_once '../../config.php';

$mysqli = $_DB; 
if ($_SERVER['REQUEST_METHOD'] === "POST") {
    
    $sql = "DELETE FROM comments WHERE id = ? AND commenter_id = ? AND post_id = ?";

    if ($stmt = $mysqli->prepare($sql)) {
        
        $post_id = (int)$_POST['post_id'];
        $user_id = (int)$_POST['user_id'];
        $comment_id = (int)$_POST['comment_id']; 

        $stmt->bind_param("iii", $comment_id, $user_id, $post_id);

        if ($stmt->execute()) {
            header("Location: /core/posts/view.php?post_id=$post_id");
            exit(); 

        } else {
            die("Error deleting comment: " . htmlspecialchars($stmt->error));
        }

        $stmt->close();
    } else {
        die("SQL Error: " . htmlspecialchars($mysqli->error));
    }
} else {
    header('Location: /core/main.php');
    exit();
}

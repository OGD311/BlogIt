<?php
require_once '../../config.php';

$mysqli = $_DB; 

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("POST request method required");
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $sql = "UPDATE posts SET title = ?, body = ?, updated_at = ? WHERE id = ? AND poster = ?";
    
    $stmt = $mysqli->stmt_init();

    if (!$stmt->prepare($sql)) {
        die("SQL Error: " . $mysqli->error);
    }


    $title = $mysqli->real_escape_string($_POST['title']);
    $body = $mysqli->real_escape_string($_POST['body']);
    $updated_at = time();
    

    $post_id = (int)$_POST['post_id'];
    $user_id = (int)$_POST['user_id'];

    $stmt->bind_param("ssiii", $title, $body, $updated_at, $post_id, $user_id);


    if ($stmt->execute()) {
        header('Location: /core/posts/view.php?post_id=' . $post_id);
        exit(); 

    } else {
        die("Error updating post: " . $stmt->error);
    }
} else {
    header('Location: /core/main.php');
    exit();
}
?>

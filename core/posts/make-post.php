<?php
require_once '../../config.php';

$mysqli = $_DB; 

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("POST request method required");
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    $sql = "INSERT INTO posts (title, body, poster, created_at)  VALUES (?, ?, ?, ?)";

    $stmt = $mysqli->stmt_init();

    if (!$stmt->prepare($sql)) {
        die('SQL Error: ' . $mysqli->error);
    }


    
    $title = htmlspecialchars($_POST['title']); 
    $body = $mysqli->real_escape_string($_POST['body']); 
    $poster = $_POST['user_id'];
    $created_at = time();

    $stmt->bind_param('ssii', $title, $body, $poster, $created_at);

    if ($stmt->execute()) {

        $sql = "SELECT id FROM posts WHERE title = ? AND poster = ? AND created_at = ?";
        $stmt = $mysqli->prepare($sql);
        $stmt->bind_param('sii', $title, $poster, $created_at); 
        $stmt->execute();
        
        $result = $stmt->get_result();
        $post_id = $result->fetch_assoc()['id'] ?? null;
        
        $stmt->close();
        
        
        header('Location: /core/posts/view.php?post_id=' . $post_id);
        exit;

    } else {
        if ($mysqli->errno === 1062) {
            die('Username already taken');
        }
        die('Error: ' . $mysqli->error . ' (' . $mysqli->errno . ')');
    }
    

}
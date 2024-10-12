<?php
require_once '../../config.php';

$mysqli = $_DB; 

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("POST request method required");
}

$title = trim($_POST['title']);
$body = trim($_POST['body']);
$post_id = (int)$_POST['post_id'];
$user_id = (int)$_POST['user_id'];

// Validate input data
if (empty($title) || empty($body) || $post_id <= 0 || $user_id <= 0) {
    exit("Invalid input data.");
}

$sql = "UPDATE posts SET title = ?, body = ?, updated_at = ? WHERE id = ? AND poster = ?";
$stmt = $mysqli->stmt_init();

if (!$stmt->prepare($sql)) {
    die("SQL Error: " . $mysqli->error);
}

$updated_at = date('Y-m-d H:i:s'); // Adjust the format as necessary
$stmt->bind_param("ssiii", $title, $body, $updated_at, $post_id, $user_id);

if ($stmt->execute()) {
    header('Location: /core/posts/view.php?post_id=' . $post_id);
    exit(); 
} else {
    die("Error updating post: " . $stmt->error);
}

$stmt->close(); // Close the statement
$mysqli->close(); // Close the database connection
?>

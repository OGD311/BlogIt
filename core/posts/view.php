<?php

require_once '../../config.php';

use Michelf\Markdown;


session_start();

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $mysqli = $_DB;

    if (isset($_GET['post_id'])) {
        $post_id = (int)$_GET['post_id']; // Casting to integer for safety
    
        // Combined SQL query using LEFT JOIN
        $sql = sprintf("
            SELECT p.*, u.id AS uploader_id, u.username, u.is_admin 
            FROM posts p 
            LEFT JOIN users u ON p.poster = u.id 
            WHERE p.id = '%s'", 
            $mysqli->real_escape_string($post_id)
        );
    
        $result = $mysqli->query($sql);
    
        $post = $result->fetch_assoc();

        $post_html = Markdown::defaultTransform($post['body']);
    
        if (!$post) {
            header("Location: ../errors/post-view.php");
            exit();
        }

    }

    
    
} else {
    header('Location: /core/main.php');
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Post <?= $post['id'] ?></title>
    <?php include '../html-elements/header.php' ?>

</head>
<body>
    <?php include '../html-elements/navbar.php'; ?>


    <div>

        <h1><?= $post['title'] ?></h1>

        <p><?= $post_html ?></p>
  
        <h5>Posted by: <a href="/core/users/user.php?user_id=<?= $post['poster'] ?>"><?= get_user_name($post['poster']) ?></h5>

        <?php if (!empty($_SESSION['user_id']) && ($post['poster'] == $_SESSION['user_id'] || is_admin($_SESSION['user_id']))) : ?>
            <a href="edit.php?post_id=<?= $post['id'] ?>">Edit Post</a>
        <?php endif ?>


        
    </div>


    <?php include '../comments/comment-view.php'; ?>
    
    <?php include '../comments/comment-form.php'; ?>


    <?php include '../html-elements/footer.php'; ?>




</body>

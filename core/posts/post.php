<?php
require_once '../../config.php';

session_start();

if (empty($_SESSION['user_id'])) {
    
    header('Location: /core/users/login.php');
}
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Post</title>
        <meta charset="UTF-8">
        <?php include '../html-elements/header.php' ?> 
        
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/simplemde/latest/simplemde.min.css">

    </head>

    <body>
        <?php include '../html-elements/navbar.php'; ?>


        <h1>Post</h1>

        <form action="make-post.php" method="post" id="signup">
            <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>'">

            <div>
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required>
            </div>

            <div>
                <label for="body">Body</label>
                <textarea type="textarea" id="body" name="body" required></textarea>
            </div>


            <button class="btn primary">Post as <?= get_user_name($_SESSION['user_id'])?></button>
        </form>


        <?php include '../html-elements/footer.php'; ?>

        
    </body>

</html>
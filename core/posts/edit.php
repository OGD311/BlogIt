<?php
require_once '../../config.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $mysqli = $_DB;

    if (isset($_GET['post_id'])) {
        $post_id = (int)$_GET['post_id']; 
        
        $userQuery = sprintf("
        SELECT
            u.id,
            p.title,
            p.body,
            p.poster
        FROM users u 
        JOIN posts p ON u.id = p.poster
        WHERE p.id = '%s'",
        $mysqli->real_escape_string($post_id));

    
    
        $userResult = $mysqli->query($userQuery);
        $postData = $userResult->fetch_assoc();

        
    
    } else {
        header('Location: /core/main.php');
        exit();
    }

    if ( ! isset($_SESSION['user_id'])) {
        header('Location: ../errors/user-edit.php');
        exit();
    }

    
    if (($_SESSION['user_id'] !== $postData['poster']) &&  (! is_admin($_SESSION['user_id'])) ) {
        header('Location: ../errors/user-edit.php');
        exit();
    }

}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editing <?= $postData['title'] ?>'s Profile</title>
    <?php include '../html-elements/header.php' ?>
    <link rel="stylesheet" href="/static/css/edit.css">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.css"/>
    <script src="https://cdn.jsdelivr.net/npm/easymde/dist/easymde.min.js"></script>


 
</head>
<body>
    <?php include '../html-elements/navbar.php'; ?>

    <h1>Editing: '<?= $postData['title'] ?>'</h1>


    <form action="edit-post.php" method="post">
        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>'">
        <input type="hidden" name="post_id" value="<?= $postData['id'] ?>'">

        <label for="name">Title</label>
        <input type="text" name="title" value="<?= $postData['title'] ?>">
        
        <br>

        <div>
            <label for="body">Body</label>
            <textarea type="textarea" id="body" name="body"></textarea>
        </div>

        <br>

        <button class="btn primary" value="Save Changes">Save Changes</button>
    </form>



    <form action="delete-post.php" method="post" onsubmit="return confirm('Delete Account? This cannot be undone.');">
        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>">
        <input type="hidden" name="post_id" value="<?= $post_id ?>">
        
        <button class="btn danger">Delete Post</button>
    </form>

    <br>
    <a href="/core/posts/view.php?post_id=<?= $postData['id'] ?>" class="btn secondary">Close</a>




    <?php include '../html-elements/footer.php'; ?>
    

    <script>
      const easymde = new EasyMDE({
        element: document.getElementById('body'),
      });

      easymde.value("<?= $postData['body'] ?>");
    </script>

</body>
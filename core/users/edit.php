<?php
require_once '../../config.php';

session_start();

if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $mysqli = $_DB;

    if (isset($_GET['user_id'])) {

        $accountId = (int)$_GET['user_id']; 
    
        $userQuery = sprintf("
        SELECT
        u.id,
        u.username, 
        u.name, 
        u.bio, 
        u.profile_picture
        FROM users u 
        WHERE u.id = '%s'", 
        $mysqli->real_escape_string($accountId));
    
    
        $userResult = $mysqli->query($userQuery);
        $userData = $userResult->fetch_assoc();

        
    
    } else {
        header('Location: /core/main.php');
        exit();
    }

    if ( ! isset($_SESSION['user_id'])) {
        header('Location: ../errors/user-edit.php');
        exit();
    }

    
    if (($_SESSION['user_id'] !== $userData['id']) &&  (! is_admin($_SESSION['user_id'])) ) {
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
    <title>Editing <?= $userData['username'] ?>'s Profile</title>
    <?php include '../html-elements/header.php' ?>
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
    
    <script src="https://cdnjs.cloudflare.com/ajax/libs/croppie/2.6.5/croppie.min.js" integrity="sha512-Gs+PsXsGkmr+15rqObPJbenQ2wB3qYvTHuJO6YJzPe/dTLvhy0fmae2BcnaozxDo5iaF8emzmCZWbQ1XXiX2Ig==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
    <script src="../../static/js/cropper.js"></script>
</head>
<body>
    <?php include '../html-elements/navbar.php'; ?>

    <h1>Editing: '<?= $userData['username'] ?>'</h1>


    <form action="edit-user.php" method="post" enctype="multipart/form-data">
        <input type="hidden" name="user_id" value="<?= $_SESSION['user_id'] ?>'">
        
        <label for="name">Name</label>
        <input type="text" name="name" value="<?= $userData['name'] ?>">
        
        <br>

        <label for="name">Bio</label>
        <input type="text" name="bio" value="<?= $userData['bio'] ?>">

        <br>

        <label for="image">Image file</label>
        <input type="file" id="image" name="image" accept="image/*">

        <input type="hidden" name="current_profile_picture" value="<?= $userData['profile_picture'] ?>">

        <br>

        <button class="btn primary" value="Save Changes">Save Changes</button>
    </form>



    <form action="delete-user.php" method="post" onsubmit="return confirm('Delete Account? This cannot be undone.');">
        <input type="hidden" name="user_id" value="<?= $userData['id'] ?>">

        <button class="btn danger">Delete Account</button>
    </form>

    <br>
    <a href="/core/users/user.php?user_id=<?= $userData['id'] ?>" class="btn secondary">Close</a>

    


    <?php include '../html-elements/footer.php'; ?>
    
</body>
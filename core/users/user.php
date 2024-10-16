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
            u.profile_picture, 
            u.is_admin, 
            u.created_at,
            u.followers,
            u.following
        FROM 
            users u 
        WHERE 
            u.id = '%s';", 
        $mysqli->real_escape_string($accountId));

        $userResult = $mysqli->query($userQuery);
        $userData = $userResult->fetch_assoc();

        if (!$userData) {
            header('Location: ../errors/user-view.php');
            exit();
        }

        // Query to get posts for the user
        $postsQuery = sprintf("
        SELECT 
            p.id, 
            p.title, 
            p.body,
            p.created_at
        FROM 
            posts p 
        WHERE 
            p.poster = '%s';", 
        $mysqli->real_escape_string($accountId));

        $postsResult = $mysqli->query($postsQuery);
        $postsData = $postsResult->fetch_all(MYSQLI_ASSOC); // Fetch all posts for the user

    } else {
        header("Location: ../../index.php");
        exit();
    }
} else {
    header("Location: ../main.php");
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($userData['username']) ?>'s profile</title>
    <link rel="stylesheet" href="../../static/css/profiles.css">
    <?php include '../html-elements/header.php' ?>
</head>
<body>
    <?php include '../html-elements/navbar.php'; ?>

    <h1><?= htmlspecialchars($userData['username']) ?></h1>

    <div class="profile-details">
        <img class="profile-picture" src='../../storage/uploads/profiles/<?php echo htmlspecialchars($userData['profile_picture']); ?>' alt='Profile Picture'>
        
        <p><?= htmlspecialchars($userData['followers']) ?></p>
        <figcaption>Followers</figcaption>

        <p><?= htmlspecialchars($userData['following']) ?></p>
        <figcaption>Following</figcaption>
    </div>

    <h3><?= htmlspecialchars($userData['name']) ?></h3>

    <?php
        if (is_admin($userData['id'])) {
            echo '<p class="is-admin">Admin</p>';
        }
    ?>

    <p><?= htmlspecialchars($userData['bio']) ?></p>

    <?php if (!empty($_SESSION['user_id']) && ($userData['id'] == $_SESSION['user_id'] || is_admin($_SESSION['user_id']))) : ?>
        <a href="edit.php?user_id=<?= $userData['id'] ?>">Edit Profile</a>
    <?php endif ?>

    <h5>Joined on <?= date("d/m/Y", $userData['created_at']); ?></h5>

    <h2>Posts</h2>
        <div id="posts">
            <?php
                if ($postsData) {
                    foreach ($postsData as $post) {
                        echo '
                        <div id="post">
                        <a href="/core/posts/view.php?post_id=' . $post['id'] . '">
                        <h2>' . $post['title'] . '</h2>
                        <p>' . preg_replace('/[^A-Za-z\-]/', '', substr($post['body'], 0, 60)) . '</p>
                        
                        <p>'. date('d/m/Y', $post['created_at']) . '</p>
                        </a>
                        </div>';
                    }
                } else {
                    echo "<p>Error: " . htmlspecialchars($mysqli->error) . "</p>";
                }

            ?>
        </div>
    <?php include '../html-elements/footer.php'; ?>
</body>
</html>

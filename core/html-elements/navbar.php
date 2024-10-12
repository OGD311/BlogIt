<?php

if (isset($_SESSION['user_id'])) { 
    $user = get_user_name($_SESSION['user_id']);
} else {
    $user = null;
}

$nav = <<<EOD
<nav class="nav">
    <ul>
        <li><a href="/index.php">Home</a></li>
EOD;

if (!isset($_SESSION['user_id'])) {
    $nav .= <<<EOD
        <li><a href="/core/users/login.php">Login</a></li>
        <li><a href="/core/users/signup.php">Signup</a></li>
EOD;
} else {
    $nav .= <<<EOD
        <li><a href='/core/posts/post.php'>Post</a></li>
        <li><a href='/core/users/user.php?user_id={$_SESSION["user_id"]}'>Profile</a></li>
        <li><a href='/core/users/logout.php'>Logout</a></li>
EOD;
}

$nav .= <<<EOD
    </ul>
</nav>
EOD;


echo $nav;

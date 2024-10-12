<?php
require_once 'config.php';

session_start();

$mysqli = $_DB;


if ($_SERVER["REQUEST_METHOD"] === "GET") {

    $number_of_pages = number_of_pages();

    if (isset($_GET['page'])) {
        $current_page_number = $_GET['page'];

        if ($current_page_number > $number_of_pages) {
            header('Location: index.php?page='. $number_of_pages .'');
        }
    } else {
        $current_page_number = 1;
    }



    $sql = "SELECT p.id, p.title, p.body, p.poster, p.created_at
        FROM posts p 
        LIMIT " . $_POSTS_PER_PAGE . " 
        OFFSET " . (($current_page_number - 1) * $_POSTS_PER_PAGE) . ";";


    $result = $mysqli->query($sql);

    $posts = [];
    while ($post = $result->fetch_assoc()) {
        $posts[] = $post; 
    }

    

} else {
    exit("GET Requests only");
}

var_dump($number_of_pages, $current_page_number)
?>

<!DOCTYPE html>
<html>
    <head>
        <title>Home</title>
        <?php include 'core/html-elements/header.php' ?>
        <link rel="stylesheet" href="/static/css/ratings.css">
        <meta charset="UTF-8">
    </head>

    <body>
        <?php include 'core/html-elements/navbar.php'; ?>
        
        <br>

        <h1>Posts</h1>

        <br>


        <div id="posts" class="container-fluid text-center row justify-content-center">
            <?php
                if ($result) {
                    foreach ($posts as $post) {
                        echo '
                        <div>
                        <a href="/core/posts/view.php?post_id=' . $post['id'] . '">
                        <p><strong>' . $post['title'] .'</strong>' . $post['body'] . '</p>
                        <p>' . get_user_name($post['poster']) . ' - ' . date('d/m/Y H:i', $post['created_at']) . '</p>
                        </a>
                        </div>';
                    }
                } else {
                    echo "<p>Error: " . htmlspecialchars($mysqli->error) . "</p>";
                }

            ?>
        </div>

        
        <br>

        <div id="pages-buttons" class="">
            
            <?php

                $current_page_number = max(1, $current_page_number); 

                if ($current_page_number > $number_of_pages) {
                    echo "<span>
                    <strong> No posts to display! </strong>
                    <p>Why don't you <a href='posts/post.php'>make a post</a>?</p>";

                } else if ($current_page_number == $number_of_pages && $current_page_number == 1) {
                    echo '<span>
                    <strong> ' . $current_page_number . ' </strong>';
                    
                } else if ($current_page_number == $number_of_pages) {
                    echo '<span>
                    <a href="index.php?page=1">1</a> 
                    ... <a href="index.php?page=' . ($current_page_number - 1) . '&search='. $searchList .'&order_by='. $order_by .'"><<</a>
                    <strong> ' . $current_page_number . ' </strong>';
                    echo "<p>You've reached the end!<br>If you got here from just scrolling I would be concerned...<br><a href='index.php?page=1'>Go Home</a></p>";
                    

                } else if ($current_page_number == 1) {
                    echo '<span>
                    <strong> ' . $current_page_number .  '</strong>
                    <a href="index.php?page=' . ($current_page_number + 1) . '&search='. $searchList .'&order_by='. $order_by .'">>></a>
                    ... <a href="index.php?page=' . ($number_of_pages) . '">'. ($number_of_pages) .'</a>';

                } else {
                    echo '<span>
                    <a href="index.php?page=1">1</a> 
                    ... <a href="index.php?page=' . ($current_page_number - 1) . '&search='. $searchList .'&order_by='. $order_by .'"><<</a>
                    <strong> ' . $current_page_number . ' </strong>
                    <a href="index.php?page=' . ($current_page_number + 1) . '&search='. $searchList .'&order_by='. $order_by .'">>></a>
                    ... <a href="index.php?page=' . ($number_of_pages) . '">'. ($number_of_pages) .'</a>';

                }

                // Select a random post
                $sql = "SELECT id FROM posts ORDER BY RAND() LIMIT 1;";
                $result = $mysqli->query($sql);

                if ($result && $result->num_rows > 0) {
                    $row = $result->fetch_assoc();
                    echo '  <a href="/core/posts/view.php?post_id=' . $row['id'] . '">Random Post</a>';
                } 
                               

                echo '</span>';
            ?>

        </div>

        <?php include 'core/html-elements/footer.php'; ?>
    </body>


</html>
<?php

$GLOBALS['_DB'] = require __DIR__ . "/storage/database.php";

$GLOBALS['_PROFILE_UPLOAD_PATH'] = __DIR__ . "/storage/uploads/profiles/";

$GLOBALS['_POSTS_PER_PAGE'] = 45;

$GLOBALS['_TAGS_ALL_LIMIT'] = 16;


function is_admin($user_id) {
    $mysqli = require __DIR__ . "/storage/database.php";

    $stmt = $mysqli->prepare("SELECT is_admin FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id); 
    $stmt->execute();

    $result = $stmt->get_result();
    $is_admin = $result->fetch_assoc()['is_admin'];
    $stmt->close();

    $val = isset($is_admin) ? (bool)$is_admin : false;

    return $val;
}

function post_title($post_id) {
    $mysqli = require __DIR__ . "/storage/database.php";

    $stmt = $mysqli->prepare("SELECT title FROM posts WHERE id = ?");
    $stmt->bind_param("i", $post_id); 
    $stmt->execute();

    $result = $stmt->get_result();
    $title = $result->fetch_assoc()['title'];
    $stmt->close();

    return $title;
}

function get_user_id($username) {
    $mysqli = require __DIR__ . "/storage/database.php";

    $stmt = $mysqli->prepare("SELECT id FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();

    $result = $stmt->get_result();
    $user_id = $result->fetch_assoc()['id'];
    $stmt->close();

    return $user_id;
}

function get_user_name($id) {
    $mysqli = require __DIR__ . "/storage/database.php";

    $stmt = $mysqli->prepare("SELECT username FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    $result = $stmt->get_result();
    $username = $result->fetch_assoc()['username'];
    $stmt->close();

    return $username;
}


function get_image_upload_error($error) {
    switch ($error) {
        case UPLOAD_ERR_PARTIAL:
            exit("File only partially uploaded");
            break;
        case UPLOAD_ERR_NO_FILE:
            exit("No file was uploaded");
            break;
        case UPLOAD_ERR_EXTENSION:
            exit("File upload stopped by a PHP extension");
            break;
        case UPLOAD_ERR_FORM_SIZE:
            exit("File exceeds MAX_FILE_SIZE");
            break;
        case UPLOAD_ERR_INI_SIZE:
            exit("File exceeds upload_max_filesize in php.ini");
            break;
        case UPLOAD_ERR_NO_TMP_DIR:
            exit("Temporary folder not found");
            break;
        case UPLOAD_ERR_CANT_WRITE:
            exit("Failed to write file");
            break;
        default:
            exit("Upload error");
            break;

    }
}


function posts_count() {
    $mysqli = require __DIR__ . "/storage/database.php";

    $sql = "SELECT COUNT(*) 
    AS total_posts 
    FROM posts";

    $result = $mysqli->query($sql);

    $posts_count = $result->fetch_assoc();

    return count($posts_count);
}


function number_of_pages() {
    $posts_per_page = $GLOBALS['_POSTS_PER_PAGE'];

    $posts_count = posts_count();

    $number_of_pages = ceil($posts_count / $posts_per_page);

    return $number_of_pages;
}
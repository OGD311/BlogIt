<?php
require_once '../../config.php';

if (empty($_POST['username'])) {
    die('Username cannot be empty');
}

if (strlen($_POST['password']) < 8) {
    die('Password must be longer than 8 characters');
}

if (!preg_match('/[a-z]/i', $_POST['password'])) {
    die('Password must contain at least one letter');
}

if (!preg_match('/[0-9]/', $_POST['password'])) {
    die('Password must contain at least one number');
}

if ($_POST['password'] !== $_POST['password_confirmation']) {
    die('Passwords do not match');
}

// Hash the password
$password_hash = password_hash($_POST['password'], PASSWORD_DEFAULT);

// Prepare the database connection
$mysqli = $_DB;

// Prepare the SQL statement
$sql = "INSERT INTO users (username, name, bio, profile_picture, password_hash, created_at) VALUES (?, ?, ?, ?, ?, ?)";
$stmt = $mysqli->stmt_init();

if (!$stmt->prepare($sql)) {
    die('SQL Error: ' . $mysqli->error);
}

// Bind the parameters (ensure they're all variables)
$username = $_POST['username'];
$name = $_POST['username']; // Assuming 'name' is the same as 'username'; adjust if needed
$bio = ""; // Assuming an empty bio for now
$profile_picture = "default_picture.png";
$created_at = time();

$stmt->bind_param('ssssi', $username, $name, $bio, $profile_picture, $password_hash, $created_at);

// Execute the statement
if ($stmt->execute()) {
    // Retrieve the user ID for the newly created user
    $user_id = get_user_id($username);

    session_start();
    session_regenerate_id();

    $_SESSION['user_id'] = $user_id;

    header('Location: user.php?user_id=' . $user_id);
    exit;
} else {
    if ($mysqli->errno === 1062) {
        die('Username already taken');
    }
    die('Error: ' . $mysqli->error . ' (' . $mysqli->errno . ')');
}

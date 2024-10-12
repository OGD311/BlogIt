<?php
require_once '../../config.php';

$mysqli = $_DB; 

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    exit("POST request method required");
}

if ($_SERVER['REQUEST_METHOD'] === "POST") {

    
    if (! empty($_FILES) && $_FILES['image']['size'] != 0) {
    
        if ($_FILES["image"]["error"] !== UPLOAD_ERR_OK) {
        
            get_image_upload_error($_FILES["image"]["error"]);
        
        }
        
        
        if ($_FILES["image"]["size"] > 8388608 ) {
            exit("File too large (max 1MB)");
        }
        
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        
        $mime_type = $finfo->file($_FILES["image"]["tmp_name"]);
        
        
        $mime_types = ["image/gif", "image/png", "image/jpeg"];
        
        if ( ! in_array($_FILES["image"]["type"], $mime_types)) {
            exit("Invalid file type");
        }
        
        
        
        // Create safe path and hash for file
        $pathinfo = pathinfo($_FILES["image"]["name"]);
        
        $filehash = md5($_FILES["image"]["name"]);

        $complete_filename = $filehash . "." . $pathinfo['extension'];
        
        $destination = $_PROFILE_UPLOAD_PATH . $complete_filename;
        
        if ( ! move_uploaded_file($_FILES["image"]["tmp_name"], $destination)) {
            exit("Can't move uploaded file");
        }

        $profile_picture = $mysqli->real_escape_string($complete_filename);
        
    } else {
        $profile_picture = $mysqli->real_escape_string($_POST['current_profile_picture']);
    }
    
    // Upload to SQL
    
  

    $sql = "UPDATE users SET name = ?, bio = ?, profile_picture = ? WHERE id = ?";
    
    $stmt = $mysqli->stmt_init();

    if (!$stmt->prepare($sql)) {
        die("SQL Error: " . $mysqli->error);
    }


    $name = $mysqli->real_escape_string($_POST['name']);
    $bio = $mysqli->real_escape_string($_POST['bio']);
    

    $user_id = (int)$_POST['user_id'];


    $stmt->bind_param("sssi", $name, $bio, $profile_picture, $user_id);


    if ($stmt->execute()) {
        header('Location: /core/users/user.php?user_id=' . $user_id);
        exit(); 

    } else {
        die("Error updating user: " . $stmt->error);
    }
} else {
    header('Location: /core/main.php');
    exit();
}
?>

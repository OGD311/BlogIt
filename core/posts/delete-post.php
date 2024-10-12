<?php
require_once '../../config.php';

$mysqli = $_DB; 
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    session_start();
    
    try {

        $mysqli->begin_transaction();

        $post_id = (int)$_POST['post_id'];
        $user_id = (int)$_POST['user_id'];
    
        $sql = "DELETE FROM posts WHERE id = ? AND poster = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("ii", $post_id, $user_id);
            if ($stmt->execute()) {

                $mysqli->commit();

                header('Location: /index.php');
                exit(); 

            } else {
                throw new Exception("Error deleting post: " . htmlspecialchars($stmt->error));
            }
        } else {
            throw new Exception("SQL Error for user: " . htmlspecialchars($mysqli->error));
        }
    
    } catch (Exception $e) {

        $mysqli->rollback();
        die("Transaction failed: " . htmlspecialchars($e->getMessage()));
    }
    

} else {
    header('Location: /core/main.php');
    exit();
}

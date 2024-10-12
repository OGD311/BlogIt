<?php
require_once '../../config.php';

$mysqli = $_DB; 
if ($_SERVER['REQUEST_METHOD'] === "POST") {

    session_start();
    
    try {

        $mysqli->begin_transaction();
    
        $userId = (int)$_POST['user_id'];
    
        $sql = "DELETE FROM users WHERE id = ?";
        if ($stmt = $mysqli->prepare($sql)) {
            $stmt->bind_param("i", $userId);
            if ($stmt->execute()) {

                $mysqli->commit();
                if ($_SESSION['user_id'] == $userId) {
                    session_destroy();
                }
                header('Location: /core/main.php');
                exit(); 

            } else {
                throw new Exception("Error deleting account: " . htmlspecialchars($stmt->error));
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

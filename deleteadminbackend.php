<?php
require_once 'Classes/DBconnection.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['admin']);

    $adminId = $_GET['id'];

    
    if ($adminId == $_SESSION['user_id']) {
        header("Location: manageadminaccounts.php?error=1");
        exit();
    }

    $conn = DBconnection::getInstance()->getConnection();

    $sql  = "DELETE FROM users WHERE id = ? AND role = 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $adminId);

    if ($stmt->execute()) {
        header("Location: manageadminaccounts.php?success=1");
    } else {
        header("Location: manageadminaccounts.php?error=1");
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: manageadminaccounts.php?error=1");
    exit();
}
?>
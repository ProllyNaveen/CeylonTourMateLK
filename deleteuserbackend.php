<?php
require_once 'Classes/DBconnection.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['admin']);

    $userId = $_GET['id'];

    $conn = DBconnection::getInstance()->getConnection();

    $sql  = "DELETE FROM users WHERE id = ? AND role != 'admin'";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);

    if ($stmt->execute()) {
        header("Location: viewuseraccounts.php?success=1");
    } else {
        header("Location: viewuseraccounts.php?error=1");
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: viewuseraccounts.php?error=1");
    exit();
}
?>
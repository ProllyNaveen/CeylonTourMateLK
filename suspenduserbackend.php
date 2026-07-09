<?php
require_once 'Classes/DBconnection.php';
$from = isset($_GET['from']) ? $_GET['from'] : 'list';
try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['admin']);

    $userId = $_GET['id'];
    $action = $_GET['action'];

    $conn = DBconnection::getInstance()->getConnection();

    $isSuspended = ($action == 'suspend') ? 1 : 0;

    $sql  = "UPDATE users SET is_suspended = ? WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ii", $isSuspended, $userId);

    if ($stmt->execute()) {
    if ($from == 'profile') {
        header("Location: viewuserprofile.php?id=" . $userId . "&success=1");
    } else {
        header("Location: viewuseraccounts.php?success=1");
    }
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
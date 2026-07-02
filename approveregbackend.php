<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/User.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['admin']);

    $userId = $_GET['id'];
    $action = $_GET['action'];

    $user   = new User(
        null, null, null, null, null,
        null, null, null, null, null, null, null
    );

    $result = $user->approveRejectRegistration($userId, $action);

    if ($result == "success") {
        header("Location: viewpendingrequests.php?success=1");
    } else {
        header("Location: viewpendingrequests.php?error=1");
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: viewpendingrequests.php?error=1");
    exit();
}
?>
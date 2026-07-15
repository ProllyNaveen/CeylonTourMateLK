<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/User.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    
    if (!isset($_SESSION['reset_user_id']) || !isset($_SESSION['reset_verified'])) {
        header("Location: forgotpassword.php");
        exit();
    }

    $newPassword     = $_POST['new_password'];
    $userId          = $_SESSION['reset_user_id'];

    $user = new User(
        null, null, null, null, $newPassword,
        null, null, null, null, null, null, null
    );

    $result = $user->resetPassword($userId);

    if ($result != "success") {
        header("Location: resetpassword.php?error=1");
        exit();
    }

    
    unset($_SESSION['reset_user_id']);
    unset($_SESSION['reset_security_question']);
    unset($_SESSION['reset_verified']);

    header("Location: login.php?resetdone=1");
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: resetpassword.php?error=1");
    exit();
}
?>
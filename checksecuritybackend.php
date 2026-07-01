<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/User.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // make sure step 1 was completed first
    if (!isset($_SESSION['reset_user_id'])) {
        header("Location: forgotpassword.php");
        exit();
    }

    $securityAnswer = trim($_POST['security_answer']);
    $userId         = $_SESSION['reset_user_id'];

    $user = new User(
        null, null, null, null, null,
        null, null, null, null, null, null, $securityAnswer
    );

    $result = $user->verifySecurityAnswer($userId);

    if ($result == "wrong") {
        header("Location: answersecurity.php?wrong=1");
        exit();
    }

    // answer correct — allow password reset
    $_SESSION['reset_verified'] = true;

    header("Location: resetpassword.php");
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: answersecurity.php?error=1");
    exit();
}
?>
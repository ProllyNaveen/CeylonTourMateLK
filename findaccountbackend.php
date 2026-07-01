<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/User.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $username = trim($_POST['username']);

    $user = new User(
        null, null, $username, null, null,
        null, null, null, null, null, null, null
    );

    $result = $user->findAccount();

    if ($result == null) {
        header("Location: forgotpassword.php?notfound=1");
        exit();
    }

    // store user id and security question in session for next step
    $_SESSION['reset_user_id']        = $result['id'];
    $_SESSION['reset_security_question'] = $result['security_question'];

    header("Location: answersecurity.php");
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: forgotpassword.php?error=1");
    exit();
}
?>
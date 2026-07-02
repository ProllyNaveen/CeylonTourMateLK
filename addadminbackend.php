<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/User.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['admin']);

    $firstname = trim($_POST['firstname']);
	$lastname  = trim($_POST['lastname']);
	$username  = trim($_POST['username']);
	$email     = trim($_POST['email']);
	$password  = $_POST['password'];
	$phone     = trim($_POST['phone']); // add this line

    // security question and answer set automatically from email
    $securityQuestion = "What is your registered email address on this platform?";
    $securityAnswer   = $email;

    $user = new User(
		$firstname, $lastname, $username, $email, $password,
		$phone, 'uploads/profiles/default.png', 'admin', 0, 1,
		$securityQuestion, $securityAnswer
	);

    $result = $user->register();

    if ($result == "exists") {
        header("Location: manageadminaccounts.php?exists=1");
        exit();
    }

    if ($result != "success") {
        header("Location: manageadminaccounts.php?error=1");
        exit();
    }

    header("Location: manageadminaccounts.php?success=1");
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: manageadminaccounts.php?error=1");
    exit();
}
?>
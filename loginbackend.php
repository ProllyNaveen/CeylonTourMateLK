<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/User.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    $username = trim($_POST['username']);
    $password = $_POST['password'];

    $user = new User(
        null, null, $username, null, $password,
        null, null, null, null, null, null, null
    );

    $result = $user->login();

    switch ($result) {
        case "success":
            switch ($_SESSION['role']) {
                case 'admin':
                    header("Location: admindashboard.php");
                    break;
                case 'tourist':
                    header("Location: touristdashboard.php");
                    break;
                case 'tour_guide':
                    header("Location: tourguidedashboard.php");
                    break;
                case 'hotel_owner':
                    header("Location: hotelownerdashboard.php");
                    break;
                case 'shop_owner':
                    header("Location: shopownerdashboard.php");
                    break;
                case 'experience_provider':
                    header("Location: experiencedashboard.php");
                    break;
                default:
                    header("Location: login.php?error=1");
                    break;
            }
            break;
        case "suspended":
            header("Location: login.php?suspended=1");
            break;
        case "notapproved":
            header("Location: login.php?notapproved=1");
            break;
        case "wrongpassword":
            header("Location: login.php?wrongpassword=1");
            break;
        case "notfound":
            header("Location: login.php?notfound=1");
            break;
        default:
            header("Location: login.php?error=1");
            break;
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: login.php?error=1");
    exit();
}
?>
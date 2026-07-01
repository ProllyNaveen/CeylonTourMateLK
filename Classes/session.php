<?php

function checkSession() {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // if not logged in redirect to login
    if (!isset($_SESSION['user_id']) || !isset($_SESSION['role'])) {
        header("Location: ../login.php");
        exit();
    }
}

function checkRole($allowedRoles) {
    checkSession();

    // if logged in but wrong role redirect to their own dashboard
    if (!in_array($_SESSION['role'], $allowedRoles)) {
        switch ($_SESSION['role']) {
            case 'admin':
                header("Location: ../admindashboard.php");
                break;
            case 'tourist':
                header("Location: ../touristdashboard.php");
                break;
            case 'tour_guide':
                header("Location: ../tourguidedashboard.php");
                break;
            case 'hotel_owner':
                header("Location: ../hotelownerdashboard.php");
                break;
            case 'shop_owner':
                header("Location: ../shopownerdashboard.php");
                break;
            case 'experience_provider':
                header("Location: ../experiencedashboard.php");
                break;
            default:
                header("Location: ../login.php");
                break;
        }
        exit();
    }
}
?>
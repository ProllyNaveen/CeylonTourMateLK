<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/ExperienceListing.php';
try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require_once 'Classes/session.php';
    checkRole(['experience_provider']);
    $listingId = $_GET['id'];
    $userId    = $_SESSION['user_id'];

    $experienceListing = new ExperienceListing($userId, null, null, null, null, null, null, null, null, null);
    $result = $experienceListing->deleteExperienceListing($listingId, $userId);

    if ($result == "success") {
        header("Location: experiencemanagelistings.php?success=1");
    } elseif ($result == "unauthorized") {
        header("Location: experiencemanagelistings.php?error=1");
    } else {
        header("Location: experiencemanagelistings.php?error=1");
    }
    exit();
} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: experiencemanagelistings.php?error=1");
    exit();
}
?>
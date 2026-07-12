<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/TourGuideListing.php';
try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require_once 'Classes/session.php';
    checkRole(['tour_guide']);
    $listingId = $_GET['id'];
    $userId    = $_SESSION['user_id'];

    $tourGuideListing = new TourGuideListing($userId, null, null, null, null, null, null, null, null, null);
    $result = $tourGuideListing->deleteTourGuideListing($listingId, $userId);

    if ($result == "success") {
        header("Location: tourguidemanagelistings.php?success=1");
    } elseif ($result == "unauthorized") {
        header("Location: tourguidemanagelistings.php?error=1");
    } else {
        header("Location: tourguidemanagelistings.php?error=1");
    }
    exit();
} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: tourguidemanagelistings.php?error=1");
    exit();
}
?>
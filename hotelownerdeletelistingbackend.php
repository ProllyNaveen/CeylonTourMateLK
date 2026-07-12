<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/HotelListing.php';
try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require_once 'Classes/session.php';
    checkRole(['hotel_owner']);
    $listingId = $_GET['id'];
    $userId    = $_SESSION['user_id'];

    $hotelListing = new HotelListing($userId, null, null, null, null, null, null, null, null, null, null);
    $result = $hotelListing->deleteHotelListing($listingId, $userId);

    if ($result == "success") {
        header("Location: hotelownermanagelistings.php?success=1");
    } elseif ($result == "unauthorized") {
        header("Location: hotelownermanagelistings.php?error=1");
    } else {
        header("Location: hotelownermanagelistings.php?error=1");
    }
    exit();
} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: hotelownermanagelistings.php?error=1");
    exit();
}
?>
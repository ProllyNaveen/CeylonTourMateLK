<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/HotelListing.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['hotel_owner']);

    $userId       = $_SESSION['user_id'];
    $listingId    = $_POST['listing_id'];
    $title        = trim($_POST['title']);
    $description  = trim($_POST['description']);
    $location     = trim($_POST['location']);
    $city         = $_POST['city'];
    $price        = $_POST['price'];
    $starRating   = $_POST['star_rating'];
    $amenities    = trim($_POST['amenities']);
    $totalRooms   = $_POST['total_rooms'];
    $checkInTime  = $_POST['check_in_time'];
    $checkOutTime = $_POST['check_out_time'];

    $hotelListing = new HotelListing(
        $userId, $title, $description, $location, $city, $price,
        $starRating, $amenities, $totalRooms, $checkInTime, $checkOutTime
    );

    $result = $hotelListing->updateHotelListing($listingId);

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
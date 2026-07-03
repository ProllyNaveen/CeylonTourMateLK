<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Room.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['hotel_owner']);

    $roomId         = $_POST['room_id'];
    $hotelListingId = $_POST['hotel_listing_id'];
    $description    = trim($_POST['description']);
    $roomType       = $_POST['room_type'];
    $pricePerNight  = $_POST['price_per_night'];
    $capacity       = $_POST['capacity'];

    $room   = new Room($hotelListingId, $description, $roomType, $pricePerNight, $capacity);
    $result = $room->updateRoom($roomId);

    if ($result == "success") {
        header("Location: hotelownermanagerooms.php?hotel_listing_id=" . $hotelListingId . "&success=1");
    } else {
        header("Location: hotelownermanagerooms.php?hotel_listing_id=" . $hotelListingId . "&error=1");
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: hotelownermanagerooms.php?error=1");
    exit();
}
?>
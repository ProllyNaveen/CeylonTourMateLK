<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Room.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['hotel_owner']);

    $roomId         = $_GET['id'];
    $hotelListingId = $_GET['hotel_listing_id'];

    $room   = new Room(null, null, null, null, null);
    $result = $room->deleteRoom($roomId);

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
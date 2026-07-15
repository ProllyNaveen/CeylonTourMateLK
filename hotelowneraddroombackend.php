<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/HotelListing.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['hotel_owner']);

    $userId          = $_SESSION['user_id'];
    $hotelListingId  = $_POST['hotel_listing_id'];
    $description     = trim($_POST['description']);
    $roomType        = $_POST['room_type'];
    $pricePerNight   = $_POST['price_per_night'];
    $capacity        = $_POST['capacity'];

    
    $conn    = DBconnection::getInstance()->getConnection();
    $chkSql  = "SELECT hl.id FROM hotel_listings hl
                 JOIN listings l ON hl.listing_id = l.id
                 WHERE hl.id = ? AND l.user_id = ?";
    $chkStmt = $conn->prepare($chkSql);
    $chkStmt->bind_param("ii", $hotelListingId, $userId);
    $chkStmt->execute();
    $chkStmt->store_result();

    if ($chkStmt->num_rows == 0) {
        header("Location: hotelownermanagerooms.php?hotel_listing_id=" . $hotelListingId . "&error=1");
        exit();
    }

    
    $hotelListing = new HotelListing(
        $userId, null, null, null, null, null,
        null, null, null, null, null
    );

    $result = $hotelListing->addRoom(
        $hotelListingId, $description, $roomType, $pricePerNight, $capacity
    );

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
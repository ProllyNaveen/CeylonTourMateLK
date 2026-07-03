<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Listing.php';
require_once 'Classes/ListingImage.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['hotel_owner']);

    $listingId = $_GET['id'];
    $userId    = $_SESSION['user_id'];
    $conn      = DBconnection::getInstance()->getConnection();

    // delete listing images first
    ListingImage::deleteAllForListing($listingId);

    // get hotel_listing_id first for room deletion
    $hlSql  = "SELECT id FROM hotel_listings WHERE listing_id = ?";
    $hlStmt = $conn->prepare($hlSql);
    $hlStmt->bind_param("i", $listingId);
    $hlStmt->execute();
    $hlResult = $hlStmt->get_result();
    $hl       = $hlResult->fetch_assoc();

    if ($hl) {
        // delete rooms first
        $roomSql  = "DELETE FROM rooms WHERE hotel_listing_id = ?";
        $roomStmt = $conn->prepare($roomSql);
        $roomStmt->bind_param("i", $hl['id']);
        $roomStmt->execute();
    }

    // delete hotel_listings subtype row
    $sql  = "DELETE FROM hotel_listings WHERE listing_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $listingId);
    $stmt->execute();

    // delete parent listing row
    $listing = new Listing($userId, null, null, null, null, null, null);
    $result  = $listing->deleteListing($listingId, $userId);

    if ($result == "success") {
        header("Location: hotelownermanagelistings.php?success=1");
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
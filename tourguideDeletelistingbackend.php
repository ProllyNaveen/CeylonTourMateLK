<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Listing.php';
require_once 'Classes/ListingImage.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['tour_guide']);

    $listingId = $_GET['id'];
    $userId    = $_SESSION['user_id'];
    $conn      = DBconnection::getInstance()->getConnection();

    // delete listing images first
    ListingImage::deleteAllForListing($listingId);

    // delete tourguide_listings subtype row
    $sql  = "DELETE FROM tourguide_listings WHERE listing_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $listingId);
    $stmt->execute();

    // delete parent listing row
    $listing = new Listing($userId, null, null, null, null, null, null);
    $result  = $listing->deleteListing($listingId, $userId);

    if ($result == "success") {
        header("Location: tourguidemanagelistings.php?success=1");
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
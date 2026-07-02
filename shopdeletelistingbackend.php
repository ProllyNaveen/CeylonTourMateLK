<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Listing.php';
require_once 'Classes/ListingImage.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['shop_owner']);

    $listingId = $_GET['id'];
    $userId    = $_SESSION['user_id'];
    $conn      = DBconnection::getInstance()->getConnection();

    // delete listing images first
    ListingImage::deleteAllForListing($listingId);

    // delete shop_listings subtype row
    $sql  = "DELETE FROM shop_listings WHERE listing_id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $listingId);
    $stmt->execute();

    // delete parent listing row
    $listing = new Listing($userId, null, null, null, null, null, null);
    $result  = $listing->deleteListing($listingId, $userId);

    if ($result == "success") {
        header("Location: shopmanagelistings.php?success=1");
    } else {
        header("Location: shopmanagelistings.php?error=1");
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: shopmanagelistings.php?error=1");
    exit();
}
?>
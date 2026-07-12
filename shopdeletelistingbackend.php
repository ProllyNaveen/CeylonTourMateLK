<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/ShopListing.php';
try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require_once 'Classes/session.php';
    checkRole(['shop_owner']);
    $listingId = $_GET['id'];
    $userId    = $_SESSION['user_id'];

    $shopListing = new ShopListing($userId, null, null, null, null, null, null, null, null);
    $result = $shopListing->deleteShopListing($listingId, $userId);

    if ($result == "success") {
        header("Location: shopmanagelistings.php?success=1");
    } elseif ($result == "unauthorized") {
        header("Location: shopmanagelistings.php?error=1");
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
<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/ShopListing.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['shop_owner']);

    $userId      = $_SESSION['user_id'];
    $listingId   = $_POST['listing_id'];
    $title       = trim($_POST['title']);
    $description = trim($_POST['description']);
    $location    = trim($_POST['location']);
    $city        = $_POST['city'];
    $price       = $_POST['price'];
    $shopType    = $_POST['shop_type'];
    $openTime    = $_POST['open_time'];
    $closeTime   = $_POST['close_time'];

    $shopListing = new ShopListing(
        $userId, $title, $description, $location, $city, $price,
        $shopType, $openTime, $closeTime
    );

    $result = $shopListing->updateShopListing($listingId);

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
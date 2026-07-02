<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/ShopListing.php';
require_once 'Classes/Listing.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['shop_owner']);

    $userId      = $_SESSION['user_id'];
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

    $listingId = $shopListing->addShopListing();

    if (!$listingId) {
        header("Location: shopaddlisting.php?error=1");
        exit();
    }

    // handle image uploads
    if (isset($_FILES['images']) && $_FILES['images']['error'][0] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $uploadDir    = 'uploads/listingimages/';

        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $fileType = $_FILES['images']['type'][$key];
            if (in_array($fileType, $allowedTypes)) {
                $fileName   = time() . '_' . $key . '_' . basename($_FILES['images']['name'][$key]);
                $uploadPath = $uploadDir . $fileName;
                if (move_uploaded_file($tmpName, $uploadPath)) {
                    $shopListing->uploadImage($listingId, $uploadPath);
                }
            }
        }
    }

    header("Location: shopaddlisting.php?success=1");
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: shopaddlisting.php?error=1");
    exit();
}
?>
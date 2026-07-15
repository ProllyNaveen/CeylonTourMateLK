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

    $listingId = $hotelListing->addHotelListing();

    if (!$listingId) {
        header("Location: hotelowneraddlisting.php?error=1");
        exit();
    }

    
    if (isset($_FILES['images']) && $_FILES['images']['error'][0] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $uploadDir    = 'uploads/listingimages/';

        foreach ($_FILES['images']['tmp_name'] as $key => $tmpName) {
            $fileType = $_FILES['images']['type'][$key];
            if (in_array($fileType, $allowedTypes)) {
                $fileName   = time() . '_' . $key . '_' . basename($_FILES['images']['name'][$key]);
                $uploadPath = $uploadDir . $fileName;
                if (move_uploaded_file($tmpName, $uploadPath)) {
                    $hotelListing->uploadImage($listingId, $uploadPath);
                }
            }
        }
    }

    header("Location: hotelowneraddlisting.php?success=1");
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: hotelowneraddlisting.php?error=1");
    exit();
}
?>
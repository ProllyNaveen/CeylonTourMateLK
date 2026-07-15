<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/TourGuideListing.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['tour_guide']);

    $userId         = $_SESSION['user_id'];
    $title          = trim($_POST['title']);
    $description    = trim($_POST['description']);
    $location       = trim($_POST['location']);
    $city           = $_POST['city'];
    $price          = $_POST['price'];
    $languages      = trim($_POST['languages']);
    $specialization = $_POST['specialization'];
    $experienceYears = $_POST['experience_years'];
    $availability   = $_POST['availability'];

    $tourGuideListing = new TourGuideListing(
        $userId, $title, $description, $location, $city, $price,
        $languages, $specialization, $experienceYears, $availability
    );

    $listingId = $tourGuideListing->addTourGuideListing();

    if (!$listingId) {
        header("Location: tourguideaddlisting.php?error=1");
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
                    $tourGuideListing->uploadImage($listingId, $uploadPath);
                }
            }
        }
    }

    header("Location: tourguideaddlisting.php?success=1");
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: tourguideaddlisting.php?error=1");
    exit();
}
?>
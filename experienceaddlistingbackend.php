<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/ExperienceListing.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['experience_provider']);

    $userId         = $_SESSION['user_id'];
    $title          = trim($_POST['title']);
    $description    = trim($_POST['description']);
    $location       = trim($_POST['location']);
    $city           = $_POST['city'];
    $price          = $_POST['price'];
    $experienceType = $_POST['experience_type'];
    $duration       = $_POST['duration'];
    $maxGroupSize   = $_POST['max_group_size'];
    $availability   = $_POST['availability'];

    $experienceListing = new ExperienceListing(
        $userId, $title, $description, $location, $city, $price,
        $experienceType, $duration, $maxGroupSize, $availability
    );

    $listingId = $experienceListing->addExperienceListing();

    if (!$listingId) {
        header("Location: experienceaddlisting.php?error=1");
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
                    $experienceListing->uploadImage($listingId, $uploadPath);
                }
            }
        }
    }

    header("Location: experienceaddlisting.php?success=1");
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: experienceaddlisting.php?error=1");
    exit();
}
?>
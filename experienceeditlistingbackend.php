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
    $listingId      = $_POST['listing_id'];
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

    $result = $experienceListing->updateExperienceListing($listingId);

    if ($result == "success") {
        header("Location: experiencemanagelistings.php?success=1");
    } elseif ($result == "unauthorized") {
        header("Location: experiencemanagelistings.php?error=1");
    } else {
        header("Location: experiencemanagelistings.php?error=1");
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: experiencemanagelistings.php?error=1");
    exit();
}
?>
<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/TourGuideListing.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['tour_guide']);

    $userId          = $_SESSION['user_id'];
    $listingId       = $_POST['listing_id'];
    $title           = trim($_POST['title']);
    $description     = trim($_POST['description']);
    $location        = trim($_POST['location']);
    $city            = $_POST['city'];
    $price           = $_POST['price'];
    $languages       = trim($_POST['languages']);
    $specialization  = $_POST['specialization'];
    $experienceYears = $_POST['experience_years'];
    $availability    = $_POST['availability'];

    $tourGuideListing = new TourGuideListing(
        $userId, $title, $description, $location, $city, $price,
        $languages, $specialization, $experienceYears, $availability
    );

    $result = $tourGuideListing->updateTourGuideListing($listingId);

    if ($result == "success") {
        header("Location: tourguidemanagelistings.php?success=1");
    } elseif ($result == "unauthorized") {
        header("Location: tourguidemanagelistings.php?error=1");
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
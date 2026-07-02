<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Review.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['tourist']);

    $touristId = $_SESSION['user_id'];
    $bookingId = $_POST['booking_id'];
    $listingId = $_POST['listing_id'];
    $rating    = $_POST['rating'];
    $comment   = trim($_POST['comment']);

    $review = new Review($touristId, $listingId, $bookingId, $rating, $comment);
    $result = $review->writeReview();

    switch ($result) {
        case "success":
            header("Location: mybookings.php?reviewsuccess=1");
            break;
        case "already":
            header("Location: writereview.php?booking_id=" . $bookingId . "&already=1");
            break;
        default:
            header("Location: writereview.php?booking_id=" . $bookingId . "&error=1");
            break;
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: mybookings.php?error=1");
    exit();
}
?>
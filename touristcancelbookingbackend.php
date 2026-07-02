<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Booking.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['tourist']);

    $bookingId = $_GET['id'];
    $touristId = $_SESSION['user_id'];

    $booking = new Booking($touristId, null, null, null, null);
    $result  = $booking->cancelBooking($bookingId, false);

    switch ($result) {
        case "success":
            header("Location: mybookings.php?cancelled=1");
            break;
        case "unauthorized":
            header("Location: mybookings.php?error=1");
            break;
        case "cannotcancel":
            header("Location: mybookings.php?cannotcancel=1");
            break;
        default:
            header("Location: mybookings.php?error=1");
            break;
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: mybookings.php?error=1");
    exit();
}
?>
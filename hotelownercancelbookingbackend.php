<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Booking.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['hotel_owner']);

    $bookingId = $_GET['id'];
    $partnerId = $_SESSION['user_id'];

    $booking = new Booking(null, null, null, null, null);
    $result  = $booking->cancelBooking($bookingId, true);

    if ($result == "success") {
        header("Location: hotelownermybookings.php?cancelled=1");
    } else {
        header("Location: hotelownermybookings.php?error=1");
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: hotelownermybookings.php?error=1");
    exit();
}
?>
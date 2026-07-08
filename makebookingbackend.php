<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Booking.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['tourist']);

    $touristId     = $_SESSION['user_id'];
    $listingId     = $_POST['listing_id'];
    $listingType   = $_POST['listing_type'];
    $startDatetime = $_POST['start_datetime'];
    $endDatetime   = $_POST['end_datetime'];
    $guestCount    = $_POST['guest_count'];
    $roomIds       = isset($_POST['room_ids']) ? $_POST['room_ids'] : [];

    $booking = new Booking(
        $touristId, $listingId, $startDatetime, $endDatetime, $guestCount
    );

    $result = $booking->makeBooking($roomIds);

switch ($result) {
    case "success":
        header("Location: mybookings.php?success=1");
        break;
    case "invalidlisting":
        header("Location: makebooking.php?listing_id=" . $listingId . "&error=1");
        break;
    case "noroomsselected":
        header("Location: makebooking.php?listing_id=" . $listingId . "&noroomsselected=1");
        break;
    case "roomunavailable":
        header("Location: makebooking.php?listing_id=" . $listingId . "&unavailable=1");
        break;
    default:
        header("Location: makebooking.php?listing_id=" . $listingId . "&error=1");
        break;
}
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: mybookings.php?error=1");
    exit();
}
?>
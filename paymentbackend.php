<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Payment.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['tourist']);

    $userId    = $_SESSION['user_id'];
    $bookingId = $_POST['booking_id'];

    
    $conn    = DBconnection::getInstance()->getConnection();
    $sql     = "SELECT total_price, status FROM bookings WHERE id = ? AND tourist_id = ?";
    $stmt    = $conn->prepare($sql);
    $stmt->bind_param("ii", $bookingId, $userId);
    $stmt->execute();
    $result  = $stmt->get_result();

    if ($result->num_rows == 0) {
        header("Location: mybookings.php?error=1");
        exit();
    }

    $booking = $result->fetch_assoc();
    $amount  = $booking['total_price'];

    $payment = new Payment(
        $userId, null, $bookingId, 'booking',
        $amount, 'completed', null, null
    );

    $payResult = $payment->completePayment();

    switch ($payResult) {
        case "success":
            header("Location: mybookings.php?paidsuccess=1");
            break;
        case "unauthorized":
            header("Location: mybookings.php?error=1");
            break;
        case "notconfirmed":
            header("Location: mybookings.php?notconfirmed=1");
            break;
        default:
            header("Location: payment.php?booking_id=" . $bookingId . "&error=1");
            break;
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: mybookings.php?error=1");
    exit();
}
?>
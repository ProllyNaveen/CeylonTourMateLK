<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Message.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['hotel_owner']);

    $senderId   = $_SESSION['user_id'];
    $receiverId = $_POST['receiver_id'];
    $listingId  = $_POST['listing_id'];
    $message    = trim($_POST['message']);

    $msg    = new Message($senderId, $receiverId, $listingId, $message);
    $result = $msg->replyToMessage();

    if ($result == "success") {
        header("Location: hotelownermessages.php?user_id=" . $receiverId . "&listing_id=" . $listingId . "&success=1");
    } else {
        header("Location: hotelownermessages.php?user_id=" . $receiverId . "&listing_id=" . $listingId . "&error=1");
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: hotelownermessages.php?error=1");
    exit();
}
?>
<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Message.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['tourist']);

    $senderId   = $_SESSION['user_id'];
    $receiverId = $_POST['receiver_id'];
    $listingId  = $_POST['listing_id'];
    $message    = trim($_POST['message']);

    $msg    = new Message($senderId, $receiverId, $listingId, $message);
    $result = $msg->sendMessage();

    if ($result == "success") {
        header("Location: listingdetail.php?id=" . $listingId . "&msgsent=1");
    } else {
        header("Location: listingdetail.php?id=" . $listingId . "&error=1");
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: touristdashboard.php?error=1");
    exit();
}
?>
<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Message.php';
try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }
    require_once 'Classes/session.php';
    checkRole(['tourist', 'tour_guide', 'hotel_owner', 'shop_owner', 'experience_provider']);
    $senderId   = $_SESSION['user_id'];
    $receiverId = $_POST['receiver_id'];
    $listingId  = $_POST['listing_id'];
    $message    = trim($_POST['message']);
    $redirect   = isset($_POST['redirect']) ? $_POST['redirect'] : 'listingdetail.php?id=' . $listingId;

    $msg    = new Message($senderId, $receiverId, $listingId, $message);
    $result = $msg->sendMessage();

    if ($result == "success") {
        header("Location: " . $redirect . (strpos($redirect, '?') !== false ? '&' : '?') . "msgsent=1");
    } else {
        header("Location: " . $redirect . (strpos($redirect, '?') !== false ? '&' : '?') . "error=1");
    }
    exit();
} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: touristdashboard.php?error=1");
    exit();
}
?>
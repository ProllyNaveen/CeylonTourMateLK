<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Notification.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['admin']);

    $sendTo   = $_POST['send_to'];
    $message  = trim($_POST['message']);
    $senderId = $_SESSION['user_id'];

    $conn = DBconnection::getInstance()->getConnection();

    if ($sendTo == 'specific') {
        // send to one user
        $receiverId   = $_POST['receiver_id'];
        $notification = new Notification($senderId, $receiverId, $message);
        $notification->sendNotification();

    } else {
        // send to a group — build query based on send_to value
        switch ($sendTo) {
            case 'all':
                $sql = "SELECT id FROM users WHERE role != 'admin'";
                $stmt = $conn->prepare($sql);
                break;
            case 'tourists':
                $sql = "SELECT id FROM users WHERE role = 'tourist'";
                $stmt = $conn->prepare($sql);
                break;
            case 'tour_guides':
                $sql = "SELECT id FROM users WHERE role = 'tour_guide'";
                $stmt = $conn->prepare($sql);
                break;
            case 'hotel_owners':
                $sql = "SELECT id FROM users WHERE role = 'hotel_owner'";
                $stmt = $conn->prepare($sql);
                break;
            case 'shop_owners':
                $sql = "SELECT id FROM users WHERE role = 'shop_owner'";
                $stmt = $conn->prepare($sql);
                break;
            case 'experience_providers':
                $sql = "SELECT id FROM users WHERE role = 'experience_provider'";
                $stmt = $conn->prepare($sql);
                break;
            default:
                header("Location: sendnotifications.php?error=1");
                exit();
        }

        $stmt->execute();
        $result = $stmt->get_result();

        while ($row = $result->fetch_assoc()) {
            $notification = new Notification($senderId, $row['id'], $message);
            $notification->sendNotification();
        }
    }

    header("Location: sendnotifications.php?success=1");
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: sendnotifications.php?error=1");
    exit();
}
?>
<?php
require_once 'Classes/DBconnection.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['admin']);

    $listingId = $_GET['id'];
    $from      = isset($_GET['from']) ? $_GET['from'] : 'alllistings';
    $conn      = DBconnection::getInstance()->getConnection();

    $sql  = "UPDATE listings SET status = 'approved' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $listingId);

    if ($stmt->execute()) {
        
        $notifSql  = "SELECT user_id, title FROM listings WHERE id = ?";
        $notifStmt = $conn->prepare($notifSql);
        $notifStmt->bind_param("i", $listingId);
        $notifStmt->execute();
        $listing = $notifStmt->get_result()->fetch_assoc();

        require_once 'Classes/Notification.php';
        $notification = new Notification(
            $_SESSION['user_id'],
            $listing['user_id'],
            "Your listing \"" . $listing['title'] . "\" has been restored and is now live on CeylonTourMate.lk!"
        );
        $notification->sendNotification();

        if ($from == 'alllistings') {
            header("Location: viewalllistings.php?success=1");
        } else {
            header("Location: viewlistingdetailadmin.php?id=" . $listingId . "&success=1&from=" . $from);
        }
    } else {
        header("Location: viewalllistings.php?error=1");
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: viewalllistings.php?error=1");
    exit();
}
?>
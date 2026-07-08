<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Listing.php';
require_once 'Classes/Notification.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['admin']);

    $listingId = $_GET['id'];
    $action    = $_GET['action'];
    $adminId   = $_SESSION['user_id'];

    $listing = new Listing(null, null, null, null, null, null, null);
    $result  = $listing->approveRejectListing($listingId, $action);

    if ($result == "success") {
        // fetch listing owner id and title
        $conn    = DBconnection::getInstance()->getConnection();
        $sql     = "SELECT user_id, title FROM listings WHERE id = ?";
        $stmt    = $conn->prepare($sql);
        $stmt->bind_param("i", $listingId);
        $stmt->execute();
        $row     = $stmt->get_result()->fetch_assoc();

        // send notification to partner
        if ($action == "approve") {
            $message = "Your listing \"" . $row['title'] . "\" has been approved and is now live on CeylonTourMate.lk!";
        } else {
            $message = "Your listing \"" . $row['title'] . "\" has been rejected. Please contact admin for more information.";
        }

        $notification = new Notification($adminId, $row['user_id'], $message);
        $notification->sendNotification();

        header("Location: viewlistingdetailadmin.php?id=" . $listingId . "&success=1");
    } else {
        header("Location: viewlistingdetailadmin.php?id=" . $listingId . "&error=1");
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: viewlistingdetailadmin.php?id=" . $listingId . "&error=1");
    exit();
}
?>
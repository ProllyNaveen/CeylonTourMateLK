<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/User.php';
require_once 'Classes/Notification.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['admin']);

    $userId  = $_GET['id'];
    $action  = $_GET['action'];
    $adminId = $_SESSION['user_id'];

    $user   = new User(null, null, null, null, null, null, null, null, null, null, null, null);
    $result = $user->approveRejectRegistration($userId, $action);

    if ($result == "success") {

        if ($action == "approve") {
            // fetch partner details to personalize the message
            $conn    = DBconnection::getInstance()->getConnection();
            $sql     = "SELECT firstname, role FROM users WHERE id = ?";
            $stmt    = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
            $stmt->execute();
            $partner = $stmt->get_result()->fetch_assoc();

            $roleName = ucfirst(str_replace('_', ' ', $partner['role']));

            $message = "Welcome to CeylonTourMate.lk, " . $partner['firstname'] . "! 🎉 Your " . $roleName . " account has been approved. You can now log in and start adding your listings!";

            $notification = new Notification($adminId, $userId, $message);
            $notification->sendNotification();
        }

        header("Location: viewpendingrequests.php?success=1");
    } else {
        header("Location: viewpendingrequests.php?error=1");
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: viewpendingrequests.php?error=1");
    exit();
}
?>
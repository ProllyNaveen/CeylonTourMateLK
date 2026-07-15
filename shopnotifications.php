<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['shop_owner']);

require_once 'Classes/Notification.php';

$userId = $_SESSION['user_id'];
$filter = isset($_GET['filter']) ? $_GET['filter'] : null;


$conn    = (require_once 'Classes/DBconnection.php') ? DBconnection::getInstance()->getConnection() : null;
$markSql = "UPDATE notifications SET is_read = 1 WHERE receiver_id = ?";
$markStmt = $conn->prepare($markSql);
$markStmt->bind_param("i", $userId);
$markStmt->execute();

$notifications = Notification::getNotifications($userId, $filter);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Notifications - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="shopownerdashboard.php"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <span class="partner-badge">Shop Owner</span>
            </td>
            <td class="nav-buttons-cell">
                <span class="admin-welcome">Welcome, <?php echo htmlspecialchars($_SESSION['firstname']); ?></span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

    <a href="shopownerdashboard.php" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">Notifications</h2>
    <p class="admin-page-subtitle">View notices and updates from the platform</p>

    <!-- FILTER -->
    <div class="filter-bar">
        <a href="shopnotifications.php" class="filter-btn <?php echo !isset($_GET['filter']) ? 'active' : ''; ?>">All</a>
        <a href="shopnotifications.php?filter=unread" class="filter-btn <?php echo (isset($_GET['filter']) && $_GET['filter']=='unread') ? 'active' : ''; ?>">Unread</a>
        <a href="shopnotifications.php?filter=read" class="filter-btn <?php echo (isset($_GET['filter']) && $_GET['filter']=='read') ? 'active' : ''; ?>">Read</a>
    </div>

    <!-- NOTIFICATIONS LIST -->
    <div class="notifications-list">

        <?php if(empty($notifications)) { ?>
    <div class="notification-item">
        <div class="notif-icon">&#128276;</div>
        <div class="notif-content">
            <p class="notif-message">No notifications yet.</p>
        </div>
    </div>
<?php } else { ?>
    <?php foreach($notifications as $notif) { ?>
    <div class="notification-item <?php echo $notif['is_read'] == 0 ? 'notif-unread' : ''; ?>">
        <div class="notif-icon">&#128276;</div>
        <div class="notif-content">
            <p class="notif-message"><?php echo $notif['message']; ?></p>
            <span class="notif-meta">
                From: <?php echo $notif['firstname'] . ' ' . $notif['lastname']; ?>
                &nbsp;|&nbsp;
                <?php echo date('d M Y h:i A', strtotime($notif['created_at'])); ?>
            </span>
        </div>
        <?php if($notif['is_read'] == 0) { ?>
            <span class="unread-badge">New</span>
        <?php } ?>
    </div>
    <?php } ?>
<?php } ?>

    </div>

</div>

</body>
</html>
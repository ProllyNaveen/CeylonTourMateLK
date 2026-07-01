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
                <a href="hotelownerdashboard.html"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <span class="partner-badge hotel-badge">Hotel Owner</span>
            </td>
            <td class="nav-buttons-cell">
                <span class="admin-welcome">Welcome, Owner</span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

    <a href="hotelownerdashboard.html" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">Notifications</h2>
    <p class="admin-page-subtitle">View notices and updates from the platform</p>

    <!-- FILTER -->
    <div class="filter-bar">
        <a href="hotelownernotifications.php" class="filter-btn <?php echo !isset($_GET['filter']) ? 'active' : ''; ?>">All</a>
        <a href="hotelownernotifications.php?filter=unread" class="filter-btn <?php echo (isset($_GET['filter']) && $_GET['filter']=='unread') ? 'active' : ''; ?>">Unread</a>
        <a href="hotelownernotifications.php?filter=read" class="filter-btn <?php echo (isset($_GET['filter']) && $_GET['filter']=='read') ? 'active' : ''; ?>">Read</a>
    </div>

    <!-- NOTIFICATIONS LIST -->
    <div class="notifications-list">

        <!-- PHP:
            if(isset($_GET['filter']) && $_GET['filter'] == 'unread') {
                $sql = "SELECT n.*, u.firstname, u.lastname, u.profile_pic
                        FROM notifications n
                        JOIN users u ON n.sender_id = u.id
                        WHERE n.receiver_id = ? AND n.is_read = 0
                        ORDER BY n.id DESC";
            } elseif(isset($_GET['filter']) && $_GET['filter'] == 'read') {
                $sql = "SELECT n.*, u.firstname, u.lastname, u.profile_pic
                        FROM notifications n
                        JOIN users u ON n.sender_id = u.id
                        WHERE n.receiver_id = ? AND n.is_read = 1
                        ORDER BY n.id DESC";
            } else {
                $sql = "SELECT n.*, u.firstname, u.lastname, u.profile_pic
                        FROM notifications n
                        JOIN users u ON n.sender_id = u.id
                        WHERE n.receiver_id = ?
                        ORDER BY n.id DESC";
            }
            // Mark all as read when page loads:
            // UPDATE notifications SET is_read = 1 WHERE receiver_id = ?
            loop through results and echo each notification
        -->

        <!-- Example notification item: -->
        <!--
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
        -->

        <div class="notification-item">
            <div class="notif-icon">&#128276;</div>
            <div class="notif-content">
                <p class="notif-message">No notifications yet.</p>
            </div>
        </div>

    </div>

</div>

</body>
</html>
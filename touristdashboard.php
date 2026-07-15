<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['tourist']);

require_once 'Classes/DBconnection.php';

$conn   = DBconnection::getInstance()->getConnection();
$userId = $_SESSION['user_id'];


$sql1  = "SELECT COUNT(*) as total FROM bookings WHERE tourist_id = ?";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $userId);
$stmt1->execute();
$totalBookings = $stmt1->get_result()->fetch_assoc()['total'];


$sql2  = "SELECT COUNT(*) as total FROM bookings WHERE tourist_id = ? AND status = 'pending'";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $userId);
$stmt2->execute();
$pendingBookings = $stmt2->get_result()->fetch_assoc()['total'];


$sql3  = "SELECT COUNT(*) as total FROM bookings WHERE tourist_id = ? AND status = 'completed'";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("i", $userId);
$stmt3->execute();
$completedBookings = $stmt3->get_result()->fetch_assoc()['total'];


$sql4  = "SELECT COUNT(*) as total FROM reviews WHERE tourist_id = ?";
$stmt4 = $conn->prepare($sql4);
$stmt4->bind_param("i", $userId);
$stmt4->execute();
$reviewsWritten = $stmt4->get_result()->fetch_assoc()['total'];


$sql5  = "SELECT COUNT(*) as total FROM messages WHERE receiver_id = ? AND is_read = 0";
$stmt5 = $conn->prepare($sql5);
$stmt5->bind_param("i", $userId);
$stmt5->execute();
$unreadMessages = $stmt5->get_result()->fetch_assoc()['total'];


$sql6  = "SELECT COUNT(*) as total FROM notifications WHERE receiver_id = ? AND is_read = 0";
$stmt6 = $conn->prepare($sql6);
$stmt6->bind_param("i", $userId);
$stmt6->execute();
$unreadNotifications = $stmt6->get_result()->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Dashboard - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="index.php"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <a href="explore.php" class="nav-link">Explore</a>
                <a href="mybookings.php" class="nav-link">My Bookings</a>
                <a href="touristmessages.php" class="nav-link">Messages</a>
                <a href="touristnotifications.php" class="nav-link">Notifications</a>
            </td>
            <td class="nav-buttons-cell">
                <!-- PHP: echo tourist's firstname from session -->
                <span class="admin-welcome">Welcome, <?php echo htmlspecialchars($_SESSION['firstname']); ?></span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- DASHBOARD CONTENT -->
<div class="admin-section">

    <h2 class="admin-page-title">My Dashboard</h2>
    <p class="admin-page-subtitle">Welcome back — here's your activity on CeylonTourMate.lk</p>

    <!-- STAT CARDS -->
    <div class="stat-grid">

        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">&#128197;</div>
            <div class="stat-info">
                <span class="stat-number"><?php echo $totalBookings; ?></span>
                <span class="stat-label">Total Bookings</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-amber">&#128336;</div>
            <div class="stat-info">
                <span class="stat-number"><?php echo $pendingBookings; ?></span>
                <span class="stat-label">Pending Bookings</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-green">&#9989;</div>
            <div class="stat-info">
                <span class="stat-number"><?php echo $completedBookings; ?></span>
                <span class="stat-label">Completed Bookings</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-teal">&#11088;</div>
            <div class="stat-info">
                <span class="stat-number"><?php echo $reviewsWritten; ?></span>
                <span class="stat-label">Reviews Written</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-purple">&#128172;</div>
            <div class="stat-info">
                <span class="stat-number"><?php echo $unreadMessages; ?></span>
                <span class="stat-label">Unread Messages</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-coral">&#128276;</div>
            <div class="stat-info">
                <span class="stat-number"><?php echo $unreadNotifications; ?></span>
                <span class="stat-label">Unread Notifications</span>
            </div>
        </div>

    </div>

    <!-- QUICK ACTIONS -->
    <h2 class="admin-page-title admin-section-spacer">Quick Actions</h2>

    <div class="action-grid">

        <a href="explore.php" class="action-card">
            <div class="action-icon">&#127758;</div>
            <h3>Explore</h3>
            <p>Browse and search for tour guides, hotels, shops and experiences</p>
        </a>

        <a href="mybookings.php" class="action-card">
            <div class="action-icon">&#128197;</div>
            <h3>My Bookings</h3>
            <p>View and manage your bookings</p>
        </a>

        <a href="touristmessages.php" class="action-card">
            <div class="action-icon">&#128172;</div>
            <h3>Messages</h3>
            <p>View and reply to messages from partners</p>
        </a>

        <a href="touristnotifications.php" class="action-card">
            <div class="action-icon">&#128276;</div>
            <h3>Notifications</h3>
            <p>View notices and updates from the platform</p>
        </a>

        <a href="touristeditprofile.php" class="action-card">
            <div class="action-icon">&#9881;&#65039;</div>
            <h3>My Profile</h3>
            <p>Update your account details</p>
        </a>

    </div>

</div>

</body>
</html>
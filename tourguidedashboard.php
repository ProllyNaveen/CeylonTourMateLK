<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['tour_guide']);

require_once 'Classes/DBconnection.php';

$conn   = DBconnection::getInstance()->getConnection();
$userId = $_SESSION['user_id'];

// active listings
$sql1  = "SELECT COUNT(*) as total FROM listings WHERE user_id = ? AND status = 'approved'";
$stmt1 = $conn->prepare($sql1);
$stmt1->bind_param("i", $userId);
$stmt1->execute();
$activeListings = $stmt1->get_result()->fetch_assoc()['total'];

// pending listings
$sql2  = "SELECT COUNT(*) as total FROM listings WHERE user_id = ? AND status = 'pending'";
$stmt2 = $conn->prepare($sql2);
$stmt2->bind_param("i", $userId);
$stmt2->execute();
$pendingListings = $stmt2->get_result()->fetch_assoc()['total'];

// pending bookings
$sql3  = "SELECT COUNT(*) as total FROM bookings b
           JOIN listings l ON b.listing_id = l.id
           WHERE l.user_id = ? AND b.status = 'pending'";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("i", $userId);
$stmt3->execute();
$pendingBookings = $stmt3->get_result()->fetch_assoc()['total'];

// total earnings
$sql4  = "SELECT COALESCE(SUM(b.total_price - b.commission_amount), 0) as total
           FROM bookings b
           JOIN listings l ON b.listing_id = l.id
           WHERE l.user_id = ? AND b.status = 'completed'";
$stmt4 = $conn->prepare($sql4);
$stmt4->bind_param("i", $userId);
$stmt4->execute();
$totalEarnings = $stmt4->get_result()->fetch_assoc()['total'];

// unread messages
$sql5  = "SELECT COUNT(*) as total FROM messages WHERE receiver_id = ? AND is_read = 0";
$stmt5 = $conn->prepare($sql5);
$stmt5->bind_param("i", $userId);
$stmt5->execute();
$unreadMessages = $stmt5->get_result()->fetch_assoc()['total'];

// unread notifications
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
    <title>Tour Guide Dashboard - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="tourguidedashboard.php"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <span class="partner-badge tourguide-badge">Tour Guide</span>
            </td>
            <td class="nav-buttons-cell">
                <!-- PHP: echo tour guide's firstname from session -->
                <span class="admin-welcome">Welcome, Guide</span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- DASHBOARD CONTENT -->
<div class="admin-section">

    <h2 class="admin-page-title">My Dashboard</h2>
    <p class="admin-page-subtitle">Manage your tour guide listings and bookings</p>

    <!-- STAT CARDS -->
    <div class="stat-grid">

        <div class="stat-card">
            <div class="stat-icon stat-icon-teal">&#127759;</div>
            <div class="stat-info">
                <span class="stat-number"><?php echo $activeListings; ?></span>
                <span class="stat-label">Active Listings</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-amber">&#128203;</div>
            <div class="stat-info">
                <span class="stat-number"><?php echo $pendingListings; ?></span>
                <span class="stat-label">Pending Listings</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">&#128197;</div>
            <div class="stat-info">
                <span class="stat-number"><?php echo $pendingBookings; ?></span>
                <span class="stat-label">Pending Bookings</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-green">&#128176;</div>
            <div class="stat-info">
                <span class="stat-number">Rs. <?php echo number_format($totalEarnings, 2); ?></span>
                <span class="stat-label">Total Earnings</span>
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

        <a href="tourguideaddlisting.php" class="action-card">
            <div class="action-icon">&#10133;</div>
            <h3>Add New Listing</h3>
            <p>Submit a new tour guide listing for admin approval</p>
        </a>

        <a href="tourguidemanagelistings.php" class="action-card">
            <div class="action-icon">&#127759;</div>
            <h3>My Listings</h3>
            <p>View, edit or delete your tour guide listings</p>
        </a>

        <a href="tourguidemybookings.php" class="action-card">
            <div class="action-icon">&#128197;</div>
            <h3>My Bookings</h3>
            <p>View and manage bookings made for your tours</p>
        </a>

        <a href="tourguidemessages.php" class="action-card">
            <div class="action-icon">&#128172;</div>
            <h3>Messages</h3>
            <p>View and reply to messages from tourists</p>
        </a>

        <a href="tourguidenotifications.php" class="action-card">
            <div class="action-icon">&#128276;</div>
            <h3>Notifications</h3>
            <p>View notices and updates from the platform</p>
        </a>

        <a href="tourguideeditprofile.php" class="action-card">
            <div class="action-icon">&#9881;&#65039;</div>
            <h3>My Profile</h3>
            <p>Update your account details</p>
        </a>

    </div>

</div>

</body>
</html>
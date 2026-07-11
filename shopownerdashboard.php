<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['shop_owner']);

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

// fees paid this month
$currentMonth = date('n');
$currentYear  = date('Y');
$sql3  = "SELECT COUNT(*) as total FROM payments p
           JOIN shop_listings sl ON p.shop_listing_id = sl.id
           JOIN listings l ON sl.listing_id = l.id
           WHERE l.user_id = ? AND p.type = 'listing_fee'
           AND p.payment_status = 'completed'
           AND p.fee_month = ? AND p.fee_year = ?";
$stmt3 = $conn->prepare($sql3);
$stmt3->bind_param("iii", $userId, $currentMonth, $currentYear);
$stmt3->execute();
$feesPaid = $stmt3->get_result()->fetch_assoc()['total'];

// unread messages
$sql4  = "SELECT COUNT(*) as total FROM messages WHERE receiver_id = ? AND is_read = 0";
$stmt4 = $conn->prepare($sql4);
$stmt4->bind_param("i", $userId);
$stmt4->execute();
$unreadMessages = $stmt4->get_result()->fetch_assoc()['total'];

// unread notifications
$sql5  = "SELECT COUNT(*) as total FROM notifications WHERE receiver_id = ? AND is_read = 0";
$stmt5 = $conn->prepare($sql5);
$stmt5->bind_param("i", $userId);
$stmt5->execute();
$unreadNotifications = $stmt5->get_result()->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Shop Owner Dashboard - CeylonTourMate.lk</title>
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
                <!-- PHP: echo shop owner's firstname from session -->
                <span class="admin-welcome">Welcome, <?php echo htmlspecialchars($_SESSION['firstname']); ?></span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- DASHBOARD CONTENT -->
<div class="admin-section">

    <h2 class="admin-page-title">My Dashboard</h2>
    <p class="admin-page-subtitle">Manage your shop listings and payments</p>

    <!-- STAT CARDS -->
    <div class="stat-grid">

        <div class="stat-card">
            <div class="stat-icon stat-icon-teal">&#127968;</div>
            <div class="stat-info">
                <!-- PHP: echo COUNT(*) FROM listings WHERE user_id = $_SESSION['user_id'] AND status = 'approved' -->
                <span class="stat-number"><?php echo $activeListings; ?></span>
                <span class="stat-label">Active Listings</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-amber">&#128203;</div>
            <div class="stat-info">
                <!-- PHP: echo COUNT(*) FROM listings WHERE user_id = $_SESSION['user_id'] AND status = 'pending' -->
                <span class="stat-number"><?php echo $pendingListings; ?></span>
                <span class="stat-label">Pending Listings</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-green">&#128176;</div>
            <div class="stat-info">
                <span class="stat-number"><?php echo $feesPaid; ?></span>
                <span class="stat-label">Fees Paid This Month</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-purple">&#128172;</div>
            <div class="stat-info">
                <!-- PHP: echo COUNT(*) FROM messages WHERE receiver_id = $_SESSION['user_id'] AND is_read = 0 -->
                <span class="stat-number"><?php echo $unreadMessages; ?></span>
                <span class="stat-label">Unread Messages</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-coral">&#128276;</div>
            <div class="stat-info">
                <!-- PHP: echo COUNT(*) FROM notifications WHERE receiver_id = $_SESSION['user_id'] AND is_read = 0 -->
                <span class="stat-number"><?php echo $unreadNotifications; ?></span>
                <span class="stat-label">Unread Notifications</span>
            </div>
        </div>

    </div>

    <!-- QUICK ACTIONS -->
    <h2 class="admin-page-title admin-section-spacer">Quick Actions</h2>

    <div class="action-grid">

        <a href="shopaddlisting.php" class="action-card">
            <div class="action-icon">&#10133;</div>
            <h3>Add New Listing</h3>
            <p>Submit a new shop listing for admin approval</p>
        </a>

        <a href="shopmanagelistings.php" class="action-card">
            <div class="action-icon">&#127968;</div>
            <h3>My Listings</h3>
            <p>View, edit or delete your shop listings</p>
        </a>

        <a href="shoppayfee.php" class="action-card">
            <div class="action-icon">&#128181;</div>
            <h3>Pay Listing Fee</h3>
            <p>Pay your monthly listing fee for active shop listings</p>
        </a>

        <a href="shopmessages.php" class="action-card">
            <div class="action-icon">&#128172;</div>
            <h3>Messages</h3>
            <p>View and reply to messages from tourists</p>
        </a>

        <a href="shopnotifications.php" class="action-card">
            <div class="action-icon">&#128276;</div>
            <h3>Notifications</h3>
            <p>View notices and updates from the platform</p>
        </a>

        <a href="shopeditprofile.php" class="action-card">
            <div class="action-icon">&#9881;&#65039;</div>
            <h3>My Profile</h3>
            <p>Update your account details</p>
        </a>

    </div>

</div>

</body>
</html>
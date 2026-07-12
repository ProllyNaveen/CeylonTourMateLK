<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['admin']);

require_once 'Classes/DBconnection.php';

$conn = DBconnection::getInstance()->getConnection();

// total users
$sql1  = "SELECT COUNT(*) as total FROM users WHERE role != 'admin'";
$stmt1 = $conn->prepare($sql1);
$stmt1->execute();
$totalUsers = $stmt1->get_result()->fetch_assoc()['total'];

// active listings
$sql2  = "SELECT COUNT(*) as total FROM listings WHERE status = 'approved' AND is_deleted = 0";
$stmt2 = $conn->prepare($sql2);
$stmt2->execute();
$activeListings = $stmt2->get_result()->fetch_assoc()['total'];

// pending listings
$sql3  = "SELECT COUNT(*) as total FROM listings WHERE status = 'pending' AND is_deleted = 0";
$stmt3 = $conn->prepare($sql3);
$stmt3->execute();
$pendingListings = $stmt3->get_result()->fetch_assoc()['total'];

// total bookings
$sql4  = "SELECT COUNT(*) as total FROM bookings";
$stmt4 = $conn->prepare($sql4);
$stmt4->execute();
$totalBookings = $stmt4->get_result()->fetch_assoc()['total'];

// total commission
$sql5  = "SELECT COALESCE(SUM(commission_amount), 0) as total FROM bookings WHERE status = 'completed'";
$stmt5 = $conn->prepare($sql5);
$stmt5->execute();
$totalCommission = $stmt5->get_result()->fetch_assoc()['total'];

// pending registrations
$sql6  = "SELECT COUNT(*) as total FROM users WHERE is_approved = 0 AND role != 'tourist' AND role != 'admin'";
$stmt6 = $conn->prepare($sql6);
$stmt6->execute();
$pendingReg = $stmt6->get_result()->fetch_assoc()['total'];
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<!-- ADMIN NAVBAR -->
<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="admindashboard.php"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <span class="admin-badge">Admin Panel</span>
            </td>
            <td class="nav-buttons-cell">
                <!-- PHP: echo admin's firstname from session -->
                <span class="admin-welcome">Welcome, <?php echo htmlspecialchars($_SESSION['firstname']); ?></span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- DASHBOARD CONTENT -->
<div class="admin-section">

    <h2 class="admin-page-title">Dashboard Overview</h2>
    <p class="admin-page-subtitle">A quick snapshot of CeylonTourMate.lk</p>

    <!-- STAT CARDS -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">&#128101;</div>
            <div class="stat-info">
                <!-- PHP: echo COUNT(*) FROM users -->
                <span class="stat-number"><?php echo $totalUsers; ?></span>
                <span class="stat-label">Total Users</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-teal">&#127968;</div>
            <div class="stat-info">
                <!-- PHP: echo COUNT(*) FROM listings WHERE status='approved' -->
                <span class="stat-number"><?php echo $activeListings; ?></span>
                <span class="stat-label">Active Listings</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-amber">&#128203;</div>
            <div class="stat-info">
                <!-- PHP: echo COUNT(*) FROM listings WHERE status='pending' -->
                <span class="stat-number"><?php echo $pendingListings; ?></span>
                <span class="stat-label">Pending Listings</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-coral">&#128197;</div>
            <div class="stat-info">
                <!-- PHP: echo COUNT(*) FROM bookings -->
                <span class="stat-number"><?php echo $totalBookings; ?></span>
                <span class="stat-label">Total Bookings</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-green">&#128176;</div>
            <div class="stat-info">
                <!-- PHP: echo SUM(commission_amount) FROM bookings -->
                <span class="stat-number">Rs. <?php echo number_format($totalCommission, 2); ?></span>
                <span class="stat-label">Total Commission Earned</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-purple">&#9888;&#65039;</div>
            <div class="stat-info">
                <!-- PHP: echo COUNT(*) FROM users WHERE is_approved=0 AND role != 'tourist' -->
                <span class="stat-number"><?php echo $pendingReg; ?></span>
                <span class="stat-label">Pending Registrations</span>
            </div>
        </div>
    </div>

    <!-- ADMIN QUICK ACTIONS -->
    <h2 class="admin-page-title admin-section-spacer">Manage Platform</h2>

    <div class="action-grid">

        <a href="viewuseraccounts.php" class="action-card">
            <div class="action-icon">&#128100;</div>
            <h3>User Accounts</h3>
            <p>View, suspend or delete tourist and partner accounts</p>
        </a>

        <a href="viewpendingrequests.php" class="action-card">
            <div class="action-icon">&#128221;</div>
            <h3>Partner Registrations</h3>
            <p>Review and approve or reject new partner sign-ups</p>
        </a>

        <a href="viewlistingrequests.php" class="action-card">
            <div class="action-icon">&#9989;</div>
            <h3>Listing Approvals</h3>
            <p>Approve or reject newly submitted listings</p>
        </a>
		<a href="viewalllistings.php" class="action-card">
			<div class="action-icon">&#127968;</div>
			<h3>All Listings</h3>
			<p>View and manage all approved listings on the platform</p>
		</a>
        <a href="viewallbookings.php" class="action-card">
            <div class="action-icon">&#128197;</div>
            <h3>All Bookings</h3>
            <p>View every booking made across the platform</p>
        </a>

        <a href="manageadminaccounts.php" class="action-card">
            <div class="action-icon">&#128272;</div>
            <h3>Admin Accounts</h3>
            <p>Add or remove other admin accounts</p>
        </a>

        <a href="commissionoverview.php" class="action-card">
            <div class="action-icon">&#128181;</div>
            <h3>Commission &amp; Fees</h3>
            <p>View earnings and change the commission rate</p>
        </a>

        <a href="sendnotifications.php" class="action-card">
            <div class="action-icon">&#128276;</div>
            <h3>Send Notifications</h3>
            <p>Send a notice or warning to platform users</p>
        </a>

        <a href="editprofile.php" class="action-card">
            <div class="action-icon">&#9881;&#65039;</div>
            <h3>My Profile</h3>
            <p>Update your own admin account details</p>
        </a>
		
		<a href="reports.php" class="action-card">
            <div class="action-icon">&#128202;</div>
            <h3>Reports</h3>
            <p>Generate platform reports for bookings, revenue and listings</p>
        </a>

    </div>

</div>

</body>
</html>
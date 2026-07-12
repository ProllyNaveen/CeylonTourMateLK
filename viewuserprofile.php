<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
$from = isset($_GET['from']) ? $_GET['from'] : 'accounts';
require_once 'Classes/session.php';
checkRole(['admin']);

require_once 'Classes/DBconnection.php';

$userId = $_GET['id'];
$conn   = DBconnection::getInstance()->getConnection();

// fetch user details
$sql  = "SELECT * FROM users WHERE id = ? AND role != 'admin'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $userId);
$stmt->execute();
$user = $stmt->get_result()->fetch_assoc();

if (!$user) {
    header("Location: viewuseraccounts.php");
    exit();
}

// fetch user listings if partner
$listings = [];
if ($user['role'] != 'tourist') {
    $listSql  = "SELECT * FROM listings WHERE user_id = ? AND is_deleted = 0 ORDER BY created_at DESC";
    $listStmt = $conn->prepare($listSql);
    $listStmt->bind_param("i", $userId);
    $listStmt->execute();
    $listings = $listStmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// fetch user bookings if tourist
$bookings = [];
if ($user['role'] == 'tourist') {
    $bookSql  = "SELECT b.*, l.title, l.type FROM bookings b
                  JOIN listings l ON b.listing_id = l.id
                  WHERE b.tourist_id = ?
                  ORDER BY b.created_at DESC";
    $bookStmt = $conn->prepare($bookSql);
    $bookStmt->bind_param("i", $userId);
    $bookStmt->execute();
    $bookings = $bookStmt->get_result()->fetch_all(MYSQLI_ASSOC);
}

// fetch license if tour guide
$license = null;
if ($user['role'] == 'tour_guide') {
    $licSql  = "SELECT * FROM tourguide_licenses WHERE user_id = ?";
    $licStmt = $conn->prepare($licSql);
    $licStmt->bind_param("i", $userId);
    $licStmt->execute();
    $license = $licStmt->get_result()->fetch_assoc();
}

// fetch review count if tourist
$reviewCount = 0;
if ($user['role'] == 'tourist') {
    $revSql  = "SELECT COUNT(*) as total FROM reviews WHERE tourist_id = ?";
    $revStmt = $conn->prepare($revSql);
    $revStmt->bind_param("i", $userId);
    $revStmt->execute();
    $reviewCount = $revStmt->get_result()->fetch_assoc()['total'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Profile - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

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
                <span class="admin-welcome">Welcome, <?php echo $_SESSION['firstname']; ?></span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<div class="admin-section">

    <?php
$backLink = $from == 'pending' ? 'viewpendingrequests.php' : 'viewuseraccounts.php';
$backText = $from == 'pending' ? 'Back to Pending Requests' : 'Back to User Accounts';
?>
<a href="<?php echo $backLink; ?>" class="back-link">&larr; <?php echo $backText; ?></a>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Action completed successfully.</p>
    <?php } ?>

    <div class="listing-detail-layout">

        <!-- LEFT: USER DETAILS -->
        <div class="listing-detail-main">

            <!-- BASIC INFO -->
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Account Information</h3>
                <table class="detail-table">
                    <tr><td>Full Name</td><td><?php echo $user['firstname'] . ' ' . $user['lastname']; ?></td></tr>
                    <tr><td>Username</td><td><?php echo $user['username']; ?></td></tr>
                    <tr><td>Email</td><td><?php echo $user['email']; ?></td></tr>
                    <tr><td>Phone</td><td><?php echo $user['phone'] ?? 'N/A'; ?></td></tr>
                    <tr><td>Role</td>
                        <td>
                            <span class="role-badge role-<?php echo $user['role']; ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $user['role'])); ?>
                            </span>
                        </td>
                    </tr>
                    <tr><td>Status</td>
                        <td>
                            <?php if($user['is_suspended'] == 1) { ?>
                                <span class="status-badge status-suspended">Suspended</span>
                            <?php } elseif($user['is_approved'] == 0) { ?>
                                <span class="status-badge status-pending">Pending Approval</span>
                            <?php } else { ?>
                                <span class="status-badge status-confirmed">Active</span>
                            <?php } ?>
                        </td>
                    </tr>
                    <tr><td>Joined</td><td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td></tr>
                </table>
            </div>

            <!-- TOUR GUIDE LICENSE -->
            <?php if($user['role'] == 'tour_guide' && $license) { ?>
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Tour Guide License</h3>
                <table class="detail-table">
                    <tr><td>License Number</td><td><?php echo $license['license_number']; ?></td></tr>
                    <tr>
                        <td>License Image</td>
                        <td>
                            <a href="<?php echo $license['license_image']; ?>" target="_blank" class="tbl-btn tbl-btn-green">
                                View License
                            </a>
                        </td>
                    </tr>
                </table>
            </div>
            <?php } ?>

            <!-- LISTINGS (for partners) -->
            <?php if(!empty($listings)) { ?>
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Listings</h3>
                <table class="admin-table">
                    <tr>
                        <th>Title</th>
                        <th>Type</th>
                        <th>City</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                    <?php foreach($listings as $listing) { ?>
                    <tr>
                        <td><?php echo $listing['title']; ?></td>
                        <td>
                            <span class="listing-type<?php echo $listing['type']; ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $listing['type'])); ?>
                            </span>
                        </td>
                        <td><?php echo $listing['city']; ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $listing['status']; ?>">
                                <?php echo ucfirst($listing['status']); ?>
                            </span>
                        </td>
                        <td>
                            <a href="viewlistingdetailadmin.php?id=<?php echo $listing['id']; ?>&from=userprofile"
                               class="tbl-btn tbl-btn-amber">View</a>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <?php } ?>

            <!-- BOOKINGS (for tourists) -->
            <?php if(!empty($bookings)) { ?>
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Booking History</h3>
                <table class="admin-table">
                    <tr>
                        <th>Listing</th>
                        <th>Type</th>
                        <th>Start</th>
                        <th>Total (Rs.)</th>
                        <th>Status</th>
                    </tr>
                    <?php foreach($bookings as $booking) { ?>
                    <tr>
                        <td><?php echo $booking['title']; ?></td>
                        <td>
                            <span class="listing-type-badge listing-type-<?php echo $booking['type']; ?>">
                                <?php echo ucfirst(str_replace('_', ' ', $booking['type'])); ?>
                            </span>
                        </td>
                        <td><?php echo date('d M Y', strtotime($booking['start_datetime'])); ?></td>
                        <td>Rs. <?php echo number_format($booking['total_price'], 2); ?></td>
                        <td>
                            <span class="status-badge status-<?php echo $booking['status']; ?>">
                                <?php echo ucfirst($booking['status']); ?>
                            </span>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <?php } ?>

        </div>

        <!-- RIGHT: SIDEBAR -->
        <div class="listing-detail-sidebar">

            <!-- PROFILE PIC -->
            <div class="sidebar-card">
                <h3 class="admin-subsection-title">Profile Picture</h3>
                <img src="<?php echo $user['profile_pic'] ?? 'uploads/profiles/default.png'; ?>"
                     alt="Profile Picture"
                     style="width:100%; border-radius:10px; object-fit:cover;">
            </div>

            <!-- STATS -->
            <div class="sidebar-card" style="margin-top:20px;">
                <h3 class="admin-subsection-title">Statistics</h3>
                <table class="detail-table">
                    <?php if($user['role'] == 'tourist') { ?>
                        <tr><td>Total Bookings</td><td><?php echo count($bookings); ?></td></tr>
                        <tr><td>Reviews Written</td><td><?php echo $reviewCount; ?></td></tr>
                    <?php } else { ?>
                        <tr><td>Total Listings</td><td><?php echo count($listings); ?></td></tr>
                    <?php } ?>
                </table>
            </div>

            <!-- ADMIN ACTIONS -->
            <div class="sidebar-card" style="margin-top:20px;">
    <h3 class="admin-subsection-title">Actions</h3>
    <div style="display:flex; flex-direction:column; gap:10px;">

        <?php if($from == 'pending') { ?>
            <a href="approveregbackend.php?id=<?php echo $user['id']; ?>&action=approve"
               class="btn btn-primary" style="text-align:center;">Approve</a>
            <a href="approveregbackend.php?id=<?php echo $user['id']; ?>&action=reject"
               class="btn btn-login"
               onclick="return confirm('Are you sure you want to reject this registration?')"
               style="text-align:center; background-color:#faece7; color:#993c1d; border-color:#993c1d;">Reject</a>

        <?php } else { ?>
            <?php if($user['is_suspended'] == 1) { ?>
                <a href="suspenduserbackend.php?id=<?php echo $user['id']; ?>&action=unsuspend&from=profile"
                   class="btn btn-primary" style="text-align:center;">Unsuspend User</a>
            <?php } else { ?>
                <a href="suspenduserbackend.php?id=<?php echo $user['id']; ?>&action=suspend&from=profile"
                   class="btn btn-login"
                   style="text-align:center; background-color:#faeeda; color:#7a5200; border-color:#7a5200;">
                   Suspend User</a>
            <?php } ?>
            <a href="deleteuserbackend.php?id=<?php echo $user['id']; ?>"
               class="btn btn-login"
               onclick="return confirm('Are you sure you want to delete this user?')"
               style="text-align:center; background-color:#faece7; color:#993c1d; border-color:#993c1d;">
               Delete User</a>
        <?php } ?>

    </div>
</div>

        </div>

    </div>

</div>

</body>
</html>
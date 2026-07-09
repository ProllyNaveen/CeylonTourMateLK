<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['admin']);

require_once 'Classes/DBconnection.php';

$conn   = DBconnection::getInstance()->getConnection();
$type   = isset($_GET['type']) ? $_GET['type'] : null;
$status = isset($_GET['status']) ? $_GET['status'] : null;

if ($type && $status) {
    $sql  = "SELECT l.*, u.firstname, u.lastname
             FROM listings l
             JOIN users u ON l.user_id = u.id
             WHERE l.type = ? AND l.status = ?
             ORDER BY l.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $type, $status);
} elseif ($type) {
    $sql  = "SELECT l.*, u.firstname, u.lastname
             FROM listings l
             JOIN users u ON l.user_id = u.id
             WHERE l.type = ?
             ORDER BY l.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $type);
} elseif ($status) {
    $sql  = "SELECT l.*, u.firstname, u.lastname
             FROM listings l
             JOIN users u ON l.user_id = u.id
             WHERE l.status = ?
             ORDER BY l.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $status);
} else {
    $sql  = "SELECT l.*, u.firstname, u.lastname
             FROM listings l
             JOIN users u ON l.user_id = u.id
             ORDER BY l.created_at DESC";
    $stmt = $conn->prepare($sql);
}
$stmt->execute();
$listings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Listings - CeylonTourMate.lk</title>
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

    <a href="admindashboard.php" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">All Listings</h2>
    <p class="admin-page-subtitle">View and manage all listings on the platform</p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Listing hidden successfully.</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <!-- FILTER BY TYPE -->
    <div class="filter-bar">
        <a href="viewalllistings.php" class="filter-btn <?php echo !$type && !$status ? 'active' : ''; ?>">All</a>
        <a href="viewalllistings.php?type=tour_guide" class="filter-btn <?php echo $type == 'tour_guide' ? 'active' : ''; ?>">Tour Guide</a>
        <a href="viewalllistings.php?type=hotel" class="filter-btn <?php echo $type == 'hotel' ? 'active' : ''; ?>">Hotel</a>
        <a href="viewalllistings.php?type=shop" class="filter-btn <?php echo $type == 'shop' ? 'active' : ''; ?>">Shop</a>
        <a href="viewalllistings.php?type=experience" class="filter-btn <?php echo $type == 'experience' ? 'active' : ''; ?>">Experience</a>
    </div>

    <!-- FILTER BY STATUS -->
    <div class="filter-bar">
        <a href="viewalllistings.php" class="filter-btn <?php echo !$status && !$type ? 'active' : ''; ?>">All Status</a>
        <a href="viewalllistings.php?status=approved" class="filter-btn <?php echo $status == 'approved' ? 'active' : ''; ?>">Approved</a>
        <a href="viewalllistings.php?status=pending" class="filter-btn <?php echo $status == 'pending' ? 'active' : ''; ?>">Pending</a>
        <a href="viewalllistings.php?status=rejected" class="filter-btn <?php echo $status == 'rejected' ? 'active' : ''; ?>">Rejected</a>
    </div>

    <!-- LISTINGS TABLE -->
    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Owner</th>
            <th>Type</th>
            <th>City</th>
            <th>Price (Rs.)</th>
            <th>Status</th>
            <th>Created</th>
            <th>Actions</th>
        </tr>

        <?php if(empty($listings)) { ?>
            <tr>
                <td colspan="9" class="table-empty">No listings found</td>
            </tr>
        <?php } else { ?>
            <?php foreach($listings as $listing) { ?>
            <tr>
                <td><?php echo $listing['id']; ?></td>
                <td><?php echo $listing['title']; ?></td>
                <td><?php echo $listing['firstname'] . ' ' . $listing['lastname']; ?></td>
                <td>
                    <span class="listing-type-badge listing-type-<?php echo $listing['type']; ?>">
                        <?php echo ucfirst(str_replace('_', ' ', $listing['type'])); ?>
                    </span>
                </td>
                <td><?php echo $listing['city']; ?></td>
                <td>Rs. <?php echo number_format($listing['price'], 2); ?></td>
                <td>
                    <span class="status-badge status-<?php echo $listing['status']; ?>">
                        <?php echo ucfirst($listing['status']); ?>
                    </span>
                </td>
                <td><?php echo date('d M Y', strtotime($listing['created_at'])); ?></td>
                <td>
    <div class="action-btns">
        <a href="viewlistingdetailadmin.php?id=<?php echo $listing['id']; ?>&from=alllistings"
           class="tbl-btn tbl-btn-amber">View</a>
        <?php if($listing['status'] == 'approved') { ?>
            <a href="adminHidelistingbackend.php?id=<?php echo $listing['id']; ?>&from=alllistings"
               class="tbl-btn tbl-btn-red"
               onclick="return confirm('Are you sure you want to hide this listing?')">Hide</a>
        <?php } else { ?>
            <a href="adminUnhidelistingbackend.php?id=<?php echo $listing['id']; ?>&from=alllistings"
               class="tbl-btn tbl-btn-green"
               onclick="return confirm('Are you sure you want to unhide this listing?')">Unhide</a>
        <?php } ?>
    </div>
</td>
            </tr>
            <?php } ?>
        <?php } ?>

    </table>

</div>

</body>
</html>
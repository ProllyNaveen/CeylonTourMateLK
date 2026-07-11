<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['tour_guide']);

require_once 'Classes/DBconnection.php';

$conn   = DBconnection::getInstance()->getConnection();
$userId = $_SESSION['user_id'];
$status = isset($_GET['status']) ? $_GET['status'] : null;

if ($status) {
    $sql  = "SELECT l.*, tl.specialization, tl.languages, tl.experience_years, tl.availability
             FROM listings l
             JOIN tourguide_listings tl ON l.id = tl.listing_id
             WHERE l.user_id = ? AND l.status = ?
             ORDER BY l.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("is", $userId, $status);
} else {
    $sql  = "SELECT l.*, tl.specialization, tl.languages, tl.experience_years, tl.availability
             FROM listings l
             JOIN tourguide_listings tl ON l.id = tl.listing_id
             WHERE l.user_id = ?
             ORDER BY l.created_at DESC";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $userId);
}
$stmt->execute();
$listings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Listings - CeylonTourMate.lk</title>
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
                <span class="admin-welcome">Welcome, <?php echo htmlspecialchars($_SESSION['firstname']); ?></span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

    <a href="tourguidedashboard.php" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">My Listings</h2>
    <p class="admin-page-subtitle">View, edit or delete your tour guide listings</p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Action completed successfully.</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <!-- FILTER BY STATUS -->
    <div class="filter-bar">
        <a href="tourguidemanagelistings.php" class="filter-btn <?php echo !isset($_GET['status']) ? 'active' : ''; ?>">All</a>
        <a href="tourguidemanagelistings.php?status=approved" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='approved') ? 'active' : ''; ?>">Approved</a>
        <a href="tourguidemanagelistings.php?status=pending" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='pending') ? 'active' : ''; ?>">Pending</a>
        <a href="tourguidemanagelistings.php?status=rejected" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='rejected') ? 'active' : ''; ?>">Rejected</a>
    </div>

    <!-- LISTINGS TABLE -->
    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Specialization</th>
            <th>Languages</th>
            <th>Experience</th>
            <th>Price (Rs.)</th>
            <th>City</th>
            <th>Availability</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php if(empty($listings)) { ?>
    <tr>
        <td colspan="10" class="table-empty">No listings found</td>
    </tr>
<?php } else { ?>
    <?php foreach($listings as $listing) { ?>
    <tr>
        <td><?php echo $listing['id']; ?></td>
        <td><?php echo $listing['title']; ?></td>
        <td><?php echo $listing['specialization']; ?></td>
        <td><?php echo $listing['languages']; ?></td>
        <td><?php echo $listing['experience_years']; ?> yrs</td>
        <td>Rs. <?php echo number_format($listing['price'], 2); ?></td>
        <td><?php echo $listing['city']; ?></td>
        <td><?php echo $listing['availability']; ?></td>
        <td>
            <span class="status-badge status-<?php echo $listing['status']; ?>">
                <?php echo ucfirst($listing['status']); ?>
            </span>
        </td>
        <td>
            <div class="action-btns">
    <a href="tourguideviewlisting.php?id=<?php echo $listing['id']; ?>"
       class="tbl-btn tbl-btn-green">View</a>
    <a href="tourguideeditlisting.php?id=<?php echo $listing['id']; ?>"
       class="tbl-btn tbl-btn-amber">Edit</a>
    <a href="tourguideDeletelistingbackend.php?id=<?php echo $listing['id']; ?>"
       class="tbl-btn tbl-btn-red"
       onclick="return confirm('Are you sure you want to delete this listing?')">Delete</a>
</div>
        </td>
    </tr>
    <?php } ?>
<?php } ?>

    </table>

</div>

</body>
</html>
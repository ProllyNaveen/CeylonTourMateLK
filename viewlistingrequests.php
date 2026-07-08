<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['admin']);

require_once 'Classes/Listing.php';

$type     = isset($_GET['type']) ? $_GET['type'] : null;
$listings = Listing::getPendingListings($type);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Listing Approvals - CeylonTourMate.lk</title>
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
                <span class="admin-welcome">Welcome, Admin</span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

    <a href="admindashboard.php" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">Listing Approvals</h2>
    <p class="admin-page-subtitle">Approve or reject newly submitted listings from partners</p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Action completed successfully.</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <!-- FILTER BY TYPE -->
    <div class="filter-bar">
        <a href="viewlistingrequests.php" class="filter-btn <?php echo !isset($_GET['type']) ? 'active' : ''; ?>">All</a>
        <a href="viewlistingrequests.php?type=tour_guide" class="filter-btn <?php echo (isset($_GET['type']) && $_GET['type']=='tour_guide') ? 'active' : ''; ?>">Tour Guide</a>
        <a href="viewlistingrequests.php?type=hotel" class="filter-btn <?php echo (isset($_GET['type']) && $_GET['type']=='hotel') ? 'active' : ''; ?>">Hotel</a>
        <a href="viewlistingrequests.php?type=shop" class="filter-btn <?php echo (isset($_GET['type']) && $_GET['type']=='shop') ? 'active' : ''; ?>">Shop</a>
        <a href="viewlistingrequests.php?type=experience" class="filter-btn <?php echo (isset($_GET['type']) && $_GET['type']=='experience') ? 'active' : ''; ?>">Experience</a>
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
            <th>Submitted</th>
            <th>Action</th>
        </tr>

        <?php if(empty($listings)) { ?>
    <tr>
        <td colspan="8" class="table-empty">No pending listings</td>
    </tr>
		<?php } else { ?>
			<?php foreach($listings as $listing) { ?>
			<tr>
				<td><?php echo $listing['id']; ?></td>
				<td><?php echo $listing['title']; ?></td>
				<td><?php echo $listing['firstname'] . ' ' . $listing['lastname']; ?></td>
				<td>
					<span class="role-badge role-<?php echo $listing['type']; ?>">
						<?php echo ucfirst(str_replace('_', ' ', $listing['type'])); ?>
					</span>
				</td>
				<td><?php echo $listing['city']; ?></td>
				<td><?php echo number_format($listing['price'], 2); ?></td>
				<td><?php echo date('d M Y', strtotime($listing['created_at'])); ?></td>
				<td>
					<div class="action-btns">
						<a href="viewlistingdetailadmin.php?id=<?php echo $listing['id']; ?>"
       class="tbl-btn tbl-btn-amber">View Listing</a>
					</div>
				</td>
			</tr>
			<?php } ?>
		<?php } ?>

    </table>

</div>

</body>
</html>
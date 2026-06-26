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
                <a href="shopownerdashboard.html"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <span class="partner-badge">Shop Owner</span>
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

    <a href="shopownerdashboard.html" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">My Listings</h2>
    <p class="admin-page-subtitle">View, edit or delete your shop listings</p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Action completed successfully.</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <!-- FILTER BY STATUS -->
    <div class="filter-bar">
        <a href="shopmanagelistings.php" class="filter-btn <?php echo !isset($_GET['status']) ? 'active' : ''; ?>">All</a>
        <a href="shopmanagelistings.php?status=approved" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='approved') ? 'active' : ''; ?>">Approved</a>
        <a href="shopmanagelistings.php?status=pending" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='pending') ? 'active' : ''; ?>">Pending</a>
        <a href="shopmanagelistings.php?status=rejected" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='rejected') ? 'active' : ''; ?>">Rejected</a>
    </div>

    <!-- LISTINGS TABLE -->
    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Title</th>
            <th>Shop Type</th>
            <th>City</th>
            <th>Price (Rs.)</th>
            <th>Open Time</th>
            <th>Close Time</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <!-- PHP:
            if(isset($_GET['status'])) {
                $status = $_GET['status'];
                $sql = "SELECT l.*, sl.shop_type, sl.open_time, sl.close_time
                        FROM listings l
                        JOIN shop_listings sl ON l.id = sl.listing_id
                        WHERE l.user_id = ? AND l.status = ?
                        ORDER BY l.created_at DESC";
            } else {
                $sql = "SELECT l.*, sl.shop_type, sl.open_time, sl.close_time
                        FROM listings l
                        JOIN shop_listings sl ON l.id = sl.listing_id
                        WHERE l.user_id = ?
                        ORDER BY l.created_at DESC";
            }
            loop through results and echo each row
        -->

        <!-- Example populated row: -->
        <!--
        <tr>
            <td><?php echo $listing['id']; ?></td>
            <td><?php echo $listing['title']; ?></td>
            <td><?php echo $listing['shop_type']; ?></td>
            <td><?php echo $listing['city']; ?></td>
            <td><?php echo number_format($listing['price'], 2); ?></td>
            <td><?php echo date('h:i A', strtotime($listing['open_time'])); ?></td>
            <td><?php echo date('h:i A', strtotime($listing['close_time'])); ?></td>
            <td>
                <span class="status-badge status-<?php echo $listing['status']; ?>">
                    <?php echo ucfirst($listing['status']); ?>
                </span>
            </td>
            <td>
                <div class="action-btns">
                    <a href="shopeditlisting.php?id=<?php echo $listing['id']; ?>" class="tbl-btn tbl-btn-amber">Edit</a>
                    <a href="shopdeletelistingbackend.php?id=<?php echo $listing['id']; ?>"
                       class="tbl-btn tbl-btn-red"
                       onclick="return confirm('Are you sure you want to delete this listing?')">Delete</a>
                </div>
            </td>
        </tr>
        -->

        <tr>
            <td colspan="9" class="table-empty">No listings found</td>
        </tr>

    </table>

</div>

</body>
</html>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>All Bookings - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<!-- ADMIN NAVBAR -->
<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="admindashboard.html"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
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

    <a href="admindashboard.html" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">All Bookings</h2>
    <p class="admin-page-subtitle">View every booking made across the platform</p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Booking cancelled successfully.</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <!-- FILTER BY STATUS -->
    <div class="filter-bar">
        <a href="viewallbookings.php" class="filter-btn <?php echo !isset($_GET['status']) ? 'active' : ''; ?>">All</a>
        <a href="viewallbookings.php?status=pending" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='pending') ? 'active' : ''; ?>">Pending</a>
        <a href="viewallbookings.php?status=confirmed" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='confirmed') ? 'active' : ''; ?>">Confirmed</a>
        <a href="viewallbookings.php?status=completed" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='completed') ? 'active' : ''; ?>">Completed</a>
        <a href="viewallbookings.php?status=cancelled" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='cancelled') ? 'active' : ''; ?>">Cancelled</a>
    </div>

    <!-- BOOKINGS TABLE -->
    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Tourist</th>
            <th>Listing</th>
            <th>Type</th>
            <th>Start</th>
            <th>End</th>
            <th>Guests</th>
            <th>Total (Rs.)</th>
            <th>Commission (Rs.)</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <!-- PHP:
            if(isset($_GET['status'])) {
                $status = $_GET['status'];
                $sql = "SELECT b.*, u.firstname, u.lastname, l.title, l.type
                        FROM bookings b
                        JOIN users u ON b.tourist_id = u.id
                        JOIN listings l ON b.listing_id = l.id
                        WHERE b.status = ?
                        ORDER BY b.created_at DESC";
            } else {
                $sql = "SELECT b.*, u.firstname, u.lastname, l.title, l.type
                        FROM bookings b
                        JOIN users u ON b.tourist_id = u.id
                        JOIN listings l ON b.listing_id = l.id
                        ORDER BY b.created_at DESC";
            }
            loop through results and echo each row
        -->

        <!-- Example populated row: -->
        <!--
        <tr>
            <td><?php echo $booking['id']; ?></td>
            <td><?php echo $booking['firstname'] . ' ' . $booking['lastname']; ?></td>
            <td><?php echo $booking['title']; ?></td>
            <td><span class="role-badge role-<?php echo $booking['type']; ?>"><?php echo ucfirst(str_replace('_', ' ', $booking['type'])); ?></span></td>
            <td><?php echo date('d M Y H:i', strtotime($booking['start_datetime'])); ?></td>
            <td><?php echo date('d M Y H:i', strtotime($booking['end_datetime'])); ?></td>
            <td><?php echo $booking['guest_count']; ?></td>
            <td><?php echo number_format($booking['total_price'], 2); ?></td>
            <td><?php echo number_format($booking['commission_amount'], 2); ?></td>
            <td>
                <span class="status-badge status-<?php echo $booking['status']; ?>">
                    <?php echo ucfirst($booking['status']); ?>
                </span>
            </td>
            <td>
                <?php if($booking['status'] == 'pending' || $booking['status'] == 'confirmed') { ?>
                    <a href="cancelbookingbackend.php?id=<?php echo $booking['id']; ?>" class="tbl-btn tbl-btn-red" onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</a>
                <?php } else { ?>
                    <span style="color:#999; font-size:12px;">—</span>
                <?php } ?>
            </td>
        </tr>
        -->

        <tr>
            <td colspan="11" class="table-empty">No bookings found</td>
        </tr>

    </table>

</div>

</body>
</html>
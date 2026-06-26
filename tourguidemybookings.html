<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Bookings - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="tourguidedashboard.html"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <span class="partner-badge tourguide-badge">Tour Guide</span>
            </td>
            <td class="nav-buttons-cell">
                <span class="admin-welcome">Welcome, Guide</span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

    <a href="tourguidedashboard.html" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">My Bookings</h2>
    <p class="admin-page-subtitle">View and manage bookings made for your tours</p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Action completed successfully.</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <!-- FILTER BY STATUS -->
    <div class="filter-bar">
        <a href="tourguidemybookings.php" class="filter-btn <?php echo !isset($_GET['status']) ? 'active' : ''; ?>">All</a>
        <a href="tourguidemybookings.php?status=pending" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='pending') ? 'active' : ''; ?>">Pending</a>
        <a href="tourguidemybookings.php?status=confirmed" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='confirmed') ? 'active' : ''; ?>">Confirmed</a>
        <a href="tourguidemybookings.php?status=completed" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='completed') ? 'active' : ''; ?>">Completed</a>
        <a href="tourguidemybookings.php?status=cancelled" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='cancelled') ? 'active' : ''; ?>">Cancelled</a>
    </div>

    <!-- BOOKINGS TABLE -->
    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Tourist</th>
            <th>Tour</th>
            <th>Start</th>
            <th>End</th>
            <th>Guests</th>
            <th>Total (Rs.)</th>
            <th>Earnings (Rs.)</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <!-- PHP:
            if(isset($_GET['status'])) {
                $status = $_GET['status'];
                $sql = "SELECT b.*, u.firstname, u.lastname, l.title,
                        (b.total_price - b.commission_amount) as earnings
                        FROM bookings b
                        JOIN users u ON b.tourist_id = u.id
                        JOIN listings l ON b.listing_id = l.id
                        WHERE l.user_id = ? AND b.status = ?
                        AND l.type = 'tour_guide'
                        ORDER BY b.created_at DESC";
            } else {
                $sql = "SELECT b.*, u.firstname, u.lastname, l.title,
                        (b.total_price - b.commission_amount) as earnings
                        FROM bookings b
                        JOIN users u ON b.tourist_id = u.id
                        JOIN listings l ON b.listing_id = l.id
                        WHERE l.user_id = ?
                        AND l.type = 'tour_guide'
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
            <td><?php echo date('d M Y h:i A', strtotime($booking['start_datetime'])); ?></td>
            <td><?php echo date('d M Y h:i A', strtotime($booking['end_datetime'])); ?></td>
            <td><?php echo $booking['guest_count']; ?></td>
            <td><?php echo number_format($booking['total_price'], 2); ?></td>
            <td><?php echo number_format($booking['earnings'], 2); ?></td>
            <td>
                <span class="status-badge status-<?php echo $booking['status']; ?>">
                    <?php echo ucfirst($booking['status']); ?>
                </span>
            </td>
            <td>
                <div class="action-btns">
                    <?php if($booking['status'] == 'pending') { ?>
                        <a href="tourguideconfirmbookingbackend.php?id=<?php echo $booking['id']; ?>"
                           class="tbl-btn tbl-btn-green">Confirm</a>
                        <a href="tourguidecancelbookingbackend.php?id=<?php echo $booking['id']; ?>"
                           class="tbl-btn tbl-btn-red"
                           onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</a>
                    <?php } elseif($booking['status'] == 'confirmed') { ?>
                        <a href="tourguidecompletebookingbackend.php?id=<?php echo $booking['id']; ?>"
                           class="tbl-btn tbl-btn-amber">Mark Complete</a>
                    <?php } else { ?>
                        <span style="color:#999; font-size:12px;">—</span>
                    <?php } ?>
                </div>
            </td>
        </tr>
        -->

        <tr>
            <td colspan="10" class="table-empty">No bookings found</td>
        </tr>

    </table>

</div>

</body>
</html>
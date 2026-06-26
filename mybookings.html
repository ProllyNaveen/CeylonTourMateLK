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
                <a href="index.html"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <a href="explore.html" class="nav-link">Explore</a>
                <a href="mybookings.html" class="nav-link">My Bookings</a>
                <a href="touristmessages.html" class="nav-link">Messages</a>
                <a href="touristnotifications.html" class="nav-link">Notifications</a>
            </td>
            <td class="nav-buttons-cell">
                <span class="admin-welcome">Welcome, Tourist</span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

    <h2 class="admin-page-title">My Bookings</h2>
    <p class="admin-page-subtitle">View and manage all your bookings</p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Payment completed successfully. Your booking is confirmed!</p>
    <?php } ?>
    <?php if(isset($_GET['cancelled'])) { ?>
        <p class="success">Booking cancelled successfully.</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <!-- FILTER BY STATUS -->
    <div class="filter-bar">
        <a href="mybookings.php" class="filter-btn <?php echo !isset($_GET['status']) ? 'active' : ''; ?>">All</a>
        <a href="mybookings.php?status=pending" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='pending') ? 'active' : ''; ?>">Pending</a>
        <a href="mybookings.php?status=confirmed" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='confirmed') ? 'active' : ''; ?>">Confirmed</a>
        <a href="mybookings.php?status=completed" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='completed') ? 'active' : ''; ?>">Completed</a>
        <a href="mybookings.php?status=cancelled" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='cancelled') ? 'active' : ''; ?>">Cancelled</a>
    </div>

    <!-- BOOKINGS TABLE -->
    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Listing</th>
            <th>Type</th>
            <th>Start</th>
            <th>End</th>
            <th>Guests</th>
            <th>Total (Rs.)</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <!-- PHP:
            if(isset($_GET['status'])) {
                $status = $_GET['status'];
                $sql = "SELECT b.*, l.title, l.type
                        FROM bookings b
                        JOIN listings l ON b.listing_id = l.id
                        WHERE b.tourist_id = ? AND b.status = ?
                        ORDER BY b.created_at DESC";
            } else {
                $sql = "SELECT b.*, l.title, l.type
                        FROM bookings b
                        JOIN listings l ON b.listing_id = l.id
                        WHERE b.tourist_id = ?
                        ORDER BY b.created_at DESC";
            }
            loop through results and echo each row
        -->

        <!-- Example populated row: -->
        <!--
        <tr>
            <td>#<?php echo $booking['id']; ?></td>
            <td>
                <a href="listingdetail.php?id=<?php echo $booking['listing_id']; ?>" style="color:#1c7293;">
                    <?php echo $booking['title']; ?>
                </a>
            </td>
            <td>
                <span class="listing-type-badge listing-type-<?php echo $booking['type']; ?>">
                    <?php echo ucfirst(str_replace('_', ' ', $booking['type'])); ?>
                </span>
            </td>
            <td><?php echo date('d M Y h:i A', strtotime($booking['start_datetime'])); ?></td>
            <td><?php echo date('d M Y h:i A', strtotime($booking['end_datetime'])); ?></td>
            <td><?php echo $booking['guest_count']; ?></td>
            <td><?php echo number_format($booking['total_price'], 2); ?></td>
            <td>
                <span class="status-badge status-<?php echo $booking['status']; ?>">
                    <?php echo ucfirst($booking['status']); ?>
                </span>
            </td>
            <td>
                <div class="action-btns">
                    <?php if($booking['status'] == 'pending') { ?>
                        <a href="payment.php?booking_id=<?php echo $booking['id']; ?>"
                           class="tbl-btn tbl-btn-green">Pay Now</a>
                        <a href="touristcancelbookingbackend.php?id=<?php echo $booking['id']; ?>"
                           class="tbl-btn tbl-btn-red"
                           onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</a>
                    <?php } elseif($booking['status'] == 'completed') { ?>
                        <?php
                        // check if review already written for this booking
                        $reviewCheck = "SELECT id FROM reviews WHERE booking_id = ? AND tourist_id = ?";
                        ?>
                        <?php if(!$reviewExists) { ?>
                            <a href="writereview.php?booking_id=<?php echo $booking['id']; ?>"
                               class="tbl-btn tbl-btn-amber">Write Review</a>
                        <?php } else { ?>
                            <span style="color:#999; font-size:12px;">Reviewed</span>
                        <?php } ?>
                    <?php } else { ?>
                        <span style="color:#999; font-size:12px;">—</span>
                    <?php } ?>
                </div>
            </td>
        </tr>
        -->

        <tr>
            <td colspan="9" class="table-empty">No bookings found</td>
        </tr>

    </table>

</div>

</body>
</html>
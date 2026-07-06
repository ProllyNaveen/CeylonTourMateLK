<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['tourist']);

require_once 'Classes/Booking.php';
require_once 'Classes/Review.php';

$userId   = $_SESSION['user_id'];
$status   = isset($_GET['status']) ? $_GET['status'] : null;
$bookings = Booking::getBookingsForTourist($userId, $status);
?>
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
                <a href="index.php"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <a href="explore.php" class="nav-link">Explore</a>
                <a href="mybookings.php" class="nav-link">My Bookings</a>
                <a href="touristmessages.php" class="nav-link">Messages</a>
                <a href="touristnotifications.php" class="nav-link">Notifications</a>
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
    <p class="success">Booking made successfully! Waiting for partner confirmation.</p>
<?php } ?>
<?php if(isset($_GET['paidsuccess'])) { ?>
    <p class="success">Payment completed successfully!</p>
<?php } ?>
<?php if(isset($_GET['cancelled'])) { ?>
    <p class="success">Booking cancelled successfully.</p>
<?php } ?>
<?php if(isset($_GET['reviewsuccess'])) { ?>
    <p class="success">Review submitted successfully. Thank you!</p>
<?php } ?>
<?php if(isset($_GET['notconfirmed'])) { ?>
    <p class="error">Your booking has not been confirmed by the partner yet.</p>
<?php } ?>
<?php if(isset($_GET['cannotcancel'])) { ?>
    <p class="error">This booking cannot be cancelled.</p>
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

        <?php if(empty($bookings)) { ?>
    <tr>
        <td colspan="9" class="table-empty">No bookings found</td>
    </tr>
<?php } else { ?>
    <?php foreach($bookings as $booking) { ?>
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
        <td>Rs. <?php echo number_format($booking['total_price'], 2); ?></td>
        <td>
            <span class="status-badge status-<?php echo $booking['status']; ?>">
                <?php echo ucfirst($booking['status']); ?>
            </span>
        </td>
        <td>
            <div class="action-btns">
                <?php if($booking['status'] == 'confirmed') { ?>
                    <a href="payment.php?booking_id=<?php echo $booking['id']; ?>"
                       class="tbl-btn tbl-btn-green">Pay Now</a>
                    <a href="touristcancelbookingbackend.php?id=<?php echo $booking['id']; ?>"
                       class="tbl-btn tbl-btn-red"
                       onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</a>
                <?php } elseif($booking['status'] == 'pending') { ?>
                    <a href="touristcancelbookingbackend.php?id=<?php echo $booking['id']; ?>"
                       class="tbl-btn tbl-btn-red"
                       onclick="return confirm('Are you sure you want to cancel this booking?')">Cancel</a>
                <?php } elseif($booking['status'] == 'completed') { ?>
                    <?php if(!Review::hasReviewed($booking['id'], $userId)) { ?>
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
    <?php } ?>
<?php } ?>

    </table>

</div>

</body>
</html>
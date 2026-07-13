<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['experience_provider']);

require_once 'Classes/Booking.php';

$userId   = $_SESSION['user_id'];
$status   = isset($_GET['status']) ? $_GET['status'] : null;
$bookings = Booking::getBookingsForPartner($userId, $status);
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
                <a href="experiencedashboard.php"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <span class="partner-badge experience-badge">Experience Provider</span>
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

    <a href="experiencedashboard.php" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">My Bookings</h2>
    <p class="admin-page-subtitle">View and manage bookings made for your experiences</p>

    <?php if(isset($_GET['success'])) { ?>
    <p class="success">Action completed successfully.</p>
<?php } ?>
<?php if(isset($_GET['msgsent'])) { ?>
    <p class="success">Message sent successfully.</p>
<?php } ?>
<?php if(isset($_GET['error'])) { ?>
    <p class="error">Something went wrong. Please try again.</p>
<?php } ?>

    <!-- FILTER BY STATUS -->
    <div class="filter-bar">
        <a href="experiencemybookings.php" class="filter-btn <?php echo !isset($_GET['status']) ? 'active' : ''; ?>">All</a>
        <a href="experiencemybookings.php?status=pending" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='pending') ? 'active' : ''; ?>">Pending</a>
        <a href="experiencemybookings.php?status=confirmed" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='confirmed') ? 'active' : ''; ?>">Confirmed</a>
        <a href="experiencemybookings.php?status=completed" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='completed') ? 'active' : ''; ?>">Completed</a>
        <a href="experiencemybookings.php?status=cancelled" class="filter-btn <?php echo (isset($_GET['status']) && $_GET['status']=='cancelled') ? 'active' : ''; ?>">Cancelled</a>
    </div>

    <!-- BOOKINGS TABLE -->
    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Tourist</th>
            <th>Experience</th>
            <th>Start</th>
            <th>End</th>
            <th>Guests</th>
            <th>Total (Rs.)</th>
            <th>Earnings (Rs.)</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>

        <?php if(empty($bookings)) { ?>
    <tr>
        <td colspan="10" class="table-empty">No bookings found</td>
    </tr>
<?php } else { ?>
    <?php foreach($bookings as $booking) { ?>
    <tr>
        <td><?php echo $booking['id']; ?></td>
        <td><?php echo $booking['firstname'] . ' ' . $booking['lastname']; ?></td>
        <td><?php echo $booking['title']; ?></td>
        <td><?php echo date('d M Y h:i A', strtotime($booking['start_datetime'])); ?></td>
        <td><?php echo date('d M Y h:i A', strtotime($booking['end_datetime'])); ?></td>
        <td><?php echo $booking['guest_count']; ?></td>
        <td>Rs. <?php echo number_format($booking['total_price'], 2); ?></td>
        <td>Rs. <?php echo number_format($booking['earnings'], 2); ?></td>
        <td>
            <span class="status-badge status-<?php echo $booking['status']; ?>">
                <?php echo ucfirst($booking['status']); ?>
            </span>
        </td>
        <td>
            <div class="action-btns">
                <?php if($booking['status'] == 'pending') { ?>
                    <a href="experienceconfirmbookingbackend.php?id=<?php echo $booking['id']; ?>"
                       class="tbl-btn tbl-btn-green">Confirm</a>
                    <a href="experiencecancelbookingbackend.php?id=<?php echo $booking['id']; ?>"
                       class="tbl-btn tbl-btn-red"
                       onclick="return confirm('Are you sure?')">Cancel</a>
                <?php } elseif($booking['status'] == 'confirmed') { ?>
                    <a href="experiencecompletebookingbackend.php?id=<?php echo $booking['id']; ?>"
                       class="tbl-btn tbl-btn-amber">Mark Complete</a>
					   <form method="post" action="sendmessagebackend.php" style="display:inline;">
            <input type="hidden" name="receiver_id" value="<?php echo $booking['tourist_id']; ?>">
            <input type="hidden" name="listing_id" value="<?php echo $booking['listing_id']; ?>">
            <input type="hidden" name="message" value="Hi! I'm reaching out about your booking.">
            <input type="hidden" name="redirect" value="experiencemybookings.php">
            <button type="submit" class="tbl-btn tbl-btn-amber">Message</button>
        </form>
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
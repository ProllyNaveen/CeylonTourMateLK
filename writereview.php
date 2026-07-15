<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['tourist']);

require_once 'Classes/DBconnection.php';

$bookingId = $_GET['booking_id'];
$userId    = $_SESSION['user_id'];
$conn      = DBconnection::getInstance()->getConnection();


$sql  = "SELECT b.*, l.title, l.type, l.city, u.firstname, u.lastname, u.profile_pic
          FROM bookings b
          JOIN listings l ON b.listing_id = l.id
          JOIN users u ON l.user_id = u.id
          WHERE b.id = ? AND b.tourist_id = ? AND b.status = 'completed'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $bookingId, $userId);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    header("Location: mybookings.php?error=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Write a Review - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validatewritereview.js"></script>
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
                <span class="admin-welcome">Welcome, <?php echo htmlspecialchars($_SESSION['firstname']); ?></span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

    <a href="mybookings.php" class="back-link">&larr; Back to My Bookings</a>

    <h2 class="admin-page-title">Write a Review</h2>
    <p class="admin-page-subtitle">Share your experience to help other travellers</p>
		<?php if(isset($_GET['error'])) { ?>
			<p class="error">Something went wrong. Please try again.</p>
		<?php } ?>
		<?php if(isset($_GET['already'])) { ?>
			<p class="error">You have already written a review for this booking.</p>
		<?php } ?>

    <div class="listing-detail-layout">

        <!-- LEFT: REVIEW FORM -->
        <div class="listing-detail-main">
            <div class="listing-detail-section">

                <form name="writereviewform" method="post" action="writereviewbackend.php" onsubmit="return validateWriteReview();">

                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">
					<input type="hidden" name="listing_id" value="<?php echo $booking['listing_id']; ?>">

                    <label>Your Rating</label>
                    <div class="star-rating-input">
                        <input type="radio" name="rating" id="star5" value="5">
                        <label for="star5" class="star-label">&#11088;</label>
                        <input type="radio" name="rating" id="star4" value="4">
                        <label for="star4" class="star-label">&#11088;</label>
                        <input type="radio" name="rating" id="star3" value="3">
                        <label for="star3" class="star-label">&#11088;</label>
                        <input type="radio" name="rating" id="star2" value="2">
                        <label for="star2" class="star-label">&#11088;</label>
                        <input type="radio" name="rating" id="star1" value="1">
                        <label for="star1" class="star-label">&#11088;</label>
                    </div>
                    <p class="form-note" style="margin-bottom:15px;">Select a rating from 1 to 5 stars</p>

                    <label>Your Review</label>
                    <textarea name="comment" id="reviewComment" placeholder="Tell us about your experience..." rows="5" style="width:100%; padding:10px; border:1px solid #d6d6d6; border-radius:5px; font-size:14px; margin-bottom:15px; resize:vertical;"></textarea>

                    <button type="submit" class="btn btn-primary">Submit Review</button>

                </form>

            </div>
        </div>

        <!-- RIGHT: BOOKING SUMMARY -->
        <div class="listing-detail-sidebar">
            <div class="sidebar-card">
                <h3 class="admin-subsection-title">Booking Details</h3>
			<div style="display:flex; align-items:center; gap:12px; margin-bottom:15px;">
    <img src="<?php echo $booking['profile_pic'] ?? 'uploads/profiles/default.png'; ?>" class="convo-avatar" alt="Owner">
    <div>
        <span class="convo-name"><?php echo $booking['firstname'] . ' ' . $booking['lastname']; ?></span>
        <span class="convo-listing">Owner</span>
    </div>
</div>

		<table class="detail-table">
			<tr>
				<td>Listing</td>
				<td><?php echo $booking['title']; ?></td>
			</tr>
			<tr>
				<td>Type</td>
				<td><?php echo ucfirst(str_replace('_', ' ', $booking['type'])); ?></td>
			</tr>
			<tr>
				<td>City</td>
				<td><?php echo $booking['city']; ?></td>
			</tr>
			<tr>
				<td>Start</td>
				<td><?php echo date('d M Y h:i A', strtotime($booking['start_datetime'])); ?></td>
			</tr>
			<tr>
				<td>End</td>
				<td><?php echo date('d M Y h:i A', strtotime($booking['end_datetime'])); ?></td>
			</tr>
			<tr>
				<td>Guests</td>
				<td><?php echo $booking['guest_count']; ?></td>
			</tr>
		</table>

            </div>
        </div>

    </div>

</div>

</body>
</html>
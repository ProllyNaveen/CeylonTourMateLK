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

// fetch booking details
$sql  = "SELECT b.*, l.title, l.type, l.city, u.firstname, u.lastname
          FROM bookings b
          JOIN listings l ON b.listing_id = l.id
          JOIN users u ON l.user_id = u.id
          WHERE b.id = ? AND b.tourist_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $bookingId, $userId);
$stmt->execute();
$booking = $stmt->get_result()->fetch_assoc();

if (!$booking) {
    header("Location: mybookings.php?error=1");
    exit();
}

// only confirmed bookings can be paid
if ($booking['status'] != 'confirmed') {
    header("Location: mybookings.php?notconfirmed=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Payment - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validatepayment.js"></script>
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

    <!-- PHP:
        $sql = "SELECT b.*, l.title, l.type, l.city, u.firstname, u.lastname
                FROM bookings b
                JOIN listings l ON b.listing_id = l.id
                JOIN users u ON l.user_id = u.id
                WHERE b.id = ? AND b.tourist_id = ?";
    -->

    <a href="mybookings.php" class="back-link">&larr; Back to My Bookings</a>

    <h2 class="admin-page-title">Complete Payment</h2>
    <p class="admin-page-subtitle">Enter your card details to confirm your booking</p>

    <?php if(isset($_GET['error'])) { ?>
		<p class="error">Payment failed. Please check your card details and try again.</p>
	<?php } ?>

    <div class="listing-detail-layout">

        <!-- LEFT: PAYMENT FORM -->
        <div class="listing-detail-main">
            <div class="listing-detail-section">

                <h3 class="admin-subsection-title">Card Details</h3>

                <form name="paymentform" method="post" action="paymentbackend.php" onsubmit="return validatePayment();">

                    <input type="hidden" name="booking_id" value="<?php echo $booking['id']; ?>">

                    <label>Cardholder Name</label>
                    <input type="text" name="cardholder_name" placeholder="Name as it appears on card">

                    <label>Card Number</label>
                    <input type="text" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19" id="card_number" oninput="formatCardNumber(this)">

                    <div style="display:flex; gap:15px;">
                        <div style="flex:1;">
                            <label>Expiry Date</label>
                            <input type="text" name="expiry_date" placeholder="MM/YY" maxlength="5" id="expiry_date" oninput="formatExpiry(this)">
                        </div>
                        <div style="flex:1;">
                            <label>CVV</label>
                            <input type="text" name="cvv" placeholder="123" maxlength="3">
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary" style="margin-top:15px; width:100%;">
						Pay Rs. <?php echo number_format($booking['total_price'], 2); ?>
					</button>

                    <p class="form-note" style="margin-top:12px; text-align:center;">
                        &#128274; Your payment is secured. Card details are not stored.
                    </p>

                </form>

            </div>
        </div>

        <!-- RIGHT: ORDER SUMMARY -->
        <div class="listing-detail-sidebar">
            <div class="sidebar-card">
                <h3 class="admin-subsection-title">Order Summary</h3>

                <table class="detail-table">
				<tr>
					<td>Booking ID</td>
					<td>#<?php echo $booking['id']; ?></td>
				</tr>
				<tr>
					<td>Listing</td>
					<td><?php echo $booking['title']; ?></td>
				</tr>
				<tr>
					<td>Type</td>
					<td><?php echo ucfirst(str_replace('_', ' ', $booking['type'])); ?></td>
				</tr>
				<tr>
					<td>Owner</td>
					<td><?php echo $booking['firstname'] . ' ' . $booking['lastname']; ?></td>
				</tr>
				<tr>
					<td>Check In / Start</td>
					<td><?php echo date('d M Y h:i A', strtotime($booking['start_datetime'])); ?></td>
				</tr>
				<tr>
					<td>Check Out / End</td>
					<td><?php echo date('d M Y h:i A', strtotime($booking['end_datetime'])); ?></td>
				</tr>
				<tr>
					<td>Guests</td>
					<td><?php echo $booking['guest_count']; ?></td>
				</tr>
				<tr>
					<td>Total Amount</td>
					<td><strong>Rs. <?php echo number_format($booking['total_price'], 2); ?></strong></td>
				</tr>
			</table>

                <div class="payment-secure-note">
                    &#128274; Secured Payment
                </div>

            </div>
        </div>

    </div>

</div>

</body>
</html>
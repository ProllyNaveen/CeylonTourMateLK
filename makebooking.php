<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['tourist']);

require_once 'Classes/DBconnection.php';
require_once 'Classes/Room.php';

$listingId = $_GET['listing_id'];
$userId    = $_SESSION['user_id'];
$conn      = DBconnection::getInstance()->getConnection();


$sql  = "SELECT l.*, u.firstname, u.lastname
          FROM listings l
          JOIN users u ON l.user_id = u.id
          WHERE l.id = ? AND l.status = 'approved'";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $listingId);
$stmt->execute();
$listing = $stmt->get_result()->fetch_assoc();

if (!$listing) {
    header("Location: explore.php");
    exit();
}


$rooms = [];
if ($listing['type'] == 'hotel') {
    $hotelSql  = "SELECT hl.id FROM hotel_listings hl WHERE hl.listing_id = ?";
    $hotelStmt = $conn->prepare($hotelSql);
    $hotelStmt->bind_param("i", $listingId);
    $hotelStmt->execute();
    $hotelResult = $hotelStmt->get_result()->fetch_assoc();

    if ($hotelResult) {
        $rooms = Room::getRoomsByHotelListing($hotelResult['id'], true);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Make a Booking - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validatemakebooking.js"></script>
</head>
<body>


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


<div class="admin-section">

    

    <a href="listingdetail.php?id=<?php echo $listing['id']; ?>" class="back-link">&larr; Back to Listing</a>

    <h2 class="admin-page-title">Make a Booking</h2>
<p class="admin-page-subtitle">Book: <strong><?php echo $listing['title']; ?></strong></p>
		
		<?php if(isset($_GET['error'])) { ?>
			<p class="error">Something went wrong. Please try again.</p>
		<?php } ?>
		<?php if(isset($_GET['noroomsselected'])) { ?>
			<p class="error">Please select at least one room.</p>
		<?php } ?>
		<?php if(isset($_GET['unavailable'])) { ?>
			<p class="error">Sorry, the selected rooms are not available for your chosen dates.</p>
		<?php } ?>


    <div class="listing-detail-layout">

        
        <div class="listing-detail-main">
            <div class="listing-detail-section">

                <form name="makebookingform" method="post" action="makebookingbackend.php" onsubmit="return validateMakeBooking();">

                    <input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>">
					<input type="hidden" name="listing_type" value="<?php echo $listing['type']; ?>" id="listing_type">

                    <label>Start Date & Time</label>
                    <input type="datetime-local" name="start_datetime" id="start_datetime">

                    <label>End Date & Time</label>
                    <input type="datetime-local" name="end_datetime" id="end_datetime">

                    <label>Number of Guests</label>
                    <input type="number" name="guest_count" id="guest_count" placeholder="Enter number of guests" min="1">

                    
                    <?php if($listing['type'] == 'hotel') { ?>
					<div id="room-selection-div">
						<label>Select Rooms</label>
						<p class="form-note" style="margin-bottom:10px;">You can select multiple rooms</p>
						<table class="admin-table" style="margin-bottom:15px;">
							<tr>
								<th>Select</th>
								<th>Room Type</th>
								<th>Capacity</th>
								<th>Price Per Night (Rs.)</th>
							</tr>
							<?php if(empty($rooms)) { ?>
								<tr>
									<td colspan="4" class="table-empty">No available rooms</td>
								</tr>
							<?php } else { ?>
								<?php foreach($rooms as $room) { ?>
								<tr>
									<td><input type="checkbox" name="room_ids[]" value="<?php echo $room['id']; ?>"></td>
									<td><?php echo ucfirst($room['room_type']); ?></td>
									<td><?php echo $room['capacity']; ?> persons</td>
									<td>Rs. <?php echo number_format($room['price_per_night'], 2); ?></td>
								</tr>
								<?php } ?>
							<?php } ?>
						</table>
					</div>
					<?php } ?>

                    <button type="submit" class="btn btn-primary" style="margin-top:10px;">Proceed to Payment</button>

                </form>

            </div>
        </div>

        
        <div class="listing-detail-sidebar">
            <div class="sidebar-card">
                <h3 class="admin-subsection-title">Booking Summary</h3>

                <table class="detail-table">
				<tr>
					<td>Listing</td>
					<td><?php echo $listing['title']; ?></td>
				</tr>
				<tr>
					<td>Type</td>
					<td><?php echo ucfirst(str_replace('_', ' ', $listing['type'])); ?></td>
				</tr>
				<tr>
					<td>Location</td>
					<td><?php echo $listing['city']; ?></td>
				</tr>
				<tr>
					<td>Base Price</td>
					<td>Rs. <?php echo number_format($listing['price'], 2); ?></td>
				</tr>
				<tr>
					<td>Owner</td>
					<td><?php echo $listing['firstname'] . ' ' . $listing['lastname']; ?></td>
				</tr>
			</table>

                <p class="form-note" style="margin-top:15px;">
                    Total price will be calculated based on your selected dates, guests and rooms. You will see the final amount on the payment page.
                </p>
            </div>
        </div>

    </div>

</div>

</body>
</html>
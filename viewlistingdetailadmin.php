<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['admin']);

require_once 'Classes/DBconnection.php';
require_once 'Classes/ListingImage.php';

$listingId = $_GET['id'];
$conn      = DBconnection::getInstance()->getConnection();


$sql  = "SELECT l.*, u.firstname, u.lastname, u.profile_pic, u.phone, u.email
          FROM listings l
          JOIN users u ON l.user_id = u.id
          WHERE l.id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $listingId);
$stmt->execute();
$listing = $stmt->get_result()->fetch_assoc();

if (!$listing) {
    header("Location: viewlistingrequests.php");
    exit();
}


$subDetails = null;
$rooms      = [];

if ($listing['type'] == 'tour_guide') {
    $subSql  = "SELECT * FROM tourguide_listings WHERE listing_id = ?";
    $subStmt = $conn->prepare($subSql);
    $subStmt->bind_param("i", $listingId);
    $subStmt->execute();
    $subDetails = $subStmt->get_result()->fetch_assoc();

} elseif ($listing['type'] == 'hotel') {
    $subSql  = "SELECT * FROM hotel_listings WHERE listing_id = ?";
    $subStmt = $conn->prepare($subSql);
    $subStmt->bind_param("i", $listingId);
    $subStmt->execute();
    $subDetails = $subStmt->get_result()->fetch_assoc();

    if ($subDetails) {
        $roomSql  = "SELECT * FROM rooms WHERE hotel_listing_id = ?";
        $roomStmt = $conn->prepare($roomSql);
        $roomStmt->bind_param("i", $subDetails['id']);
        $roomStmt->execute();
        $rooms = $roomStmt->get_result()->fetch_all(MYSQLI_ASSOC);
    }

} elseif ($listing['type'] == 'shop') {
    $subSql  = "SELECT * FROM shop_listings WHERE listing_id = ?";
    $subStmt = $conn->prepare($subSql);
    $subStmt->bind_param("i", $listingId);
    $subStmt->execute();
    $subDetails = $subStmt->get_result()->fetch_assoc();

} elseif ($listing['type'] == 'experience') {
    $subSql  = "SELECT * FROM experience_listings WHERE listing_id = ?";
    $subStmt = $conn->prepare($subSql);
    $subStmt->bind_param("i", $listingId);
    $subStmt->execute();
    $subDetails = $subStmt->get_result()->fetch_assoc();
}


$images = ListingImage::getImagesByListing($listingId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Listing - CeylonTourMate.lk</title>
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
                <span class="admin-welcome">Welcome, <?php echo $_SESSION['firstname']; ?></span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

<?php
$from = isset($_GET['from']) ? $_GET['from'] : 'requests';

if ($from == 'bookings') {
    $backLink = 'viewallbookings.php';
    $backText = 'Back to All Bookings';
} elseif ($from == 'userprofile') {
    $backLink = 'javascript:history.back()';
    $backText = 'Back to User Profile';
} elseif ($from == 'alllistings') {
    $backLink = 'viewalllistings.php';
    $backText = 'Back to All Listings';
} else {
    $backLink = 'viewlistingrequests.php';
    $backText = 'Back to Listing Requests';
}
?>
<a href="<?php echo $backLink; ?>" class="back-link">&larr; <?php echo $backText; ?></a>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Action completed successfully.</p>
    <?php } ?>

    <!-- LISTING HEADER -->
    <div class="listing-detail-header">
        <div>
            <h2 class="admin-page-title"><?php echo $listing['title']; ?></h2>
            <p class="listing-card-location">&#128205; <?php echo $listing['city']; ?> &nbsp;|&nbsp;
                <span class="listing-type-badge listing-type-<?php echo $listing['type']; ?>">
                    <?php echo ucfirst(str_replace('_', ' ', $listing['type'])); ?>
                </span>
            </p>
            <p style="font-size:14px; margin-top:8px;">
                Status: <span class="status-badge status-<?php echo $listing['status']; ?>">
                    <?php echo ucfirst($listing['status']); ?>
                </span>
            </p>
        </div>
        <div class="listing-detail-price">
            <span>Rs. <?php echo number_format($listing['price'], 2); ?></span>
            <span style="font-size:13px; color:#6b6b6b;">per person / per night</span>
        </div>
    </div>

    <!-- LISTING IMAGES -->
    <div class="listing-images-row">
        <?php if(empty($images)) { ?>
            <div class="listing-img-placeholder">&#127758; No images uploaded</div>
        <?php } else { ?>
            <?php foreach($images as $image) { ?>
                <img src="<?php echo $image['image_path']; ?>" class="listing-detail-img" alt="listing image">
            <?php } ?>
        <?php } ?>
    </div>

    <div class="listing-detail-layout">

        <!-- LEFT: MAIN DETAILS -->
        <div class="listing-detail-main">

            <!-- DESCRIPTION -->
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">About</h3>
                <p style="font-size:14px; color:#2b2b2b; line-height:1.7;"><?php echo $listing['description']; ?></p>
            </div>

            <!-- TYPE SPECIFIC DETAILS -->
            <?php if($listing['type'] == 'tour_guide' && $subDetails) { ?>
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Tour Guide Details</h3>
                <table class="detail-table">
                    <tr><td>Languages</td><td><?php echo $subDetails['languages']; ?></td></tr>
                    <tr><td>Specialization</td><td><?php echo $subDetails['specialization']; ?></td></tr>
                    <tr><td>Experience</td><td><?php echo $subDetails['experience_years']; ?> years</td></tr>
                    <tr><td>Availability</td><td><?php echo $subDetails['availability']; ?></td></tr>
                </table>
            </div>
            <?php } ?>

            <?php if($listing['type'] == 'hotel' && $subDetails) { ?>
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Hotel Details</h3>
                <table class="detail-table">
                    <tr><td>Star Rating</td><td><?php echo str_repeat('&#11088;', $subDetails['star_rating']); ?></td></tr>
                    <tr><td>Amenities</td><td><?php echo $subDetails['amenities']; ?></td></tr>
                    <tr><td>Total Rooms</td><td><?php echo $subDetails['total_rooms']; ?></td></tr>
                    <tr><td>Check-in Time</td><td><?php echo date('h:i A', strtotime($subDetails['check_in_time'])); ?></td></tr>
                    <tr><td>Check-out Time</td><td><?php echo date('h:i A', strtotime($subDetails['check_out_time'])); ?></td></tr>
                </table>
            </div>
            <?php if(!empty($rooms)) { ?>
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Rooms</h3>
                <table class="admin-table">
                    <tr>
                        <th>Room Type</th>
                        <th>Capacity</th>
                        <th>Price Per Night (Rs.)</th>
                        <th>Description</th>
                        <th>Available</th>
                    </tr>
                    <?php foreach($rooms as $room) { ?>
                    <tr>
                        <td><?php echo ucfirst($room['room_type']); ?></td>
                        <td><?php echo $room['capacity']; ?> persons</td>
                        <td><?php echo number_format($room['price_per_night'], 2); ?></td>
                        <td><?php echo $room['description']; ?></td>
                        <td>
                            <?php if($room['is_available']) { ?>
                                <span class="status-badge status-confirmed">Yes</span>
                            <?php } else { ?>
                                <span class="status-badge status-cancelled">No</span>
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <?php } ?>
            <?php } ?>

            <?php if($listing['type'] == 'shop' && $subDetails) { ?>
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Shop Details</h3>
                <table class="detail-table">
                    <tr><td>Shop Type</td><td><?php echo $subDetails['shop_type']; ?></td></tr>
                    <tr><td>Opening Time</td><td><?php echo date('h:i A', strtotime($subDetails['open_time'])); ?></td></tr>
                    <tr><td>Closing Time</td><td><?php echo date('h:i A', strtotime($subDetails['close_time'])); ?></td></tr>
                </table>
            </div>
            <?php } ?>

            <?php if($listing['type'] == 'experience' && $subDetails) { ?>
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Experience Details</h3>
                <table class="detail-table">
                    <tr><td>Type</td><td><?php echo $subDetails['experience_type']; ?></td></tr>
                    <tr><td>Duration</td><td><?php echo $subDetails['duration']; ?></td></tr>
                    <tr><td>Max Group Size</td><td><?php echo $subDetails['max_group_size']; ?> persons</td></tr>
                    <tr><td>Availability</td><td><?php echo $subDetails['availability']; ?></td></tr>
                </table>
            </div>
            <?php } ?>

        </div>

        <!-- RIGHT: SIDEBAR -->
        <div class="listing-detail-sidebar">

            <!-- OWNER INFO -->
            <div class="sidebar-card">
                <h3 class="admin-subsection-title">Owner Details</h3>
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:15px;">
                    <img src="<?php echo $listing['profile_pic'] ?? 'uploads/profiles/default.png'; ?>" class="convo-avatar" alt="Owner">
                    <div>
                        <span class="convo-name"><?php echo $listing['firstname'] . ' ' . $listing['lastname']; ?></span>
                        <span class="convo-listing">&#128222; <?php echo $listing['phone']; ?></span>
                        <span class="convo-listing">&#128231; <?php echo $listing['email']; ?></span>
                    </div>
                </div>
            </div>

            <!-- DECISION / ACTIONS -->
			<div class="sidebar-card" style="margin-top:20px;">
				<h3 class="admin-subsection-title">Actions</h3>

				<?php if($listing['status'] == 'pending') { ?>
					<p style="font-size:13px; color:#6b6b6b; margin-bottom:15px;">
						Review the listing details and approve or reject it.
					</p>
					<div style="display:flex; flex-direction:column; gap:10px;">
						<a href="approvelistingbackend.php?id=<?php echo $listing['id']; ?>&action=approve"
						   class="btn btn-primary" style="text-align:center;">Approve</a>
						<a href="approvelistingbackend.php?id=<?php echo $listing['id']; ?>&action=reject&from=<?php echo $from; ?>"
							   class="btn btn-login"
							   onclick="return confirm('Are you sure you want to reject this listing?')"
							   style="text-align:center; background-color:#faece7; color:#993c1d; border-color:#993c1d;">Reject</a>
					</div>

				<?php } elseif($listing['status'] == 'approved') { ?>
					<p style="font-size:13px; color:#6b6b6b; margin-bottom:15px;">
						This listing is currently
						<span class="status-badge status-confirmed">Approved</span>
					</p>
					<div style="display:flex; flex-direction:column; gap:10px;">
						<a href="adminHidelistingbackend.php?id=<?php echo $listing['id']; ?>&from=<?php echo $from; ?>"
						   class="btn btn-login"
						   onclick="return confirm('Are you sure you want to hide this listing?')"
						   style="text-align:center; background-color:#faece7; color:#993c1d; border-color:#993c1d;">
						   Hide Listing</a>
					</div>

				<?php } else { ?>
					<p style="font-size:13px; color:#6b6b6b; margin-bottom:15px;">
						This listing is currently
						<span class="status-badge status-cancelled"><?php echo ucfirst($listing['status']); ?></span>
					</p>
					<div style="display:flex; flex-direction:column; gap:10px;">
						<a href="approvelistingbackend.php?id=<?php echo $listing['id']; ?>&action=approve&from=<?php echo $from; ?>"
							class="btn btn-primary" style="text-align:center;">Re-approve Listing</a>
					</div>

				<?php } ?>

			</div>

        </div>

    </div>

</div>

</body>
</html>
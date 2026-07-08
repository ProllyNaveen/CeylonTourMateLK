<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['hotel_owner']);

require_once 'Classes/DBconnection.php';
require_once 'Classes/ListingImage.php';
require_once 'Classes/Room.php';

$listingId = $_GET['id'];
$userId    = $_SESSION['user_id'];
$conn      = DBconnection::getInstance()->getConnection();

$sql  = "SELECT l.*, hl.id as hotel_listing_id, hl.star_rating, hl.amenities,
          hl.total_rooms, hl.check_in_time, hl.check_out_time
          FROM listings l
          JOIN hotel_listings hl ON l.id = hl.listing_id
          WHERE l.id = ? AND l.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $listingId, $userId);
$stmt->execute();
$listing = $stmt->get_result()->fetch_assoc();

if (!$listing) {
    header("Location: hotelownermanagelistings.php");
    exit();
}

$images = ListingImage::getImagesByListing($listingId);
$rooms  = Room::getRoomsByHotelListing($listing['hotel_listing_id']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>View Listing - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="hotelownerdashboard.php"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <span class="partner-badge hotel-badge">Hotel Owner</span>
            </td>
            <td class="nav-buttons-cell">
                <span class="admin-welcome">Welcome, <?php echo $_SESSION['firstname']; ?></span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<div class="admin-section">

    <a href="hotelownermanagelistings.php" class="back-link">&larr; Back to My Listings</a>

    <div class="listing-detail-header">
        <div>
            <h2 class="admin-page-title"><?php echo $listing['title']; ?></h2>
            <p class="listing-card-location">&#128205; <?php echo $listing['city']; ?></p>
            <p style="font-size:14px; margin-top:8px;">
                Status: <span class="status-badge status-<?php echo $listing['status']; ?>">
                    <?php echo ucfirst($listing['status']); ?>
                </span>
            </p>
        </div>
        <div class="listing-detail-price">
            <span>Rs. <?php echo number_format($listing['price'], 2); ?></span>
            <span style="font-size:13px; color:#6b6b6b;">starting per night</span>
        </div>
    </div>

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
        <div class="listing-detail-main">

            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">About</h3>
                <p style="font-size:14px; color:#2b2b2b; line-height:1.7;"><?php echo $listing['description']; ?></p>
            </div>

            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Hotel Details</h3>
                <table class="detail-table">
                    <tr><td>Star Rating</td><td><?php echo str_repeat('&#11088;', $listing['star_rating']); ?></td></tr>
                    <tr><td>Amenities</td><td><?php echo $listing['amenities']; ?></td></tr>
                    <tr><td>Total Rooms</td><td><?php echo $listing['total_rooms']; ?></td></tr>
                    <tr><td>Check-in Time</td><td><?php echo date('h:i A', strtotime($listing['check_in_time'])); ?></td></tr>
                    <tr><td>Check-out Time</td><td><?php echo date('h:i A', strtotime($listing['check_out_time'])); ?></td></tr>
                    <tr><td>Address</td><td><?php echo $listing['location']; ?></td></tr>
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

        </div>

        <div class="listing-detail-sidebar">
            <div class="sidebar-card">
                <h3 class="admin-subsection-title">Quick Actions</h3>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <a href="hotelownermanagerooms.php?hotel_listing_id=<?php echo $listing['hotel_listing_id']; ?>"
                       class="btn btn-primary" style="text-align:center;">Manage Rooms</a>
                    <a href="hotelownereditlisting.php?id=<?php echo $listing['id']; ?>"
                       class="btn btn-primary" style="text-align:center;">Edit Listing</a>
                    <a href="hotelownerdeletelistingbackend.php?id=<?php echo $listing['id']; ?>"
                       class="btn btn-login"
                       onclick="return confirm('Are you sure you want to delete this listing?')"
                       style="text-align:center; background-color:#faece7; color:#993c1d; border-color:#993c1d;">Delete Listing</a>
                </div>
            </div>
        </div>
    </div>

</div>
</body>
</html>
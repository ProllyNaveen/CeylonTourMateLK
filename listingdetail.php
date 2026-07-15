<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['tourist']);

require_once 'Classes/DBconnection.php';
require_once 'Classes/Review.php';
require_once 'Classes/ListingImage.php';

$listingId = $_GET['id'];
$userId    = $_SESSION['user_id'];
$conn      = DBconnection::getInstance()->getConnection();


$sql  = "SELECT l.*, u.firstname, u.lastname, u.profile_pic, u.phone,
          AVG(r.rating) as avg_rating, COUNT(r.id) as review_count
          FROM listings l
          JOIN users u ON l.user_id = u.id
          LEFT JOIN reviews r ON l.id = r.listing_id
          WHERE l.id = ? AND l.status = 'approved'
          GROUP BY l.id";
$stmt = $conn->prepare($sql);
$stmt->bind_param("i", $listingId);
$stmt->execute();
$listing = $stmt->get_result()->fetch_assoc();

if (!$listing) {
    header("Location: explore.php");
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
        $roomSql  = "SELECT * FROM rooms WHERE hotel_listing_id = ? AND is_available = 1";
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


$images  = ListingImage::getImagesByListing($listingId);


$reviews = Review::getReviewsByListing($listingId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Listing Detail - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validatelistingdetail.js"></script>
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
                <span class="admin-welcome">Welcome, <?php echo $_SESSION['firstname']; ?></span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

    <a href="explore.php" class="back-link">&larr; Back to Explore</a>

    <?php if(isset($_GET['msgsent'])) { ?>
        <p class="success">Message sent successfully!</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <!-- LISTING HEADER -->
    <div class="listing-detail-header">
        <div>
            <h2 class="admin-page-title"><?php echo $listing['title']; ?></h2>
            <p class="listing-card-location">&#128205; <?php echo $listing['city']; ?> &nbsp;|&nbsp;
                
            </p>
            <p style="font-size:14px; color:#6b6b6b; margin-top:8px;">
                &#11088; <?php echo $listing['avg_rating'] ? number_format($listing['avg_rating'], 1) : 'New'; ?>
                (<?php echo $listing['review_count']; ?> reviews)
            </p>
        </div>
        <div class="listing-detail-price">
    <?php if($listing['type'] == 'hotel') { ?>
        <span style="font-size:14px; color:#6b6b6b;">See room prices below</span>
    <?php } elseif($listing['type'] == 'shop') { ?>
        <span>Rs. <?php echo number_format($listing['price'], 2); ?></span>
        <span style="font-size:13px; color:#6b6b6b;">starting price</span>
    <?php } elseif($listing['type'] == 'tour_guide') { ?>
        <span>Rs. <?php echo number_format($listing['price'], 2); ?></span>
        <span style="font-size:13px; color:#6b6b6b;">per day</span>
    <?php } elseif($listing['type'] == 'experience') { ?>
        <span>Rs. <?php echo number_format($listing['price'], 2); ?></span>
        <span style="font-size:13px; color:#6b6b6b;">per person</span>
    <?php } ?>
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
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Available Rooms</h3>
                <?php if(empty($rooms)) { ?>
                    <p class="table-empty">No available rooms at the moment.</p>
                <?php } else { ?>
                <table class="admin-table">
                    <tr>
                        <th>Room Type</th>
                        <th>Capacity</th>
                        <th>Price Per Night (Rs.)</th>
                        <th>Description</th>
                    </tr>
                    <?php foreach($rooms as $room) { ?>
                    <tr>
                        <td><?php echo ucfirst($room['room_type']); ?></td>
                        <td><?php echo $room['capacity']; ?> persons</td>
                        <td><?php echo number_format($room['price_per_night'], 2); ?></td>
                        <td><?php echo $room['description']; ?></td>
                    </tr>
                    <?php } ?>
                </table>
                <?php } ?>
            </div>
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

            <!-- REVIEWS -->
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Reviews</h3>
                <?php if(empty($reviews)) { ?>
                    <p class="table-empty" style="padding:20px; text-align:center;">No reviews yet</p>
                <?php } else { ?>
                    <?php foreach($reviews as $review) { ?>
                    <div class="review-item">
                        <div class="review-header">
                            <img src="<?php echo $review['profile_pic'] ?? 'uploads/profiles/default.png'; ?>" class="convo-avatar">
                            <div>
                                <span class="convo-name"><?php echo $review['firstname'] . ' ' . $review['lastname']; ?></span>
                                <span style="font-size:12px; color:#6b6b6b; display:block;">
                                    <?php echo date('d M Y', strtotime($review['created_at'])); ?>
                                </span>
                            </div>
                            <span class="review-stars"><?php echo str_repeat('&#11088;', $review['rating']); ?></span>
                        </div>
                        <p class="review-comment"><?php echo $review['comment']; ?></p>
                    </div>
                    <?php } ?>
                <?php } ?>
            </div>

        </div>

        <!-- RIGHT: SIDEBAR -->
        <div class="listing-detail-sidebar">

            <!-- OWNER INFO -->
            <div class="sidebar-card">
                <h3 class="admin-subsection-title">About the Owner</h3>
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:15px;">
                    <img src="<?php echo $listing['profile_pic'] ?? 'uploads/profiles/default.png'; ?>" class="convo-avatar" alt="Owner">
                    <div>
                        <span class="convo-name"><?php echo $listing['firstname'] . ' ' . $listing['lastname']; ?></span>
                        <span class="convo-listing">&#128222; <?php echo $listing['phone']; ?></span>
                    </div>
                </div>

                <form name="messageform" method="post" action="sendmessagebackend.php" onsubmit="return validateMessage();">
                    <input type="hidden" name="receiver_id" value="<?php echo $listing['user_id']; ?>">
                    <input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>">
                    <label style="font-size:13px; color:#2b2b2b; margin-bottom:6px; display:block;">Send a Message</label>
                    <textarea name="message" id="messageText" placeholder="Ask about this listing..." rows="3" style="width:100%; padding:10px; border:1px solid #d6d6d6; border-radius:5px; font-size:13px; resize:none; margin-bottom:10px;"></textarea>
                    <button type="submit" class="btn btn-primary" style="width:100%;">Send Message</button>
                </form>
            </div>

            <!-- BOOK NOW — only for non-shop listings -->
            <?php if($listing['type'] != 'shop') { ?>
            <div class="sidebar-card" style="margin-top:20px;">
                <h3 class="admin-subsection-title">Book Now</h3>
                <p style="font-size:13px; color:#6b6b6b; margin-bottom:15px;">Ready to book? Click below to proceed.</p>
                <a href="makebooking.php?listing_id=<?php echo $listing['id']; ?>" class="btn btn-primary" style="display:block; text-align:center;">
                    Make a Booking
                </a>
            </div>
            <?php } ?>

        </div>

    </div>

</div>

</body>
</html>
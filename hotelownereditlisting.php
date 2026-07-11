<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['hotel_owner']);

require_once 'Classes/DBconnection.php';

$listingId = $_GET['id'];
$userId    = $_SESSION['user_id'];
$conn      = DBconnection::getInstance()->getConnection();

$sql  = "SELECT l.*, hl.star_rating, hl.amenities, hl.total_rooms,
          hl.check_in_time, hl.check_out_time
          FROM listings l
          JOIN hotel_listings hl ON l.id = hl.listing_id
          WHERE l.id = ? AND l.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $listingId, $userId);
$stmt->execute();
$listing = $stmt->get_result()->fetch_assoc();

if (!$listing) {
    header("Location: hotelownermanagelistings.php?error=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Listing - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validatehotelowneraddlisting.js"></script>
</head>
<body>

<!-- NAVBAR -->
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

<!-- PAGE CONTENT -->
<div class="admin-section">

    <a href="hotelownermanagelistings.php" class="back-link">&larr; Back to My Listings</a>

    <h2 class="admin-page-title">Edit Listing</h2>
    <p class="admin-page-subtitle">Update your hotel listing details</p>

    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <div class="commission-form-card" style="max-width:600px;">
        <form name="hotelowneraddlistingform" method="post" action="hotelownereditlistingbackend.php" onsubmit="return validateHotelOwnerAddListing();">

            <input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>">

            <h3 style="color:#0f2a43; margin-bottom:15px;">General Information</h3>

            <label>Hotel Name</label>
            <input type="text" name="title" value="<?php echo $listing['title']; ?>"readonly style="background-color:#f5f5f5; cursor:not-allowed;">

            <label>Description</label>
            <textarea name="description" rows="4" style="width:100%; padding:10px; border:1px solid #d6d6d6; border-radius:5px; font-size:14px; margin-bottom:15px; resize:vertical;"><?php echo $listing['description']; ?></textarea>

            <label>Address</label>
            <input type="text" name="location" value="<?php echo $listing['location']; ?>"readonly style="background-color:#f5f5f5; cursor:not-allowed;">

            <label>City</label>
			<input type="text" name="city" value="<?php echo $listing['city']; ?>"readonly style="background-color:#f5f5f5; cursor:not-allowed;">
            

            <label>Starting Price Per Night (Rs.)</label>
            <input type="number" name="price" step="0.01" min="0" value="<?php echo $listing['price']; ?>">

            <hr style="border:none; border-top:1px solid #eee; margin:20px 0;">

            <h3 style="color:#0f2a43; margin-bottom:15px;">Hotel Details</h3>

            <label>Star Rating</label>
			<input type="text" name="star_rating" value="<?php echo $listing['star_rating']; ?>"readonly style="background-color:#f5f5f5; cursor:not-allowed;">
            

            <label>Amenities</label>
            <textarea name="amenities" rows="3" readonly style="width:100%; padding:10px; border:1px solid #d6d6d6; border-radius:5px; font-size:14px; margin-bottom:15px; resize:vertical; background-color:#f5f5f5; cursor:not-allowed;"><?php echo htmlspecialchars($listing['amenities']); ?> </textarea>

            <label>Total Number of Rooms</label>
            <input type="number" name="total_rooms" min="1" value="<?php echo $listing['total_rooms']; ?>"readonly style="background-color:#f5f5f5; cursor:not-allowed;">

            <label>Check-in Time</label>
            <input type="time" name="check_in_time" value="<?php echo $listing['check_in_time']; ?>">

            <label>Check-out Time</label>
            <input type="time" name="check_out_time" value="<?php echo $listing['check_out_time']; ?>">

            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Save Changes</button>

        </form>
    </div>

</div>

</body>
</html>
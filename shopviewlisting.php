<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['shop_owner']);

require_once 'Classes/DBconnection.php';
require_once 'Classes/ListingImage.php';

$listingId = $_GET['id'];
$userId    = $_SESSION['user_id'];
$conn      = DBconnection::getInstance()->getConnection();

$sql  = "SELECT l.*, sl.shop_type, sl.open_time, sl.close_time
          FROM listings l
          JOIN shop_listings sl ON l.id = sl.listing_id
          WHERE l.id = ? AND l.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $listingId, $userId);
$stmt->execute();
$listing = $stmt->get_result()->fetch_assoc();

if (!$listing) {
    header("Location: shopmanagelistings.php");
    exit();
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

<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="shopownerdashboard.php"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <span class="partner-badge">Shop Owner</span>
            </td>
            <td class="nav-buttons-cell">
                <span class="admin-welcome">Welcome, <?php echo $_SESSION['firstname']; ?></span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<div class="admin-section">

    <a href="shopmanagelistings.php" class="back-link">&larr; Back to My Listings</a>

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
                <h3 class="admin-subsection-title">Shop Details</h3>
                <table class="detail-table">
                    <tr><td>Shop Type</td><td><?php echo $listing['shop_type']; ?></td></tr>
                    <tr><td>Location</td><td><?php echo $listing['location']; ?></td></tr>
                    <tr><td>Opening Time</td><td><?php echo date('h:i A', strtotime($listing['open_time'])); ?></td></tr>
                    <tr><td>Closing Time</td><td><?php echo date('h:i A', strtotime($listing['close_time'])); ?></td></tr>
                </table>
            </div>

        </div>

        <div class="listing-detail-sidebar">
            <div class="sidebar-card">
                <h3 class="admin-subsection-title">Quick Actions</h3>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <a href="shopeditlisting.php?id=<?php echo $listing['id']; ?>"
                       class="btn btn-primary" style="text-align:center;">Edit Listing</a>
                    <a href="shopdeletelistingbackend.php?id=<?php echo $listing['id']; ?>"
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
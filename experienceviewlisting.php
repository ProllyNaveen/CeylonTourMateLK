<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['experience_provider']);

require_once 'Classes/DBconnection.php';
require_once 'Classes/ListingImage.php';
require_once 'Classes/Review.php';

$listingId = $_GET['id'];
$userId    = $_SESSION['user_id'];
$conn      = DBconnection::getInstance()->getConnection();

$sql  = "SELECT l.*, el.experience_type, el.duration, el.max_group_size, el.availability
          FROM listings l
          JOIN experience_listings el ON l.id = el.listing_id
          WHERE l.id = ? AND l.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $listingId, $userId);
$stmt->execute();
$listing = $stmt->get_result()->fetch_assoc();

if (!$listing) {
    header("Location: experiencemanagelistings.php");
    exit();
}

$images  = ListingImage::getImagesByListing($listingId);
$reviews = Review::getReviewsByListing($listingId);
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
                <a href="experiencedashboard.php"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <span class="partner-badge experience-badge">Experience Provider</span>
            </td>
            <td class="nav-buttons-cell">
                <span class="admin-welcome">Welcome, <?php echo $_SESSION['firstname']; ?></span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<div class="admin-section">

    <a href="experiencemanagelistings.php" class="back-link">&larr; Back to My Listings</a>

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
            <span style="font-size:13px; color:#6b6b6b;">per person</span>
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
                <h3 class="admin-subsection-title">Experience Details</h3>
                <table class="detail-table">
                    <tr><td>Type</td><td><?php echo $listing['experience_type']; ?></td></tr>
                    <tr><td>Duration</td><td><?php echo $listing['duration']; ?></td></tr>
                    <tr><td>Max Group Size</td><td><?php echo $listing['max_group_size']; ?> persons</td></tr>
                    <tr><td>Availability</td><td><?php echo $listing['availability']; ?></td></tr>
                    <tr><td>Location</td><td><?php echo $listing['location']; ?></td></tr>
                </table>
            </div>

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

        <div class="listing-detail-sidebar">
            <div class="sidebar-card">
                <h3 class="admin-subsection-title">Quick Actions</h3>
                <div style="display:flex; flex-direction:column; gap:10px;">
                    <a href="experienceeditlisting.php?id=<?php echo $listing['id']; ?>"
                       class="btn btn-primary" style="text-align:center;">Edit Listing</a>
                    <a href="experiencedeletelistingbackend.php?id=<?php echo $listing['id']; ?>"
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
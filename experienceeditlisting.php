<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['experience_provider']);

require_once 'Classes/DBconnection.php';

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
    header("Location: experiencemanagelistings.php?error=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Listing - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validateexperienceaddlisting.js"></script>
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

    <h2 class="admin-page-title">Edit Listing</h2>
    <p class="admin-page-subtitle">Update your experience listing details</p>

    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <div class="commission-form-card" style="max-width:600px;">
        <form name="experienceaddlistingform" method="post" action="experienceeditlistingbackend.php" onsubmit="return validateExperienceAddListing();">

            <input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>">

            <h3 style="color:#0f2a43; margin-bottom:15px;">General Information</h3>

            <label>Experience Title</label>
            <input type="text" name="title" value="<?php echo $listing['title']; ?>"readonly style="background-color:#f5f5f5; cursor:not-allowed;">

            <label>Description</label>
            <textarea name="description" rows="4" style="width:100%; padding:10px; border:1px solid #d6d6d6; border-radius:5px; font-size:14px; margin-bottom:15px; resize:vertical;"><?php echo $listing['description']; ?></textarea>

            <label>Location / Meeting Point</label>
            <input type="text" name="location" value="<?php echo $listing['location']; ?>">

            <label>City</label>
			<input type="text" name="city" value="<?php echo $listing['city']; ?>"readonly style="background-color:#f5f5f5; cursor:not-allowed;">
            

            <label>Price Per Person (Rs.)</label>
            <input type="number" name="price" step="0.01" min="0" value="<?php echo $listing['price']; ?>">

            <hr style="border:none; border-top:1px solid #eee; margin:20px 0;">

            <h3 style="color:#0f2a43; margin-bottom:15px;">Experience Details</h3>

            <label>Experience Type</label>
			<input type="text" name="experience_type" value="<?php echo $listing['experience_type']; ?>"readonly style="background-color:#f5f5f5; cursor:not-allowed;">
            

            <label>Duration</label>
            <select name="duration">
                <?php
                $durations = ['1 hour','2 hours','3 hours','Half day','Full day','2 days','3 days','Custom'];
                foreach($durations as $duration) {
                    $selected = ($listing['duration'] == $duration) ? 'selected' : '';
                    echo "<option value='$duration' $selected>$duration</option>";
                }
                ?>
            </select>

            <label>Maximum Group Size</label>
            <input type="number" name="max_group_size" min="1" value="<?php echo $listing['max_group_size']; ?>">

            <label>Availability</label>
            <select name="availability">
                <?php
                $avails = ['Daily','Weekdays only','Weekends only','By appointment'];
                foreach($avails as $avail) {
                    $selected = ($listing['availability'] == $avail) ? 'selected' : '';
                    echo "<option value='$avail' $selected>$avail</option>";
                }
                ?>
            </select>

            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Save Changes</button>

        </form>
    </div>

</div>

</body>
</html>
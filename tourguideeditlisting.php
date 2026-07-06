<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['tour_guide']);

require_once 'Classes/DBconnection.php';

$listingId = $_GET['id'];
$userId    = $_SESSION['user_id'];
$conn      = DBconnection::getInstance()->getConnection();

$sql  = "SELECT l.*, tl.languages, tl.specialization, tl.experience_years, tl.availability
          FROM listings l
          JOIN tourguide_listings tl ON l.id = tl.listing_id
          WHERE l.id = ? AND l.user_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $listingId, $userId);
$stmt->execute();
$listing = $stmt->get_result()->fetch_assoc();

if (!$listing) {
    header("Location: tourguidemanagelistings.php?error=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Listing - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validatetourguideaddlisting.js"></script>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="tourguidedashboard.php"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <span class="partner-badge tourguide-badge">Tour Guide</span>
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

    <a href="tourguidemanagelistings.php" class="back-link">&larr; Back to My Listings</a>

    <h2 class="admin-page-title">Edit Listing</h2>
    <p class="admin-page-subtitle">Update your tour guide listing details</p>

    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <div class="commission-form-card" style="max-width:600px;">
        <form name="tourguideaddlistingform" method="post" action="tourguideeditlistingbackend.php" onsubmit="return validateTourGuideAddListing();">

            <input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>">

            <h3 style="color:#0f2a43; margin-bottom:15px;">General Information</h3>

            <label>Tour Title</label>
            <input type="text" name="title" value="<?php echo $listing['title']; ?>">

            <label>Description</label>
            <textarea name="description" rows="4" style="width:100%; padding:10px; border:1px solid #d6d6d6; border-radius:5px; font-size:14px; margin-bottom:15px; resize:vertical;"><?php echo $listing['description']; ?></textarea>

            <label>Meeting Point / Location</label>
            <input type="text" name="location" value="<?php echo $listing['location']; ?>">

            <label>City</label>
            <select name="city">
                <option value="">-- Select City --</option>
                <?php
                $cities = ['Colombo','Galle','Kandy','Jaffna','Negombo','Trincomalee',
                           'Batticaloa','Anuradhapura','Polonnaruwa','Matara','Kurunegala',
                           'Ratnapura','Badulla','Nuwara Eliya','Hambantota','Other'];
                foreach($cities as $city) {
                    $selected = ($listing['city'] == $city) ? 'selected' : '';
                    echo "<option value='$city' $selected>$city</option>";
                }
                ?>
            </select>

            <label>Price Per Person (Rs.)</label>
            <input type="number" name="price" step="0.01" min="0" value="<?php echo $listing['price']; ?>">

            <hr style="border:none; border-top:1px solid #eee; margin:20px 0;">

            <h3 style="color:#0f2a43; margin-bottom:15px;">Tour Guide Details</h3>

            <label>Languages Spoken</label>
            <input type="text" name="languages" value="<?php echo $listing['languages']; ?>">

            <label>Specialization</label>
            <select name="specialization">
                <?php
                $specs = ['Cultural & Heritage','Wildlife & Nature','Adventure','Historical',
                          'Religious','Beach & Coastal','City Tours','Photography','Food & Cuisine','General'];
                foreach($specs as $spec) {
                    $selected = ($listing['specialization'] == $spec) ? 'selected' : '';
                    echo "<option value='$spec' $selected>$spec</option>";
                }
                ?>
            </select>

            <label>Years of Experience</label>
            <input type="number" name="experience_years" min="0" value="<?php echo $listing['experience_years']; ?>">

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
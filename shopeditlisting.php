<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['shop_owner']);

require_once 'Classes/DBconnection.php';

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
    header("Location: shopmanagelistings.php?error=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Listing - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validateshopaddlisting.js"></script>
</head>
<body>

<!-- NAVBAR -->
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

<!-- PAGE CONTENT -->
<div class="admin-section">

    <a href="shopmanagelistings.php" class="back-link">&larr; Back to My Listings</a>

    <h2 class="admin-page-title">Edit Listing</h2>
    <p class="admin-page-subtitle">Update your shop listing details</p>

    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <div class="commission-form-card" style="max-width:600px;">
        <form name="shopaddlistingform" method="post" action="shopeditlistingbackend.php" onsubmit="return validateShopAddListing();">

            <input type="hidden" name="listing_id" value="<?php echo $listing['id']; ?>">

            <h3 style="color:#0f2a43; margin-bottom:15px;">General Information</h3>

            <label>Shop Name / Title</label>
            <input type="text" name="title" value="<?php echo $listing['title']; ?>">

            <label>Description</label>
            <textarea name="description" rows="4" style="width:100%; padding:10px; border:1px solid #d6d6d6; border-radius:5px; font-size:14px; margin-bottom:15px; resize:vertical;"><?php echo $listing['description']; ?></textarea>

            <label>Location / Address</label>
            <input type="text" name="location" value="<?php echo $listing['location']; ?>">

            <label>City</label>
            <select name="city">
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

            <label>Price (Rs.)</label>
            <input type="number" name="price" step="0.01" min="0" value="<?php echo $listing['price']; ?>">

            <hr style="border:none; border-top:1px solid #eee; margin:20px 0;">

            <h3 style="color:#0f2a43; margin-bottom:15px;">Shop Details</h3>

            <label>Shop Type</label>
            <select name="shop_type">
                <?php
                $shopTypes = ['Souvenir','Clothing','Grocery','Jewellery','Spices',
                              'Handicrafts','Electronics','Pharmacy','Other'];
                foreach($shopTypes as $type) {
                    $selected = ($listing['shop_type'] == $type) ? 'selected' : '';
                    echo "<option value='$type' $selected>$type</option>";
                }
                ?>
            </select>

            <label>Opening Time</label>
            <input type="time" name="open_time" value="<?php echo $listing['open_time']; ?>">

            <label>Closing Time</label>
            <input type="time" name="close_time" value="<?php echo $listing['close_time']; ?>">

            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Save Changes</button>

        </form>
    </div>

</div>

</body>
</html>
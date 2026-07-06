<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['hotel_owner']);

require_once 'Classes/DBconnection.php';

$roomId         = $_GET['id'];
$hotelListingId = $_GET['hotel_listing_id'];
$conn           = DBconnection::getInstance()->getConnection();

$sql  = "SELECT * FROM rooms WHERE id = ? AND hotel_listing_id = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ii", $roomId, $hotelListingId);
$stmt->execute();
$room = $stmt->get_result()->fetch_assoc();

if (!$room) {
    header("Location: hotelownermanagerooms.php?hotel_listing_id=" . $hotelListingId . "&error=1");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Edit Room - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validatehotelownerrooms.js"></script>
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

    <a href="hotelownermanagerooms.php?hotel_listing_id=<?php echo $hotelListingId; ?>" class="back-link">&larr; Back to Rooms</a>

    <h2 class="admin-page-title">Edit Room</h2>
    <p class="admin-page-subtitle">Update this room's details</p>

    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <div class="commission-form-card" style="max-width:550px;">
        <form name="addroomform" method="post" action="hotelownereditroombackend.php" onsubmit="return validateAddRoom();">

            <input type="hidden" name="room_id" value="<?php echo $room['id']; ?>">
            <input type="hidden" name="hotel_listing_id" value="<?php echo $hotelListingId; ?>">

            <label>Room Type</label>
            <select name="room_type">
                <?php
                $roomTypes = ['single','double','suite','family'];
                foreach($roomTypes as $type) {
                    $selected = ($room['room_type'] == $type) ? 'selected' : '';
                    echo "<option value='$type' $selected>" . ucfirst($type) . "</option>";
                }
                ?>
            </select>

            <label>Description</label>
            <textarea name="description" rows="3" style="width:100%; padding:10px; border:1px solid #d6d6d6; border-radius:5px; font-size:14px; margin-bottom:15px; resize:vertical;"><?php echo $room['description']; ?></textarea>

            <label>Price Per Night (Rs.)</label>
            <input type="number" name="price_per_night" step="0.01" min="0" value="<?php echo $room['price_per_night']; ?>">

            <label>Capacity (persons)</label>
            <input type="number" name="capacity" min="1" value="<?php echo $room['capacity']; ?>">

            <button type="submit" class="btn btn-primary" style="margin-top:10px;">Save Changes</button>

        </form>
    </div>

</div>

</body>
</html>
<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['hotel_owner']);

require_once 'Classes/DBconnection.php';
require_once 'Classes/Room.php';

$hotelListingId = $_GET['hotel_listing_id'];
$userId         = $_SESSION['user_id'];
$conn           = DBconnection::getInstance()->getConnection();


$chkSql  = "SELECT l.title FROM hotel_listings hl
             JOIN listings l ON hl.listing_id = l.id
             WHERE hl.id = ? AND l.user_id = ?";
$chkStmt = $conn->prepare($chkSql);
$chkStmt->bind_param("ii", $hotelListingId, $userId);
$chkStmt->execute();
$chkResult = $chkStmt->get_result();

if ($chkResult->num_rows == 0) {
    header("Location: hotelownermanagelistings.php?error=1");
    exit();
}

$hotelTitle = $chkResult->fetch_assoc()['title'];
$rooms      = Room::getRoomsByHotelListing($hotelListingId);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Rooms - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validatehotelownerrooms.js"></script>
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
                <span class="admin-welcome">Welcome, <?php echo htmlspecialchars($_SESSION['firstname']); ?></span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>


<div class="admin-section">

    <a href="hotelownermanagelistings.php" class="back-link">&larr; Back to My Listings</a>

    <h2 class="admin-page-title">Manage Rooms</h2>
    <p class="admin-page-subtitle">Manage rooms for: <strong><?php echo $hotelTitle; ?></strong></p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Action completed successfully.</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    
    <h3 class="admin-subsection-title">Add New Room</h3>

    <div class="commission-form-card" style="max-width:550px; margin-bottom:40px;">
        <form name="addroomform" method="post" action="hotelowneraddroombackend.php" onsubmit="return validateAddRoom();">

            
            <input type="hidden" name="hotel_listing_id" value="<?php echo $_GET['hotel_listing_id']; ?>">

            <label>Room Type</label>
            <select name="room_type">
                <option value="">-- Select Room Type --</option>
                <option value="single">Single</option>
                <option value="double">Double</option>
                <option value="suite">Suite</option>
                <option value="family">Family</option>
            </select>

            <label>Description</label>
            <textarea name="description" placeholder="Describe this room type..." rows="3" style="width:100%; padding:10px; border:1px solid #d6d6d6; border-radius:5px; font-size:14px; margin-bottom:15px; resize:vertical;"></textarea>

            <label>Price Per Night (Rs.)</label>
            <input type="number" name="price_per_night" placeholder="e.g. 8000.00" step="0.01" min="0">

            <label>Capacity (persons)</label>
            <input type="number" name="capacity" placeholder="e.g. 2" min="1">

            <button type="submit" class="btn btn-primary" style="margin-top:10px;">Add Room</button>

        </form>
    </div>

    
    <h3 class="admin-subsection-title">Existing Rooms</h3>

    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Room Type</th>
            <th>Description</th>
            <th>Price Per Night (Rs.)</th>
            <th>Capacity</th>
            <th>Available</th>
            <th>Actions</th>
        </tr>

        <?php if(empty($rooms)) { ?>
    <tr>
        <td colspan="7" class="table-empty">No rooms added yet</td>
    </tr>
<?php } else { ?>
    <?php foreach($rooms as $room) { ?>
    <tr>
        <td><?php echo $room['id']; ?></td>
        <td><?php echo ucfirst($room['room_type']); ?></td>
        <td><?php echo $room['description']; ?></td>
        <td>Rs. <?php echo number_format($room['price_per_night'], 2); ?></td>
        <td><?php echo $room['capacity']; ?> persons</td>
        <td>
            <?php if($room['is_available'] == 1) { ?>
                <span class="status-badge status-confirmed">Yes</span>
            <?php } else { ?>
                <span class="status-badge status-cancelled">No</span>
            <?php } ?>
        </td>
        <td>
            <div class="action-btns">
                <?php if($room['is_available'] == 1) { ?>
                    <a href="hotelownerroomavailbackend.php?id=<?php echo $room['id']; ?>&action=unavailable&hotel_listing_id=<?php echo $hotelListingId; ?>"
                       class="tbl-btn tbl-btn-amber">Mark Unavailable</a>
                <?php } else { ?>
                    <a href="hotelownerroomavailbackend.php?id=<?php echo $room['id']; ?>&action=available&hotel_listing_id=<?php echo $hotelListingId; ?>"
                       class="tbl-btn tbl-btn-green">Mark Available</a>
                <?php } ?>
                <a href="hotelownereditroom.php?id=<?php echo $room['id']; ?>&hotel_listing_id=<?php echo $hotelListingId; ?>"
                   class="tbl-btn tbl-btn-amber">Edit</a>
                <a href="hotelownerdeleteroombackend.php?id=<?php echo $room['id']; ?>&hotel_listing_id=<?php echo $hotelListingId; ?>"
                   class="tbl-btn tbl-btn-red"
                   onclick="return confirm('Are you sure you want to delete this room?')">Delete</a>
            </div>
        </td>
    </tr>
    <?php } ?>
<?php } ?>

    </table>

</div>

</body>
</html>
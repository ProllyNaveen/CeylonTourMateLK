<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Manage Rooms - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validatehotelownerrooms.js"></script>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="hotelownerdashboard.html"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <span class="partner-badge hotel-badge">Hotel Owner</span>
            </td>
            <td class="nav-buttons-cell">
                <span class="admin-welcome">Welcome, Owner</span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

    <a href="hotelownermanagelistings.html" class="back-link">&larr; Back to My Listings</a>

    <h2 class="admin-page-title">Manage Rooms</h2>
    <!-- PHP: echo "for " . $listing['title']; -->
    <p class="admin-page-subtitle">Add, edit or remove rooms for this hotel listing</p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Action completed successfully.</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <!-- ADD NEW ROOM -->
    <h3 class="admin-subsection-title">Add New Room</h3>

    <div class="commission-form-card" style="max-width:550px; margin-bottom:40px;">
        <form name="addroomform" method="post" action="hotelowneraddroombackend.php" onsubmit="return validateAddRoom();">

            <!-- PHP: echo hidden hotel_listing_id from $_GET['hotel_listing_id'] -->
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

    <!-- EXISTING ROOMS TABLE -->
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

        <!-- PHP:
            $sql = "SELECT * FROM rooms WHERE hotel_listing_id = ?
                    ORDER BY room_type ASC";
            loop through results and echo each row
        -->

        <!-- Example populated row: -->
        <!--
        <tr>
            <td><?php echo $room['id']; ?></td>
            <td><?php echo ucfirst($room['room_type']); ?></td>
            <td><?php echo $room['description']; ?></td>
            <td><?php echo number_format($room['price_per_night'], 2); ?></td>
            <td><?php echo $room['capacity']; ?></td>
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
                        <a href="hotelownerroomavailbackend.php?id=<?php echo $room['id']; ?>&action=unavailable&hotel_listing_id=<?php echo $_GET['hotel_listing_id']; ?>"
                           class="tbl-btn tbl-btn-amber">Mark Unavailable</a>
                    <?php } else { ?>
                        <a href="hotelownerroomavailbackend.php?id=<?php echo $room['id']; ?>&action=available&hotel_listing_id=<?php echo $_GET['hotel_listing_id']; ?>"
                           class="tbl-btn tbl-btn-green">Mark Available</a>
                    <?php } ?>
                    <a href="hotelownereditroom.php?id=<?php echo $room['id']; ?>&hotel_listing_id=<?php echo $_GET['hotel_listing_id']; ?>"
                       class="tbl-btn tbl-btn-amber">Edit</a>
                    <a href="hotelownerdeleteroombackend.php?id=<?php echo $room['id']; ?>&hotel_listing_id=<?php echo $_GET['hotel_listing_id']; ?>"
                       class="tbl-btn tbl-btn-red"
                       onclick="return confirm('Are you sure you want to delete this room?')">Delete</a>
                </div>
            </td>
        </tr>
        -->

        <tr>
            <td colspan="7" class="table-empty">No rooms added yet</td>
        </tr>

    </table>

</div>

</body>
</html>
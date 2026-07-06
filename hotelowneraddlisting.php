<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['hotel_owner']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Listing - CeylonTourMate.lk</title>
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
                <span class="admin-welcome">Welcome, Owner</span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

    <a href="hotelownerdashboard.php" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">Add New Listing</h2>
    <p class="admin-page-subtitle">Submit a new hotel listing for admin approval</p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Listing submitted successfully. Waiting for admin approval.</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <div class="commission-form-card" style="max-width:600px;">
        <form name="hotelowneraddlistingform" method="post" action="hotelowneraddlistingbackend.php" enctype="multipart/form-data" onsubmit="return validateHotelOwnerAddListing();">

            <h3 style="color:#0f2a43; margin-bottom:15px;">General Information</h3>

            <label>Hotel Name</label>
            <input type="text" name="title" placeholder="Enter your hotel name">

            <label>Description</label>
            <textarea name="description" placeholder="Describe your hotel, facilities and services..." rows="4" style="width:100%; padding:10px; border:1px solid #d6d6d6; border-radius:5px; font-size:14px; margin-bottom:15px; resize:vertical;"></textarea>

            <label>Address</label>
            <input type="text" name="location" placeholder="Enter full address">

            <label>City</label>
            <select name="city">
                <option value="">-- Select City --</option>
                <option value="Colombo">Colombo</option>
                <option value="Galle">Galle</option>
                <option value="Kandy">Kandy</option>
                <option value="Jaffna">Jaffna</option>
                <option value="Negombo">Negombo</option>
                <option value="Trincomalee">Trincomalee</option>
                <option value="Batticaloa">Batticaloa</option>
                <option value="Anuradhapura">Anuradhapura</option>
                <option value="Polonnaruwa">Polonnaruwa</option>
                <option value="Matara">Matara</option>
                <option value="Kurunegala">Kurunegala</option>
                <option value="Ratnapura">Ratnapura</option>
                <option value="Badulla">Badulla</option>
                <option value="Nuwara Eliya">Nuwara Eliya</option>
                <option value="Hambantota">Hambantota</option>
                <option value="Other">Other</option>
            </select>

            <label>Starting Price Per Night (Rs.)</label>
            <input type="number" name="price" placeholder="e.g. 5000.00" step="0.01" min="0">

            <hr style="border:none; border-top:1px solid #eee; margin:20px 0;">

            <h3 style="color:#0f2a43; margin-bottom:15px;">Hotel Details</h3>

            <label>Star Rating</label>
            <select name="star_rating">
                <option value="">-- Select Star Rating --</option>
                <option value="1">1 Star</option>
                <option value="2">2 Stars</option>
                <option value="3">3 Stars</option>
                <option value="4">4 Stars</option>
                <option value="5">5 Stars</option>
            </select>

            <label>Amenities</label>
            <textarea name="amenities" placeholder="e.g. Free WiFi, Swimming Pool, Restaurant, Gym, Parking..." rows="3" style="width:100%; padding:10px; border:1px solid #d6d6d6; border-radius:5px; font-size:14px; margin-bottom:15px; resize:vertical;"></textarea>

            <label>Total Number of Rooms</label>
            <input type="number" name="total_rooms" placeholder="e.g. 20" min="1">

            <label>Check-in Time</label>
            <input type="time" name="check_in_time">

            <label>Check-out Time</label>
            <input type="time" name="check_out_time">

            <hr style="border:none; border-top:1px solid #eee; margin:20px 0;">

            <h3 style="color:#0f2a43; margin-bottom:15px;">Listing Images</h3>

            <label>Upload Images <span style="font-size:11px; color:#999;">(you can select multiple)</span></label>
            <input type="file" name="images[]" accept="image/*" multiple>

            <button type="submit" class="btn btn-primary" style="margin-top:15px;">Submit Listing</button>

        </form>
    </div>

</div>

</body>
</html>
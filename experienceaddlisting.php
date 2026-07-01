<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Add Listing - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validateexperienceaddlisting.js"></script>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="experiencedashboard.html"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <span class="partner-badge experience-badge">Experience Provider</span>
            </td>
            <td class="nav-buttons-cell">
                <span class="admin-welcome">Welcome, Provider</span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

    <a href="experiencedashboard.html" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">Add New Listing</h2>
    <p class="admin-page-subtitle">Submit a new experience listing for admin approval</p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Listing submitted successfully. Waiting for admin approval.</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <div class="commission-form-card" style="max-width:600px;">
        <form name="experienceaddlistingform" method="post" action="experienceaddlistingbackend.php" enctype="multipart/form-data" onsubmit="return validateExperienceAddListing();">

            <h3 style="color:#0f2a43; margin-bottom:15px;">General Information</h3>

            <label>Experience Title</label>
            <input type="text" name="title" placeholder="Enter your experience title">

            <label>Description</label>
            <textarea name="description" placeholder="Describe your experience in detail..." rows="4" style="width:100%; padding:10px; border:1px solid #d6d6d6; border-radius:5px; font-size:14px; margin-bottom:15px; resize:vertical;"></textarea>

            <label>Location / Meeting Point</label>
            <input type="text" name="location" placeholder="Enter meeting point or address">

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

            <label>Price Per Person (Rs.)</label>
            <input type="number" name="price" placeholder="e.g. 2500.00" step="0.01" min="0">

            <hr style="border:none; border-top:1px solid #eee; margin:20px 0;">

            <h3 style="color:#0f2a43; margin-bottom:15px;">Experience Details</h3>

            <label>Experience Type</label>
            <select name="experience_type">
                <option value="">-- Select Experience Type --</option>
                <option value="Adventure">Adventure</option>
                <option value="Cultural">Cultural</option>
                <option value="Wildlife">Wildlife</option>
                <option value="Water Sports">Water Sports</option>
                <option value="Hiking">Hiking & Trekking</option>
                <option value="Cooking">Cooking Class</option>
                <option value="Wellness">Wellness & Yoga</option>
                <option value="Photography">Photography Tour</option>
                <option value="Historical">Historical Tour</option>
                <option value="Other">Other</option>
            </select>

            <label>Duration</label>
            <select name="duration">
                <option value="">-- Select Duration --</option>
                <option value="1 hour">1 Hour</option>
                <option value="2 hours">2 Hours</option>
                <option value="3 hours">3 Hours</option>
                <option value="Half day">Half Day</option>
                <option value="Full day">Full Day</option>
                <option value="2 days">2 Days</option>
                <option value="3 days">3 Days</option>
                <option value="Custom">Custom</option>
            </select>

            <label>Maximum Group Size</label>
            <input type="number" name="max_group_size" placeholder="e.g. 10" min="1">

            <label>Availability</label>
            <select name="availability">
                <option value="">-- Select Availability --</option>
                <option value="Daily">Daily</option>
                <option value="Weekdays only">Weekdays Only</option>
                <option value="Weekends only">Weekends Only</option>
                <option value="By appointment">By Appointment</option>
            </select>

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
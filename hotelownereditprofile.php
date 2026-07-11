<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['hotel_owner']);

require_once 'Classes/DBconnection.php';

$conn   = DBconnection::getInstance()->getConnection();
$sql    = "SELECT * FROM users WHERE id = ?";
$stmt   = $conn->prepare($sql);
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$user   = $stmt->get_result()->fetch_assoc();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validatehotelownereditprofile.js"></script>
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
                <span class="admin-welcome">Welcome, <?php echo htmlspecialchars($_SESSION['firstname']); ?></span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

    <a href="hotelownerdashboard.php" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">My Profile</h2>
    <p class="admin-page-subtitle">Update your account details</p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Profile updated successfully.</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>
    <?php if(isset($_GET['exists'])) { ?>
        <p class="error">Username or email already taken.</p>
    <?php } ?>
    <?php if(isset($_GET['wrongpassword'])) { ?>
        <p class="error">Current password is incorrect.</p>
    <?php } ?>

    <!-- PROFILE PICTURE -->
    <div class="profile-pic-section">
        <img src="<?php echo $user['profile_pic']; ?>" alt="Profile Picture" class="profile-pic-preview" id="profilePicPreview">
        <p class="form-note">Click below to change your profile picture</p>
    </div>

    <!-- EDIT PROFILE FORM -->
    <div class="commission-form-card" style="max-width:550px;">
        <form name="hotelownereditprofileform" method="post" action="hotelownereditprofilebackend.php" enctype="multipart/form-data" onsubmit="return validateHotelOwnerEditProfile();">

            <label>First Name</label>
            <input type="text" name="firstname" placeholder="Enter first name" value="<?php echo $user['firstname']; ?>">

            <label>Last Name</label>
            <input type="text" name="lastname" placeholder="Enter last name" value="<?php echo $user['lastname']; ?>">

            <label>Username</label>
            <input type="text" name="username" placeholder="Enter username" value="<?php echo $user['username']; ?>">
            <label>Email</label>
            <input type="email" name="email" placeholder="Enter email" value="<?php echo $user['email']; ?>">

            <label>Phone</label>
            <input type="text" name="phone" placeholder="Enter phone number" value="<?php echo $user['phone']; ?>">

            <label>Profile Picture</label>
            <input type="file" name="profile_pic" accept="image/*" onchange="previewImage(this)">

            <hr style="border:none; border-top:1px solid #eee; margin:20px 0;">

            <p class="form-note" style="margin-bottom:15px;">Leave password fields empty if you don't want to change your password.</p>

            <label>Current Password</label>
            <input type="password" name="current_password" placeholder="Enter current password">

            <label>New Password</label>
            <input type="password" name="new_password" placeholder="Enter new password">

            <label>Confirm New Password</label>
            <input type="password" name="confirm_password" placeholder="Confirm new password">

            <button type="submit" class="btn btn-primary" style="margin-top:10px;">Save Changes</button>

        </form>
    </div>

</div>

</body>
</html>
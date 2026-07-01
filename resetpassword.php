<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validateresetpassword.js"></script>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="index.php"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <a href="index.php">Home</a>
                <a href="index.php#explore">Explore</a>
                <a href="index.php#about">About Us</a>
            </td>
            <td class="nav-buttons-cell">
                <a href="login.php"><button class="btn btn-login">Login</button></a>
                <a href="regform.php"><button class="btn btn-signup">Sign Up</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- STEP 3: SET NEW PASSWORD -->
<div class="auth-section">
    <div class="auth-card auth-card-narrow">
        <h2>Set a new password</h2>
        <p class="auth-subtitle">Identity confirmed. Choose a new password for your account.</p>
		<?php if(isset($_GET['error'])) { ?>
		<p class="error">Something went wrong. Please try again.</p>
		<?php } ?>
        <form name="resetform" method="post" action="resetpasswordbackend.php" onsubmit="return validateResetPassword();">

            <label>New Password</label>
            <input type="password" name="new_password">

            <label>Confirm New Password</label>
            <input type="password" name="confirm_password">

            <button type="submit" class="btn btn-primary btn-full">Reset Password</button>
        </form>

        <p class="auth-switch"><a href="login.php">Back to Login</a></p>
    </div>
</div>

</body>
</html>
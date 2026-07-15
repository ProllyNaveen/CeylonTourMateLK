<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Login - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validatelogin.js"></script>
</head>
<body>

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

<div class="auth-section">
    <div class="auth-card auth-card-narrow">
        <h2>Welcome back</h2>
        <p class="auth-subtitle">Login to your CeylonTourMate.lk account</p>

        <?php if(isset($_GET['registered'])) { ?>
            <p class="success">Registration successful! You can now log in.</p>
        <?php } ?>
        <?php if(isset($_GET['pending'])) { ?>
            <p class="success">Registration submitted! Please wait for admin approval before logging in.</p>
        <?php } ?>
        <?php if(isset($_GET['wrongpassword'])) { ?>
            <p class="error">Incorrect password. Please try again.</p>
        <?php } ?>
        <?php if(isset($_GET['notfound'])) { ?>
            <p class="error">No account found with that username or email.</p>
        <?php } ?>
        <?php if(isset($_GET['suspended'])) { ?>
            <p class="error">Your account has been suspended. Please contact admin.</p>
        <?php } ?>
        <?php if(isset($_GET['notapproved'])) { ?>
            <p class="error">Your account is pending admin approval.</p>
        <?php } ?>
        <?php if(isset($_GET['error'])) { ?>
            <p class="error">Something went wrong. Please try again.</p>
        <?php } ?>
		<?php if(isset($_GET['resetdone'])) { ?>
			<p class="success">Password reset successful! You can now log in.</p>
		<?php } ?>
		<?php if(isset($_GET['loggedout'])) { ?>
			<p class="success">You have been logged out successfully.</p>
		<?php } ?>
        <form name="loginform" method="post" action="loginbackend.php" onsubmit="return validateLoginForm();">
            <label>Username or Email</label>
            <input type="text" name="username">
            <label>Password</label>
            <input type="password" name="password">
            <div class="login-extra-row">
                <a href="forgotpassword.php" class="forgot-link">Forgot password?</a>
            </div>
            <button type="submit" class="btn btn-primary btn-full">Login</button>
        </form>
        <p class="auth-switch">Don't have an account? <a href="regform.php">Sign up here</a></p>
    </div>
</div>
</body>
</html>
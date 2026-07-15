<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validateforgotpassword.js"></script>
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
        <h2>Forgot your password?</h2>
        <p class="auth-subtitle">Enter your username or email to find your account</p>
		<?php if(isset($_GET['notfound'])) { ?>
		<p class="error">No account found with that username or email.</p>
		<?php } ?>
		<?php if(isset($_GET['error'])) { ?>
		<p class="error">Something went wrong. Please try again.</p>
		<?php } ?>
        <form name="findaccountform" method="post" action="findaccountbackend.php" onsubmit="return validateFindAccount();">

            <label>Username or Email</label>
            <input type="text" name="username">

            <button type="submit" class="btn btn-primary btn-full">Continue</button>
        </form>

        <p class="auth-switch"><a href="login.php">Back to Login</a></p>
    </div>
</div>

</body>
</html>
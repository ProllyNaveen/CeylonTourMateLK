<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}


if (!isset($_SESSION['reset_user_id'])) {
    header("Location: forgotpassword.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Security Question - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validateanswersecurity.js"></script>
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
        <h2>Answer your security question</h2>
        <p class="auth-subtitle">Confirm your identity to reset your password</p>

        <?php if(isset($_GET['wrong'])) { ?>
            <p class="error">Incorrect answer. Please try again.</p>
        <?php } ?>
        <?php if(isset($_GET['error'])) { ?>
            <p class="error">Something went wrong. Please try again.</p>
        <?php } ?>

        <form name="securityform" method="post" action="checksecuritybackend.php" onsubmit="return validateSecurityAnswer();">
            <label>Security Question</label>
            <p class="security-question-display">
                <?php echo isset($_SESSION['reset_security_question']) ? $_SESSION['reset_security_question'] : ''; ?>
            </p>
            <label>Your Answer</label>
            <input type="text" name="security_answer">
            <button type="submit" class="btn btn-primary btn-full">Verify</button>
        </form>
        <p class="auth-switch"><a href="login.php">Back to Login</a></p>
    </div>
</div>
</body>
</html>
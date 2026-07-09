<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['admin']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Listings Report - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>
<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="admindashboard.php"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell"><span class="admin-badge">Admin Panel</span></td>
            <td class="nav-buttons-cell">
                <span class="admin-welcome">Welcome, <?php echo $_SESSION['firstname']; ?></span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>
<div class="admin-section">
    <a href="reports.php" class="back-link">&larr; Back to Reports</a>
    <h2 class="admin-page-title">Listings Report</h2>
    <p class="admin-page-subtitle">All listings separated by type and status</p>
    <div class="commission-form-card" style="max-width:400px; margin-top:30px;">
        <p style="font-size:14px; color:#2b2b2b; margin-bottom:20px;">
            Click below to generate the latest listings report as a PDF.
        </p>
        <a href="reports/listings_report.php" target="_blank" class="btn btn-primary" style="display:block; text-align:center;">
            &#127968; Generate Listings Report PDF
        </a>
    </div>
</div>
</body>
</html>
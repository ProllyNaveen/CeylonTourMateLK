<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reports - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
</head>
<body>

<!-- ADMIN NAVBAR -->
<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="admindashboard.php"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <span class="admin-badge">Admin Panel</span>
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

    <a href="admindashboard.php" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">Reports</h2>
    <p class="admin-page-subtitle">Generate and view platform reports</p>

    <div class="action-grid">

        <a href="report_revenue.php" class="action-card">
            <div class="action-icon">&#128176;</div>
            <h3>Revenue Report</h3>
            <p>View total commission earned and listing fees collected by date range</p>
        </a>

        <a href="report_bookings.php" class="action-card">
            <div class="action-icon">&#128197;</div>
            <h3>Bookings Report</h3>
            <p>View bookings filtered by date range, listing type and status</p>
        </a>

        <a href="report_users.php" class="action-card">
            <div class="action-icon">&#128101;</div>
            <h3>Users Report</h3>
            <p>View registered users by role and date range</p>
        </a>

        <a href="report_listings.php" class="action-card">
            <div class="action-icon">&#127968;</div>
            <h3>Listings Report</h3>
            <p>View listings by type, status and approval date</p>
        </a>

        <a href="report_unpaidshops.php" class="action-card">
            <div class="action-icon">&#9888;&#65039;</div>
            <h3>Unpaid Shop Fees</h3>
            <p>View shop listings that have not paid their monthly listing fee</p>
        </a>

    </div>

</div>

</body>
</html>
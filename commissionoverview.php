<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Commission & Fees - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validatecommission.js"></script>
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
                <span class="admin-welcome">Welcome, Admin</span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

    <a href="admindashboard.php" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">Commission & Fees</h2>
    <p class="admin-page-subtitle">Track platform earnings and manage the commission rate</p>

    <!-- EARNINGS STAT CARDS -->
    <div class="stat-grid">
        <div class="stat-card">
            <div class="stat-icon stat-icon-green">&#128176;</div>
            <div class="stat-info">
                <!-- PHP: echo SUM(commission_amount) FROM bookings -->
                <span class="stat-number">Rs. 0</span>
                <span class="stat-label">Total Commission from Bookings</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-teal">&#128181;</div>
            <div class="stat-info">
                <!-- PHP: echo SUM(amount) FROM payments WHERE type='listing_fee' AND payment_status='completed' -->
                <span class="stat-number">Rs. 0</span>
                <span class="stat-label">Total Listing Fees Collected</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-amber">&#128202;</div>
            <div class="stat-info">
                <!-- PHP: echo current rate FROM commission ORDER BY updated_at DESC LIMIT 1 -->
                <span class="stat-number">5%</span>
                <span class="stat-label">Current Commission Rate</span>
            </div>
        </div>
    </div>

    <!-- CHANGE COMMISSION RATE -->
    <h3 class="admin-subsection-title admin-section-spacer">Change Commission Rate</h3>

    <div class="commission-form-card">
        <?php if(isset($_GET['success'])) { ?>
            <p class="success">Commission rate updated successfully!</p>
        <?php } ?>
        <?php if(isset($_GET['error'])) { ?>
            <p class="error">Please enter a valid rate between 0 and 100.</p>
        <?php } ?>

        <form name="commissionform" method="post" action="changecommissionratebackend.php" onsubmit="return validateCommissionRate();">
            <label>New Commission Rate (%)</label>
            <input type="number" name="rate" step="0.01" min="0" max="100" placeholder="e.g. 5.00">
            <button type="submit" class="btn btn-primary">Update Rate</button>
        </form>

        <p class="form-note">This rate applies to all new bookings from now on. Existing bookings keep the commission amount they were charged at the time.</p>
    </div>

    <!-- COMMISSION RATE HISTORY -->
    <h3 class="admin-subsection-title admin-section-spacer">Rate Change History</h3>

    <table class="admin-table">
        <tr>
            <th>Rate</th>
            <th>Changed By</th>
            <th>Date</th>
        </tr>
        <!-- PHP: loop through commission table ORDER BY updated_at DESC -->
        <tr>
            <td colspan="3" class="table-empty">No commission history yet</td>
        </tr>
        <!--
        Example of a populated row:
        <tr>
            <td>5.00%</td>
            <td><?php echo $admin_firstname; ?></td>
            <td><?php echo $updated_at; ?></td>
        </tr>
        -->
    </table>

</div>

</body>
</html>
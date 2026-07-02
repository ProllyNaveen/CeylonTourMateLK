<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['admin']);

require_once 'Classes/User.php';

$role = isset($_GET['role']) ? $_GET['role'] : null;

$user     = new User(null, null, null, null, null, null, null, null, null, null, null, null);
$requests = $user->viewPendingRequests($role);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Partner Registrations - CeylonTourMate.lk</title>
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
                <span class="admin-welcome">Welcome, Admin</span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

    <a href="admindashboard.php" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">Partner Registrations</h2>
    <p class="admin-page-subtitle">Review and approve or reject new partner sign-up requests</p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Action completed successfully.</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <!-- FILTER BY ROLE -->
    <div class="filter-bar">
        <a href="viewpendingrequests.php" class="filter-btn <?php echo !isset($_GET['role']) ? 'active' : ''; ?>">All</a>
        <a href="viewpendingrequests.php?role=tour_guide" class="filter-btn <?php echo (isset($_GET['role']) && $_GET['role']=='tour_guide') ? 'active' : ''; ?>">Tour Guides</a>
        <a href="viewpendingrequests.php?role=hotel_owner" class="filter-btn <?php echo (isset($_GET['role']) && $_GET['role']=='hotel_owner') ? 'active' : ''; ?>">Hotel Owners</a>
        <a href="viewpendingrequests.php?role=shop_owner" class="filter-btn <?php echo (isset($_GET['role']) && $_GET['role']=='shop_owner') ? 'active' : ''; ?>">Shop Owners</a>
        <a href="viewpendingrequests.php?role=experience_provider" class="filter-btn <?php echo (isset($_GET['role']) && $_GET['role']=='experience_provider') ? 'active' : ''; ?>">Experience Providers</a>
    </div>

    <!-- PENDING REQUESTS TABLE -->
    <table class="admin-table">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Phone</th>
        <th>Role</th>
        <th>License</th>
        <th>Joined</th>
        <th>Actions</th>
    </tr>

    <?php if(empty($requests)) { ?>
        <tr>
            <td colspan="9" class="table-empty">No pending registrations</td>
        </tr>
    <?php } else { ?>
        <?php foreach($requests as $req) { ?>
        <tr>
            <td><?php echo $req['id']; ?></td>
            <td><?php echo $req['firstname'] . ' ' . $req['lastname']; ?></td>
            <td><?php echo $req['username']; ?></td>
            <td><?php echo $req['email']; ?></td>
            <td><?php echo $req['phone']; ?></td>
            <td>
                <span class="role-badge role-<?php echo $req['role']; ?>">
                    <?php echo ucfirst(str_replace('_', ' ', $req['role'])); ?>
                </span>
            </td>
            <td>
                <?php if($req['license_image']) { ?>
                    <a href="<?php echo $req['license_image']; ?>" target="_blank" class="tbl-btn tbl-btn-green">View License</a>
                <?php } else { ?>
                    <span style="color:#999; font-size:12px;">N/A</span>
                <?php } ?>
            </td>
            <td><?php echo date('d M Y', strtotime($req['created_at'])); ?></td>
            <td>
                <div class="action-btns">
                    <a href="approveregbackend.php?id=<?php echo $req['id']; ?>&action=approve"
                       class="tbl-btn tbl-btn-green">Approve</a>
                    <a href="approveregbackend.php?id=<?php echo $req['id']; ?>&action=reject"
                       class="tbl-btn tbl-btn-red"
                       onclick="return confirm('Are you sure you want to reject this registration?')">Reject</a>
                </div>
            </td>
        </tr>
        <?php } ?>
    <?php } ?>

</table>

</div>

</body>
</html>
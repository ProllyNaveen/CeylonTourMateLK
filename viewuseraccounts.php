<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['admin']);

require_once 'Classes/User.php';

$role = isset($_GET['role']) ? $_GET['role'] : null;

$user  = new User(null, null, null, null, null, null, null, null, null, null, null, null);
$users = $user->manageUserAccounts($role);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>User Accounts - CeylonTourMate.lk</title>
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

    <h2 class="admin-page-title">User Accounts</h2>
    <p class="admin-page-subtitle">View, suspend or delete tourist and partner accounts</p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Action completed successfully.</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <!-- FILTER BY ROLE -->
    <div class="filter-bar">
        <a href="viewuseraccounts.php" class="filter-btn <?php echo !isset($_GET['role']) ? 'active' : ''; ?>">All</a>
        <a href="viewuseraccounts.php?role=tourist" class="filter-btn <?php echo (isset($_GET['role']) && $_GET['role']=='tourist') ? 'active' : ''; ?>">Tourists</a>
        <a href="viewuseraccounts.php?role=tour_guide" class="filter-btn <?php echo (isset($_GET['role']) && $_GET['role']=='tour_guide') ? 'active' : ''; ?>">Tour Guides</a>
        <a href="viewuseraccounts.php?role=hotel_owner" class="filter-btn <?php echo (isset($_GET['role']) && $_GET['role']=='hotel_owner') ? 'active' : ''; ?>">Hotel Owners</a>
        <a href="viewuseraccounts.php?role=shop_owner" class="filter-btn <?php echo (isset($_GET['role']) && $_GET['role']=='shop_owner') ? 'active' : ''; ?>">Shop Owners</a>
        <a href="viewuseraccounts.php?role=experience_provider" class="filter-btn <?php echo (isset($_GET['role']) && $_GET['role']=='experience_provider') ? 'active' : ''; ?>">Experience Providers</a>
    </div>

    <!-- USERS TABLE -->
    <table class="admin-table">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Role</th>
        <th>Status</th>
        <th>Joined</th>
        <th>Actions</th>
    </tr>

    <?php if(empty($users)) { ?>
        <tr>
            <td colspan="8" class="table-empty">No users found</td>
        </tr>
    <?php } else { ?>
        <?php foreach($users as $u) { ?>
        <tr>
            <td><?php echo $u['id']; ?></td>
            <td><?php echo $u['firstname'] . ' ' . $u['lastname']; ?></td>
            <td><?php echo $u['username']; ?></td>
            <td><?php echo $u['email']; ?></td>
            <td>
                <span class="role-badge role-<?php echo $u['role']; ?>">
                    <?php echo ucfirst(str_replace('_', ' ', $u['role'])); ?>
                </span>
            </td>
            <td>
                <?php if($u['is_suspended'] == 1) { ?>
                    <span class="status-badge status-suspended">Suspended</span>
                <?php } else { ?>
                    <span class="status-badge status-active">Active</span>
                <?php } ?>
            </td>
            <td><?php echo date('d M Y', strtotime($u['created_at'])); ?></td>
            <td>
                <div class="action-btns">
    <a href="viewuserprofile.php?id=<?php echo $u['id']; ?>&from=accounts"
		class="tbl-btn tbl-btn-green">View</a>
    <?php if($u['is_suspended'] == 1) { ?>
        <a href="suspenduserbackend.php?id=<?php echo $u['id']; ?>&action=unsuspend"
           class="tbl-btn tbl-btn-green">Unsuspend</a>
    <?php } else { ?>
        <a href="suspenduserbackend.php?id=<?php echo $u['id']; ?>&action=suspend"
           class="tbl-btn tbl-btn-amber">Suspend</a>
    <?php } ?>
    <a href="deleteuserbackend.php?id=<?php echo $u['id']; ?>"
       class="tbl-btn tbl-btn-red"
       onclick="return confirm('Are you sure you want to delete this user?')">Delete</a>
</div>
            </td>
        </tr>
        <?php } ?>
    <?php } ?>

</table>

</div>

</body>
</html>
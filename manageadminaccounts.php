<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['admin']);

require_once 'Classes/User.php';

$user   = new User(null, null, null, null, null, null, null, null, null, null, null, null);
$admins = $user->manageAdminAccounts();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Accounts - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validateaddadmin.js"></script>
</head>
<body>


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


<div class="admin-section">

    <a href="admindashboard.php" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">Admin Accounts</h2>
    <p class="admin-page-subtitle">Add or remove admin accounts</p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Admin account added successfully.</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>
    <?php if(isset($_GET['exists'])) { ?>
        <p class="error">Username or email already exists.</p>
    <?php } ?>

    
    <h3 class="admin-subsection-title">Add New Admin</h3>

    <div class="commission-form-card">
        <form name="addadminform" method="post" action="addadminbackend.php" onsubmit="return validateAddAdmin();">

            <label>First Name</label>
            <input type="text" name="firstname" placeholder="Enter first name">

            <label>Last Name</label>
            <input type="text" name="lastname" placeholder="Enter last name">

            <label>Username</label>
            <input type="text" name="username" placeholder="Enter username">

            <label>Email</label>
            <input type="email" name="email" placeholder="Enter email">
			<label>Phone</label>
			<input type="text" name="phone" placeholder="Enter phone number">

            <label>Password</label>
            <input type="password" name="password" placeholder="Enter password (min 6 characters)">

            <button type="submit" class="btn btn-primary">Add Admin</button>

        </form>
    </div>

    
    <h3 class="admin-subsection-title admin-section-spacer">Existing Admins</h3>

    <table class="admin-table">
    <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Username</th>
        <th>Email</th>
        <th>Joined</th>
        <th>Actions</th>
    </tr>

    <?php if(empty($admins)) { ?>
        <tr>
            <td colspan="6" class="table-empty">No admins found</td>
        </tr>
    <?php } else { ?>
        <?php foreach($admins as $admin) { ?>
        <tr>
            <td><?php echo $admin['id']; ?></td>
            <td><?php echo $admin['firstname'] . ' ' . $admin['lastname']; ?></td>
            <td><?php echo $admin['username']; ?></td>
            <td><?php echo $admin['email']; ?></td>
            <td><?php echo date('d M Y', strtotime($admin['created_at'])); ?></td>
            <td>
                <?php if($admin['id'] != $_SESSION['user_id']) { ?>
                    <a href="deleteadminbackend.php?id=<?php echo $admin['id']; ?>"
                       class="tbl-btn tbl-btn-red"
                       onclick="return confirm('Are you sure you want to delete this admin?')">Delete</a>
                <?php } else { ?>
                    <span style="color:#999; font-size:12px;">Current Account</span>
                <?php } ?>
            </td>
        </tr>
        <?php } ?>
    <?php } ?>

</table>

</div>

</body>
</html>
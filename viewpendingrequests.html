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
                <a href="admindashboard.html"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
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

    <a href="admindashboard.html" class="back-link">&larr; Back to Dashboard</a>

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

        <!-- PHP:
            if(isset($_GET['role'])) {
                $role = $_GET['role'];
                $sql = "SELECT u.*, tl.license_image, tl.license_number
                        FROM users u
                        LEFT JOIN tourguide_licenses tl ON u.id = tl.user_id
                        WHERE u.is_approved = 0 AND u.role = ?
                        ORDER BY u.created_at DESC";
            } else {
                $sql = "SELECT u.*, tl.license_image, tl.license_number
                        FROM users u
                        LEFT JOIN tourguide_licenses tl ON u.id = tl.user_id
                        WHERE u.is_approved = 0 AND u.role != 'tourist' AND u.role != 'admin'
                        ORDER BY u.created_at DESC";
            }
            loop through results and echo each row
        -->

        <!-- Example populated row: -->
        <!--
        <tr>
            <td><?php echo $user['id']; ?></td>
            <td><?php echo $user['firstname'] . ' ' . $user['lastname']; ?></td>
            <td><?php echo $user['username']; ?></td>
            <td><?php echo $user['email']; ?></td>
            <td><?php echo $user['phone']; ?></td>
            <td><span class="role-badge role-<?php echo $user['role']; ?>"><?php echo ucfirst(str_replace('_', ' ', $user['role'])); ?></span></td>
            <td>
                <?php if($user['license_image']) { ?>
                    <a href="<?php echo $user['license_image']; ?>" target="_blank" class="tbl-btn tbl-btn-green">View License</a>
                <?php } else { ?>
                    <span style="color:#999; font-size:12px;">N/A</span>
                <?php } ?>
            </td>
            <td><?php echo date('d M Y', strtotime($user['created_at'])); ?></td>
            <td>
                <div class="action-btns">
                    <a href="approveregbackend.php?id=<?php echo $user['id']; ?>&action=approve" class="tbl-btn tbl-btn-green">Approve</a>
                    <a href="approveregbackend.php?id=<?php echo $user['id']; ?>&action=reject" class="tbl-btn tbl-btn-red" onclick="return confirm('Are you sure you want to reject this registration?')">Reject</a>
                </div>
            </td>
        </tr>
        -->

        <tr>
            <td colspan="9" class="table-empty">No pending registrations</td>
        </tr>

    </table>

</div>

</body>
</html>
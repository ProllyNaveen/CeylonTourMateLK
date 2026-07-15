<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['admin']);

require_once 'Classes/DBconnection.php';
require_once 'Classes/Notification.php';

$conn     = DBconnection::getInstance()->getConnection();


$usersSql  = "SELECT id, firstname, lastname, username, role FROM users WHERE role != 'admin' ORDER BY firstname ASC";
$usersStmt = $conn->prepare($usersSql);
$usersStmt->execute();
$allUsers  = $usersStmt->get_result()->fetch_all(MYSQLI_ASSOC);


$histSql  = "SELECT n.*, CONCAT(s.firstname, ' ', s.lastname) as sender_name,
              CONCAT(r.firstname, ' ', r.lastname) as receiver_name
              FROM notifications n
              JOIN users s ON n.sender_id = s.id
              JOIN users r ON n.receiver_id = r.id
              ORDER BY n.id DESC";
$histStmt = $conn->prepare($histSql);
$histStmt->execute();
$history  = $histStmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Send Notifications - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validatenotification.js"></script>
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

    <h2 class="admin-page-title">Send Notifications</h2>
    <p class="admin-page-subtitle">Send a notice or warning to platform users</p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Notification sent successfully.</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    
    <div class="commission-form-card" style="max-width: 600px;">
        <form name="notificationform" method="post" action="sendnotificationbackend.php" onsubmit="return validateNotification();">

            <label>Send To</label>
            <select name="send_to" id="send_to" onchange="toggleUserSelect()">
                <option value="">-- Select recipients --</option>
                <option value="all">All Users</option>
                <option value="tourists">All Tourists</option>
                <option value="tour_guides">All Tour Guides</option>
                <option value="hotel_owners">All Hotel Owners</option>
                <option value="shop_owners">All Shop Owners</option>
                <option value="experience_providers">All Experience Providers</option>
                <option value="specific">Specific User</option>
            </select>

            
            <div id="specific-user-div" style="display:none;">
                <label>Select User</label>
                <select name="receiver_id">
                    <option value="">-- Select a user --</option>
                    <?php foreach($allUsers as $u) { ?>
					<option value="<?php echo $u['id']; ?>">
						<?php echo $u['firstname'] . ' ' . $u['lastname'] . ' (' . $u['username'] . ' - ' . str_replace('_', ' ', $u['role']) . ')'; ?>
					</option>
				<?php } ?>
                </select>
            </div>

            <label>Message</label>
            <textarea name="message" placeholder="Enter your notification message here..." rows="5" style="width:100%; padding:10px; border:1px solid #d6d6d6; border-radius:5px; font-size:14px; margin-bottom:15px; resize:vertical;"></textarea>

            <button type="submit" class="btn btn-primary">Send Notification</button>

        </form>
    </div>

    
    <h3 class="admin-subsection-title admin-section-spacer">Sent Notifications History</h3>

    <table class="admin-table">
        <tr>
            <th>ID</th>
            <th>Sent By</th>
            <th>Sent To</th>
            <th>Message</th>
            <th>Read</th>
            <th>Date</th>
        </tr>

        <?php if(empty($history)) { ?>
    <tr>
        <td colspan="6" class="table-empty">No notifications sent yet</td>
    </tr>
<?php } else { ?>
    <?php foreach($history as $notif) { ?>
    <tr>
        <td><?php echo $notif['id']; ?></td>
        <td><?php echo $notif['sender_name']; ?></td>
        <td><?php echo $notif['receiver_name']; ?></td>
        <td><?php echo $notif['message']; ?></td>
        <td>
            <?php if($notif['is_read'] == 1) { ?>
                <span class="status-badge status-confirmed">Read</span>
            <?php } else { ?>
                <span class="status-badge status-pending">Unread</span>
            <?php } ?>
        </td>
        <td><?php echo date('d M Y', strtotime($notif['created_at'])); ?></td>
    </tr>
    <?php } ?>
<?php } ?>

    </table>

</div>

</body>
</html>
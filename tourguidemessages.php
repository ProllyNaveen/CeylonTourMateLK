<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['tour_guide']);

require_once 'Classes/Message.php';

$userId        = $_SESSION['user_id'];
$conversations = Message::getConversations($userId);

$thread = [];
if (isset($_GET['user_id']) && isset($_GET['listing_id'])) {
    $otherUserId = $_GET['user_id'];
    $listingId   = $_GET['listing_id'];
    $thread      = Message::getThread($userId, $otherUserId, $listingId);
    Message::markThreadAsRead($userId, $otherUserId, $listingId);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validatetourguidemessages.js"></script>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="tourguidedashboard.php"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <span class="partner-badge tourguide-badge">Tour Guide</span>
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

    <a href="tourguidedashboard.php" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">Messages</h2>
    <p class="admin-page-subtitle">View and reply to messages from tourists</p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Reply sent successfully.</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>

    <div class="messages-layout">

        <!-- CONVERSATIONS LIST -->
        <div class="conversations-list">
            <h3 class="admin-subsection-title">Conversations</h3>

            <?php if(empty($conversations)) { ?>
    <p class="table-empty" style="padding:20px; text-align:center;">No conversations yet</p>
<?php } else { ?>
    <?php foreach($conversations as $convo) { ?>
    <a href="tourguidemessages.php?user_id=<?php echo $convo['other_user_id']; ?>&listing_id=<?php echo $convo['listing_id']; ?>"
       class="conversation-item <?php echo $convo['unread_count'] > 0 ? 'unread' : ''; ?>">
        <img src="<?php echo $convo['profile_pic'] ?? 'uploads/profiles/default.png'; ?>" class="convo-avatar">
        <div class="convo-info">
            <span class="convo-name"><?php echo $convo['firstname'] . ' ' . $convo['lastname']; ?></span>
            <span class="convo-listing"><?php echo $convo['listing_title']; ?></span>
            <?php if($convo['unread_count'] > 0) { ?>
                <span class="unread-badge"><?php echo $convo['unread_count']; ?></span>
            <?php } ?>
        </div>
    </a>
    <?php } ?>
<?php } ?>

        </div>

        <!-- MESSAGE THREAD -->
        <div class="message-thread">
            <h3 class="admin-subsection-title">
                Select a conversation
            </h3>

            <div class="message-bubble-container" id="messageBubbles">

                <?php if(empty($thread)) { ?>
    <p class="table-empty" style="padding:20px; text-align:center;">No messages to show</p>
<?php } else { ?>
    <?php foreach($thread as $msg) { ?>
    <div class="message-bubble <?php echo $msg['sender_id'] == $userId ? 'bubble-sent' : 'bubble-received'; ?>">
        <p><?php echo $msg['message']; ?></p>
        <span class="bubble-time"><?php echo date('d M Y h:i A', strtotime($msg['created_at'])); ?></span>
    </div>
    <?php } ?>
<?php } ?>

            </div>

            <!-- REPLY FORM -->
            <?php if(isset($_GET['user_id']) && isset($_GET['listing_id'])) { ?>
            <form name="replyform" method="post" action="tourguidereplybackend.php" onsubmit="return validateReply();">
                <input type="hidden" name="receiver_id" value="<?php echo $_GET['user_id']; ?>">
                <input type="hidden" name="listing_id" value="<?php echo $_GET['listing_id']; ?>">
                <div class="reply-bar">
                    <textarea name="message" placeholder="Type your reply..." rows="2" id="replyMessage" style="flex:1; padding:10px; border:1px solid #d6d6d6; border-radius:5px; font-size:14px; resize:none;"></textarea>
                    <button type="submit" class="btn btn-primary" style="margin-left:10px;">Send</button>
                </div>
            </form>
            <?php } ?>

        </div>

    </div>

</div>

</body>
</html>
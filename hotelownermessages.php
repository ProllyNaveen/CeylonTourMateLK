<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Messages - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validatehotelownermessages.js"></script>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="hotelownerdashboard.html"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <span class="partner-badge hotel-badge">Hotel Owner</span>
            </td>
            <td class="nav-buttons-cell">
                <span class="admin-welcome">Welcome, Owner</span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

    <a href="hotelownerdashboard.html" class="back-link">&larr; Back to Dashboard</a>

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

            <!-- PHP:
                $sql = "SELECT m.*, u.firstname, u.lastname, u.profile_pic, l.title as listing_title,
                        COUNT(CASE WHEN m.is_read = 0 AND m.receiver_id = ? THEN 1 END) as unread_count
                        FROM messages m
                        JOIN users u ON (m.sender_id = u.id AND m.sender_id != ?)
                        JOIN listings l ON m.listing_id = l.id
                        WHERE m.sender_id = ? OR m.receiver_id = ?
                        GROUP BY m.sender_id, m.listing_id
                        ORDER BY m.created_at DESC";
                loop through results and echo each conversation
            -->

            <!-- Example conversation item: -->
            <!--
            <a href="hotelownermessages.php?user_id=<?php echo $convo['sender_id']; ?>&listing_id=<?php echo $convo['listing_id']; ?>"
               class="conversation-item <?php echo $convo['unread_count'] > 0 ? 'unread' : ''; ?>">
                <img src="<?php echo $convo['profile_pic'] ?? 'images/default.png'; ?>" class="convo-avatar">
                <div class="convo-info">
                    <span class="convo-name"><?php echo $convo['firstname'] . ' ' . $convo['lastname']; ?></span>
                    <span class="convo-listing"><?php echo $convo['listing_title']; ?></span>
                    <?php if($convo['unread_count'] > 0) { ?>
                        <span class="unread-badge"><?php echo $convo['unread_count']; ?></span>
                    <?php } ?>
                </div>
            </a>
            -->

            <p class="table-empty" style="padding:20px; text-align:center;">No conversations yet</p>

        </div>

        <!-- MESSAGE THREAD -->
        <div class="message-thread">
            <h3 class="admin-subsection-title">
                Select a conversation
            </h3>

            <div class="message-bubble-container" id="messageBubbles">

                <!-- PHP:
                    if(isset($_GET['user_id']) && isset($_GET['listing_id'])) {
                        $sql = "SELECT m.*, u.firstname, u.lastname, u.profile_pic
                                FROM messages m
                                JOIN users u ON m.sender_id = u.id
                                WHERE ((m.sender_id = ? AND m.receiver_id = ?)
                                OR (m.sender_id = ? AND m.receiver_id = ?))
                                AND m.listing_id = ?
                                ORDER BY m.created_at ASC";
                        // also mark all as read:
                        // UPDATE messages SET is_read = 1 WHERE receiver_id = ? AND sender_id = ? AND listing_id = ?
                        loop and echo each bubble
                    }
                -->

                <!-- Example message bubbles: -->
                <!--
                <div class="message-bubble <?php echo $msg['sender_id'] == $_SESSION['user_id'] ? 'bubble-sent' : 'bubble-received'; ?>">
                    <p><?php echo $msg['message']; ?></p>
                    <span class="bubble-time"><?php echo date('d M Y h:i A', strtotime($msg['created_at'])); ?></span>
                </div>
                -->

                <p class="table-empty" style="padding:20px; text-align:center;">No messages to show</p>

            </div>

            <!-- REPLY FORM -->
            <?php if(isset($_GET['user_id']) && isset($_GET['listing_id'])) { ?>
            <form name="replyform" method="post" action="hotelownerreplybackend.php" onsubmit="return validateReply();">
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
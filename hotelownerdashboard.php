<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Hotel Owner Dashboard - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
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
                <!-- PHP: echo hotel owner's firstname from session -->
                <span class="admin-welcome">Welcome, Owner</span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- DASHBOARD CONTENT -->
<div class="admin-section">

    <h2 class="admin-page-title">My Dashboard</h2>
    <p class="admin-page-subtitle">Manage your hotel listings, rooms and bookings</p>

    <!-- STAT CARDS -->
    <div class="stat-grid">

        <div class="stat-card">
            <div class="stat-icon stat-icon-teal">&#127968;</div>
            <div class="stat-info">
                <!-- PHP: echo COUNT(*) FROM listings WHERE user_id = $_SESSION['user_id'] AND status = 'approved' -->
                <span class="stat-number">0</span>
                <span class="stat-label">Active Listings</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-blue">&#128716;</div>
            <div class="stat-info">
                <!-- PHP: echo COUNT(*) FROM rooms r
                     JOIN hotel_listings hl ON r.hotel_listing_id = hl.id
                     JOIN listings l ON hl.listing_id = l.id
                     WHERE l.user_id = $_SESSION['user_id'] AND r.is_available = 1 -->
                <span class="stat-number">0</span>
                <span class="stat-label">Available Rooms</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-amber">&#128197;</div>
            <div class="stat-info">
                <!-- PHP: echo COUNT(*) FROM bookings b
                     JOIN listings l ON b.listing_id = l.id
                     WHERE l.user_id = $_SESSION['user_id'] AND b.status = 'pending' -->
                <span class="stat-number">0</span>
                <span class="stat-label">Pending Bookings</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-green">&#128176;</div>
            <div class="stat-info">
                <!-- PHP: echo SUM(b.total_price - b.commission_amount) FROM bookings b
                     JOIN listings l ON b.listing_id = l.id
                     WHERE l.user_id = $_SESSION['user_id'] AND b.status = 'completed' -->
                <span class="stat-number">Rs. 0</span>
                <span class="stat-label">Total Earnings</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-purple">&#128172;</div>
            <div class="stat-info">
                <!-- PHP: echo COUNT(*) FROM messages WHERE receiver_id = $_SESSION['user_id'] AND is_read = 0 -->
                <span class="stat-number">0</span>
                <span class="stat-label">Unread Messages</span>
            </div>
        </div>

        <div class="stat-card">
            <div class="stat-icon stat-icon-coral">&#128276;</div>
            <div class="stat-info">
                <!-- PHP: echo COUNT(*) FROM notifications WHERE receiver_id = $_SESSION['user_id'] AND is_read = 0 -->
                <span class="stat-number">0</span>
                <span class="stat-label">Unread Notifications</span>
            </div>
        </div>

    </div>

    <!-- QUICK ACTIONS -->
    <h2 class="admin-page-title admin-section-spacer">Quick Actions</h2>

    <div class="action-grid">

        <a href="hotelowneraddlisting.html" class="action-card">
            <div class="action-icon">&#10133;</div>
            <h3>Add New Listing</h3>
            <p>Submit a new hotel listing for admin approval</p>
        </a>

        <a href="hotelownermanagelistings.html" class="action-card">
            <div class="action-icon">&#127968;</div>
            <h3>My Listings</h3>
            <p>View, edit or delete your hotel listings</p>
        </a>

        <a href="hotelownermanagerooms.html" class="action-card">
            <div class="action-icon">&#128716;</div>
            <h3>Manage Rooms</h3>
            <p>Add, edit or remove rooms from your hotel listings</p>
        </a>

        <a href="hotelownermybookings.html" class="action-card">
            <div class="action-icon">&#128197;</div>
            <h3>My Bookings</h3>
            <p>View and manage bookings made for your hotel</p>
        </a>

        <a href="hotelownermessages.html" class="action-card">
            <div class="action-icon">&#128172;</div>
            <h3>Messages</h3>
            <p>View and reply to messages from tourists</p>
        </a>

        <a href="hotelownernotifications.html" class="action-card">
            <div class="action-icon">&#128276;</div>
            <h3>Notifications</h3>
            <p>View notices and updates from the platform</p>
        </a>

        <a href="hotelownereditprofile.html" class="action-card">
            <div class="action-icon">&#9881;&#65039;</div>
            <h3>My Profile</h3>
            <p>Update your account details</p>
        </a>

    </div>

</div>

</body>
</html>
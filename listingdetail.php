<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Listing Detail - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validatelistingdetail.js"></script>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="index.html"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <a href="explore.html" class="nav-link">Explore</a>
                <a href="mybookings.html" class="nav-link">My Bookings</a>
                <a href="touristmessages.html" class="nav-link">Messages</a>
                <a href="touristnotifications.html" class="nav-link">Notifications</a>
            </td>
            <td class="nav-buttons-cell">
                <span class="admin-welcome">Welcome, Tourist</span>
                <a href="logout.php"><button class="btn btn-login">Logout</button></a>
            </td>
        </tr>
    </table>
</div>

<!-- PAGE CONTENT -->
<div class="admin-section">

    <a href="explore.html" class="back-link">&larr; Back to Explore</a>

    <!-- PHP:
        $sql = "SELECT l.*, u.firstname, u.lastname, u.profile_pic, u.phone,
                AVG(r.rating) as avg_rating, COUNT(r.id) as review_count
                FROM listings l
                JOIN users u ON l.user_id = u.id
                LEFT JOIN reviews r ON l.id = r.listing_id
                WHERE l.id = ? AND l.status = 'approved'
                GROUP BY l.id";
    -->

    <!-- LISTING HEADER -->
    <div class="listing-detail-header">
        <div>
            <h2 class="admin-page-title">Listing Title Here</h2>
            <!-- PHP: echo listing title -->
            <p class="listing-card-location">&#128205; City Here &nbsp;|&nbsp;
                <span class="listing-type-badge listing-type-tour_guide">Type Here</span>
            </p>
            <p style="font-size:14px; color:#6b6b6b; margin-top:8px;">
                &#11088; 0.0 (0 reviews)
            </p>
        </div>
        <div class="listing-detail-price">
            <span>Rs. 0.00</span>
            <span style="font-size:13px; color:#6b6b6b;">per person / per night</span>
        </div>
    </div>

    <!-- LISTING IMAGES -->
    <div class="listing-images-row">
        <!-- PHP:
            $sql = "SELECT * FROM listing_images WHERE listing_id = ?";
            loop through and echo each image
        -->
        <!-- Example:
        <img src="<?php echo $img['image_path']; ?>" class="listing-detail-img" alt="listing image">
        -->
        <div class="listing-img-placeholder">&#127758; No images uploaded</div>
    </div>

    <div class="listing-detail-layout">

        <!-- LEFT: MAIN DETAILS -->
        <div class="listing-detail-main">

            <!-- DESCRIPTION -->
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">About</h3>
                <!-- PHP: echo $listing['description']; -->
                <p style="font-size:14px; color:#2b2b2b; line-height:1.7;">Description here</p>
            </div>

            <!-- TYPE SPECIFIC DETAILS -->
            <!-- PHP: show different fields based on $listing['type'] -->

            <!-- TOUR GUIDE DETAILS -->
            <!--
            <?php if($listing['type'] == 'tour_guide') { ?>
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Tour Guide Details</h3>
                <table class="detail-table">
                    <tr><td>Languages</td><td><?php echo $tg['languages']; ?></td></tr>
                    <tr><td>Specialization</td><td><?php echo $tg['specialization']; ?></td></tr>
                    <tr><td>Experience</td><td><?php echo $tg['experience_years']; ?> years</td></tr>
                    <tr><td>Availability</td><td><?php echo $tg['availability']; ?></td></tr>
                </table>
            </div>
            <?php } ?>
            -->

            <!-- HOTEL DETAILS -->
            <!--
            <?php if($listing['type'] == 'hotel') { ?>
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Hotel Details</h3>
                <table class="detail-table">
                    <tr><td>Star Rating</td><td><?php echo str_repeat('&#11088;', $hotel['star_rating']); ?></td></tr>
                    <tr><td>Amenities</td><td><?php echo $hotel['amenities']; ?></td></tr>
                    <tr><td>Total Rooms</td><td><?php echo $hotel['total_rooms']; ?></td></tr>
                    <tr><td>Check-in Time</td><td><?php echo date('h:i A', strtotime($hotel['check_in_time'])); ?></td></tr>
                    <tr><td>Check-out Time</td><td><?php echo date('h:i A', strtotime($hotel['check_out_time'])); ?></td></tr>
                </table>
            </div>
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Available Rooms</h3>
                <table class="admin-table">
                    <tr>
                        <th>Room Type</th>
                        <th>Capacity</th>
                        <th>Price Per Night (Rs.)</th>
                        <th>Description</th>
                    </tr>
                    <?php foreach($rooms as $room) { ?>
                    <tr>
                        <td><?php echo ucfirst($room['room_type']); ?></td>
                        <td><?php echo $room['capacity']; ?> persons</td>
                        <td><?php echo number_format($room['price_per_night'], 2); ?></td>
                        <td><?php echo $room['description']; ?></td>
                    </tr>
                    <?php } ?>
                </table>
            </div>
            <?php } ?>
            -->

            <!-- SHOP DETAILS -->
            <!--
            <?php if($listing['type'] == 'shop') { ?>
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Shop Details</h3>
                <table class="detail-table">
                    <tr><td>Shop Type</td><td><?php echo $shop['shop_type']; ?></td></tr>
                    <tr><td>Opening Time</td><td><?php echo date('h:i A', strtotime($shop['open_time'])); ?></td></tr>
                    <tr><td>Closing Time</td><td><?php echo date('h:i A', strtotime($shop['close_time'])); ?></td></tr>
                </table>
            </div>
            <?php } ?>
            -->

            <!-- EXPERIENCE DETAILS -->
            <!--
            <?php if($listing['type'] == 'experience') { ?>
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Experience Details</h3>
                <table class="detail-table">
                    <tr><td>Type</td><td><?php echo $exp['experience_type']; ?></td></tr>
                    <tr><td>Duration</td><td><?php echo $exp['duration']; ?></td></tr>
                    <tr><td>Max Group Size</td><td><?php echo $exp['max_group_size']; ?> persons</td></tr>
                    <tr><td>Availability</td><td><?php echo $exp['availability']; ?></td></tr>
                </table>
            </div>
            <?php } ?>
            -->

            <!-- REVIEWS -->
            <div class="listing-detail-section">
                <h3 class="admin-subsection-title">Reviews</h3>

                <!-- PHP:
                    $sql = "SELECT r.*, u.firstname, u.lastname, u.profile_pic
                            FROM reviews r
                            JOIN users u ON r.tourist_id = u.id
                            WHERE r.listing_id = ?
                            ORDER BY r.created_at DESC";
                    loop through and echo each review
                -->

                <!-- Example review: -->
                <!--
                <div class="review-item">
                    <div class="review-header">
                        <img src="<?php echo $review['profile_pic'] ?? 'images/default.png'; ?>" class="convo-avatar">
                        <div>
                            <span class="convo-name"><?php echo $review['firstname'] . ' ' . $review['lastname']; ?></span>
                            <span style="font-size:12px; color:#6b6b6b; display:block;"><?php echo date('d M Y', strtotime($review['created_at'])); ?></span>
                        </div>
                        <span class="review-stars"><?php echo str_repeat('&#11088;', $review['rating']); ?></span>
                    </div>
                    <p class="review-comment"><?php echo $review['comment']; ?></p>
                </div>
                -->

                <p class="table-empty" style="padding:20px; text-align:center;">No reviews yet</p>

            </div>

        </div>

        <!-- RIGHT: SIDEBAR -->
        <div class="listing-detail-sidebar">

            <!-- OWNER INFO -->
            <div class="sidebar-card">
                <h3 class="admin-subsection-title">About the Owner</h3>
                <!-- PHP: echo owner profile pic, name -->
                <div style="display:flex; align-items:center; gap:12px; margin-bottom:15px;">
                    <img src="images/default.png" class="convo-avatar" alt="Owner">
                    <div>
                        <span class="convo-name">Owner Name</span>
                        <span class="convo-listing">&#128222; Phone here</span>
                    </div>
                </div>

                <!-- MESSAGE FORM — only for non-shop listings -->
                <!--
                <?php if($listing['type'] != 'shop') { ?>
                -->
                <form name="messageform" method="post" action="sendmessagebackend.php" onsubmit="return validateMessage();">
                    <input type="hidden" name="receiver_id" value="">
                    <!-- PHP: echo $listing['user_id'] -->
                    <input type="hidden" name="listing_id" value="">
                    <!-- PHP: echo $listing['id'] -->
                    <label style="font-size:13px; color:#2b2b2b; margin-bottom:6px; display:block;">Send a Message</label>
                    <textarea name="message" id="messageText" placeholder="Ask about this listing..." rows="3" style="width:100%; padding:10px; border:1px solid #d6d6d6; border-radius:5px; font-size:13px; resize:none; margin-bottom:10px;"></textarea>
                    <button type="submit" class="btn btn-primary" style="width:100%;">Send Message</button>
                </form>
                <!--
                <?php } ?>
                -->
            </div>

            <!-- BOOK NOW — only for non-shop listings -->
            <!--
            <?php if($listing['type'] != 'shop') { ?>
            -->
            <div class="sidebar-card" style="margin-top:20px;">
                <h3 class="admin-subsection-title">Book Now</h3>
                <p style="font-size:13px; color:#6b6b6b; margin-bottom:15px;">Ready to book? Click below to proceed.</p>
                <a href="makebooking.php?listing_id=0" class="btn btn-primary" style="display:block; text-align:center;">
                    <!-- PHP: echo "makebooking.php?listing_id=" . $listing['id'] -->
                    Make a Booking
                </a>
            </div>
            <!--
            <?php } ?>
            -->

        </div>

    </div>

</div>

</body>
</html>
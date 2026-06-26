<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Make a Booking - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validatemakebooking.js"></script>
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

    <!-- PHP:
        $sql = "SELECT l.*, u.firstname, u.lastname
                FROM listings l
                JOIN users u ON l.user_id = u.id
                WHERE l.id = ? AND l.status = 'approved'";
    -->

    <a href="listingdetail.php?id=0" class="back-link">&larr; Back to Listing</a>
    <!-- PHP: echo "listingdetail.php?id=" . $listing['id'] -->

    <h2 class="admin-page-title">Make a Booking</h2>
    <!-- PHP: echo "Book: " . $listing['title']; -->
    <p class="admin-page-subtitle">Fill in your booking details below</p>

    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Something went wrong. Please try again.</p>
    <?php } ?>
    <?php if(isset($_GET['unavailable'])) { ?>
        <p class="error">Sorry, the selected rooms are not available for your chosen dates.</p>
    <?php } ?>

    <div class="listing-detail-layout">

        <!-- LEFT: BOOKING FORM -->
        <div class="listing-detail-main">
            <div class="listing-detail-section">

                <form name="makebookingform" method="post" action="makebookingbackend.php" onsubmit="return validateMakeBooking();">

                    <input type="hidden" name="listing_id" value="">
                    <!-- PHP: echo $listing['id'] -->

                    <input type="hidden" name="listing_type" value="" id="listing_type">
                    <!-- PHP: echo $listing['type'] -->

                    <label>Start Date & Time</label>
                    <input type="datetime-local" name="start_datetime" id="start_datetime">

                    <label>End Date & Time</label>
                    <input type="datetime-local" name="end_datetime" id="end_datetime">

                    <label>Number of Guests</label>
                    <input type="number" name="guest_count" id="guest_count" placeholder="Enter number of guests" min="1">

                    <!-- ROOM SELECTION — only for hotel listings -->
                    <!--
                    <?php if($listing['type'] == 'hotel') { ?>
                    -->
                    <div id="room-selection-div">
                        <label>Select Rooms</label>
                        <p class="form-note" style="margin-bottom:10px;">You can select multiple rooms</p>
                        <table class="admin-table" style="margin-bottom:15px;">
                            <tr>
                                <th>Select</th>
                                <th>Room Type</th>
                                <th>Capacity</th>
                                <th>Price Per Night (Rs.)</th>
                            </tr>
                            <!-- PHP:
                                $sql = "SELECT * FROM rooms WHERE hotel_listing_id = ? AND is_available = 1";
                                loop through rooms and echo each row
                            -->
                            <!-- Example room row:
                            <tr>
                                <td><input type="checkbox" name="room_ids[]" value="<?php echo $room['id']; ?>"></td>
                                <td><?php echo ucfirst($room['room_type']); ?></td>
                                <td><?php echo $room['capacity']; ?> persons</td>
                                <td><?php echo number_format($room['price_per_night'], 2); ?></td>
                            </tr>
                            -->
                            <tr>
                                <td colspan="4" class="table-empty">No available rooms</td>
                            </tr>
                        </table>
                    </div>
                    <!--
                    <?php } ?>
                    -->

                    <button type="submit" class="btn btn-primary" style="margin-top:10px;">Proceed to Payment</button>

                </form>

            </div>
        </div>

        <!-- RIGHT: BOOKING SUMMARY -->
        <div class="listing-detail-sidebar">
            <div class="sidebar-card">
                <h3 class="admin-subsection-title">Booking Summary</h3>

                <!-- PHP: echo listing details -->
                <table class="detail-table">
                    <tr>
                        <td>Listing</td>
                        <td>Title here</td>
                    </tr>
                    <tr>
                        <td>Type</td>
                        <td>Type here</td>
                    </tr>
                    <tr>
                        <td>Location</td>
                        <td>City here</td>
                    </tr>
                    <tr>
                        <td>Base Price</td>
                        <td>Rs. 0.00</td>
                    </tr>
                    <tr>
                        <td>Owner</td>
                        <td>Name here</td>
                    </tr>
                </table>

                <p class="form-note" style="margin-top:15px;">
                    Total price will be calculated based on your selected dates, guests and rooms. You will see the final amount on the payment page.
                </p>
            </div>
        </div>

    </div>

</div>

</body>
</html>
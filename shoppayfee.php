<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
require_once 'Classes/session.php';
checkRole(['shop_owner']);

require_once 'Classes/DBconnection.php';
// fetch current listing fee
require_once 'Classes/Commission.php';
$currentFee = Commission::getCurrentListingFee();

$conn         = DBconnection::getInstance()->getConnection();
$userId       = $_SESSION['user_id'];
$currentMonth = date('n');
$currentYear  = date('Y');

// fetch listings with fee status
$sql  = "SELECT l.id, l.title, l.city, sl.id as shop_listing_id, sl.shop_type,
          p.id as payment_id, p.payment_status
          FROM listings l
          JOIN shop_listings sl ON l.id = sl.listing_id
          LEFT JOIN payments p ON p.shop_listing_id = sl.id
              AND p.type = 'listing_fee'
              AND p.payment_status = 'completed'
              AND p.fee_month = ?
              AND p.fee_year = ?
          WHERE l.user_id = ? AND l.status = 'approved'
          ORDER BY l.created_at DESC";
$stmt = $conn->prepare($sql);
$stmt->bind_param("iii", $currentMonth, $currentYear, $userId);
$stmt->execute();
$feeListings = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

// fetch payment history
$histSql  = "SELECT p.*, l.title
              FROM payments p
              JOIN shop_listings sl ON p.shop_listing_id = sl.id
              JOIN listings l ON sl.listing_id = l.id
              WHERE l.user_id = ? AND p.type = 'listing_fee'
              ORDER BY p.payment_date DESC";
$histStmt = $conn->prepare($histSql);
$histStmt->bind_param("i", $userId);
$histStmt->execute();
$paymentHistory = $histStmt->get_result()->fetch_all(MYSQLI_ASSOC);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Pay Listing Fee - CeylonTourMate.lk</title>
    <link rel="stylesheet" type="text/css" href="style.css">
    <script type="text/javascript" src="validateshoppayfee.js"></script>
</head>
<body>

<!-- NAVBAR -->
<div class="navbar">
    <table>
        <tr>
            <td class="logo-cell">
                <a href="shopownerdashboard.php"><span class="logo-text">Ceylon<span>TourMate</span>.lk</span></a>
            </td>
            <td class="nav-links-cell">
                <span class="partner-badge">Shop Owner</span>
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

    <a href="shopownerdashboard.php" class="back-link">&larr; Back to Dashboard</a>

    <h2 class="admin-page-title">Pay Listing Fee</h2>
    <p class="admin-page-subtitle">Pay your monthly listing fee for your active shop listings</p>

    <?php if(isset($_GET['success'])) { ?>
        <p class="success">Payment completed successfully. Thank you!</p>
    <?php } ?>
    <?php if(isset($_GET['error'])) { ?>
        <p class="error">Payment failed. Please try again.</p>
    <?php } ?>
    <?php if(isset($_GET['alreadypaid'])) { ?>
        <p class="success">You have already paid the listing fee for this listing this month.</p>
    <?php } ?>

    <!-- MY LISTINGS AND FEE STATUS -->
    <h3 class="admin-subsection-title">My Listings — Fee Status This Month</h3>

    <table class="admin-table" style="margin-bottom:40px;">
        <tr>
            <th>Listing</th>
            <th>Shop Type</th>
            <th>City</th>
            <th>Fee Status</th>
            <th>Action</th>
        </tr>

        <?php if(empty($feeListings)) { ?>
    <tr>
        <td colspan="5" class="table-empty">No active listings found</td>
    </tr>
<?php } else { ?>
    <?php foreach($feeListings as $listing) { ?>
    <tr>
        <td><?php echo $listing['title']; ?></td>
        <td><?php echo $listing['shop_type']; ?></td>
        <td><?php echo $listing['city']; ?></td>
        <td>
            <?php if($listing['payment_id']) { ?>
                <span class="status-badge status-confirmed">Paid</span>
            <?php } else { ?>
                <span class="status-badge status-cancelled">Unpaid</span>
            <?php } ?>
        </td>
        <td>
            <?php if(!$listing['payment_id']) { ?>
                <button class="tbl-btn tbl-btn-green"
						onclick="selectListing(<?php echo $listing['shop_listing_id']; ?>, '<?php echo $listing['title']; ?>', <?php echo $currentFee; ?>)">
					Pay Now
				</button>
            <?php } else { ?>
                <span style="color:#999; font-size:12px;">Already Paid</span>
            <?php } ?>
        </td>
    </tr>
    <?php } ?>
<?php } ?>

    </table>

    <!-- PAYMENT FORM -->
    <h3 class="admin-subsection-title">Card Payment</h3>
    <p class="form-note" style="margin-bottom:20px;">Selected listing: <strong id="selected-listing-name">None selected</strong></p>

    <div class="commission-form-card" style="max-width:500px;">
        <form name="shoppayfeeform" method="post" action="shoppayfeebackend.php" onsubmit="return validateShopPayFee();">

            <input type="hidden" name="shop_listing_id" id="shop_listing_id" value="">

            <!-- PHP: echo current listing fee amount from commission or fixed fee -->
            <label>Amount Due (Rs.)</label>
            <input type="text" name="amount" id="amount" value="<?php echo $currentFee; ?>" readonly style="background-color:#f5f5f5;">

            <label>Cardholder Name</label>
            <input type="text" name="cardholder_name" placeholder="Name on card">

            <label>Card Number</label>
            <input type="text" name="card_number" placeholder="1234 5678 9012 3456" maxlength="19">

            <label>Expiry Date</label>
            <input type="text" name="expiry_date" placeholder="MM/YY" maxlength="5">

            <label>CVV</label>
            <input type="text" name="cvv" placeholder="123" maxlength="3">

            <button type="submit" class="btn btn-primary" style="margin-top:10px;">Complete Payment</button>

        </form>
    </div>

    <!-- PAYMENT HISTORY -->
    <h3 class="admin-subsection-title admin-section-spacer">Payment History</h3>

    <table class="admin-table">
        <tr>
            <th>Listing</th>
            <th>Amount (Rs.)</th>
            <th>Month</th>
            <th>Year</th>
            <th>Status</th>
            <th>Date Paid</th>
        </tr>

        <?php if(empty($paymentHistory)) { ?>
    <tr>
        <td colspan="6" class="table-empty">No payment history found</td>
    </tr>
<?php } else { ?>
    <?php foreach($paymentHistory as $payment) { ?>
    <tr>
        <td><?php echo $payment['title']; ?></td>
        <td>Rs. <?php echo number_format($payment['amount'], 2); ?></td>
        <td><?php echo date('F', mktime(0, 0, 0, $payment['fee_month'], 1)); ?></td>
        <td><?php echo $payment['fee_year']; ?></td>
        <td><span class="status-badge status-confirmed">Completed</span></td>
        <td><?php echo date('d M Y', strtotime($payment['payment_date'])); ?></td>
    </tr>
    <?php } ?>
<?php } ?>

    </table>

</div>

</body>
</html>
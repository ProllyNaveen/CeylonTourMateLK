<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Payment.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['shop_owner']);

    $userId        = $_SESSION['user_id'];
    $shopListingId = $_POST['shop_listing_id'];
    $amount        = $_POST['amount'];
    $feeMonth      = date('n');
    $feeYear       = date('Y');

    $payment = new Payment(
        $userId, $shopListingId, null, 'listing_fee',
        $amount, 'completed', $feeMonth, $feeYear
    );

    $result = $payment->payListingFee();

    switch ($result) {
        case "success":
            header("Location: shoppayfee.php?success=1");
            break;
        case "alreadypaid":
            header("Location: shoppayfee.php?alreadypaid=1");
            break;
        case "unauthorized":
            header("Location: shoppayfee.php?error=1");
            break;
        default:
            header("Location: shoppayfee.php?error=1");
            break;
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: shoppayfee.php?error=1");
    exit();
}
?>
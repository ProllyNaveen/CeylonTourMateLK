<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Listing.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['admin']);

    $listingId = $_GET['id'];
    $action    = $_GET['action'];

    $listing = new Listing(
        null, null, null, null, null, null, null
    );

    $result = $listing->approveRejectListing($listingId, $action);

    if ($result == "success") {
        header("Location: viewlistingrequests.php?success=1");
    } else {
        header("Location: viewlistingrequests.php?error=1");
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: viewlistingrequests.php?error=1");
    exit();
}
?>
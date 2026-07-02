<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/Commission.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['admin']);

    $rate      = $_POST['rate'];
    $updatedBy = $_SESSION['user_id'];

    $commission = new Commission($rate, $updatedBy);
    $result     = $commission->changeRate();

    if ($result == "success") {
        header("Location: commissionoverview.php?success=1");
    } else {
        header("Location: commissionoverview.php?error=1");
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: commissionoverview.php?error=1");
    exit();
}
?>
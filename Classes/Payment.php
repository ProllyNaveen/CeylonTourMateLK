<?php
require_once 'DBconnection.php';

class Payment {

    private $id;
    private $userId;
    private $shopListingId;
    private $bookingId;
    private $type;
    private $amount;
    private $paymentStatus;
    private $feeMonth;
    private $feeYear;

    public function __construct($userId, $shopListingId, $bookingId, $type, $amount,
                                $paymentStatus, $feeMonth, $feeYear) {
        $this->userId        = $userId;
        $this->shopListingId = $shopListingId;
        $this->bookingId     = $bookingId;
        $this->type          = $type;
        $this->amount        = $amount;
        $this->paymentStatus = $paymentStatus;
        $this->feeMonth      = $feeMonth;
        $this->feeYear       = $feeYear;
    }

    public function completePayment() {
    $conn = DBconnection::getInstance()->getConnection();

    $checkSql = "SELECT id, status FROM bookings WHERE id = ? AND tourist_id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $this->bookingId, $this->userId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows == 0) {
        return "unauthorized";
    }

    $booking = $result->fetch_assoc();

    if ($booking['status'] != 'confirmed') {
        return "notconfirmed";
    }

    $sql = "INSERT INTO payments (user_id, booking_id, type, amount, payment_status)
            VALUES (?, ?, 'booking', ?, 'completed')";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iid",
        $this->userId,
        $this->bookingId,
        $this->amount
    );

    if ($stmt->execute()) {
        return "success";
    } else {
        return "error";
    }
}

    public function payListingFee() {
        $conn = DBconnection::getInstance()->getConnection();

        $checkOwnerSql = "SELECT sl.id FROM shop_listings sl
                           JOIN listings l ON sl.listing_id = l.id
                           WHERE sl.id = ? AND l.user_id = ?";
        $checkOwnerStmt = $conn->prepare($checkOwnerSql);
        $checkOwnerStmt->bind_param("ii", $this->shopListingId, $this->userId);
        $checkOwnerStmt->execute();
        $checkOwnerStmt->store_result();

        if ($checkOwnerStmt->num_rows == 0) {
            return "unauthorized";
        }

        $checkSql = "SELECT id FROM payments
                      WHERE shop_listing_id = ? AND type = 'listing_fee'
                      AND payment_status = 'completed'
                      AND fee_month = ? AND fee_year = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("iii", $this->shopListingId, $this->feeMonth, $this->feeYear);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            return "alreadypaid";
        }

        $sql = "INSERT INTO payments (user_id, shop_listing_id, type, amount,
                payment_status, fee_month, fee_year)
                VALUES (?, ?, 'listing_fee', ?, 'completed', ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iidii",
            $this->userId,
            $this->shopListingId,
            $this->amount,
            $this->feeMonth,
            $this->feeYear
        );

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    public static function getPaymentHistory($userId, $type = null) {
        $conn = DBconnection::getInstance()->getConnection();

        if ($type) {
            $sql = "SELECT * FROM payments WHERE user_id = ? AND type = ?
                    ORDER BY payment_date DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $userId, $type);
        } else {
            $sql = "SELECT * FROM payments WHERE user_id = ?
                    ORDER BY payment_date DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $payments = [];
        while ($row = $result->fetch_assoc()) {
            $payments[] = $row;
        }
        return $payments;
    }

    public static function isFeesPaid($shopListingId, $month, $year) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "SELECT id FROM payments
                WHERE shop_listing_id = ? AND type = 'listing_fee'
                AND payment_status = 'completed'
                AND fee_month = ? AND fee_year = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $shopListingId, $month, $year);
        $stmt->execute();
        $stmt->store_result();

        return $stmt->num_rows > 0;
    }

    public function getId()            { return $this->id; }
    public function getUserId()        { return $this->userId; }
    public function getShopListingId() { return $this->shopListingId; }
    public function getBookingId()     { return $this->bookingId; }
    public function getType()          { return $this->type; }
    public function getAmount()        { return $this->amount; }
    public function getPaymentStatus() { return $this->paymentStatus; }
    public function getFeeMonth()      { return $this->feeMonth; }
    public function getFeeYear()       { return $this->feeYear; }

    public function setId($id)                       { $this->id = $id; }
    public function setUserId($id)                   { $this->userId = $id; }
    public function setShopListingId($id)             { $this->shopListingId = $id; }
    public function setBookingId($id)                 { $this->bookingId = $id; }
    public function setType($type)                    { $this->type = $type; }
    public function setAmount($amount)                { $this->amount = $amount; }
    public function setPaymentStatus($status)         { $this->paymentStatus = $status; }
    public function setFeeMonth($month)               { $this->feeMonth = $month; }
    public function setFeeYear($year)                 { $this->feeYear = $year; }
}
?>
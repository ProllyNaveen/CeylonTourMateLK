<?php
require_once 'DBconnection.php';

class Commission {

    private $id;
    private $rate;
    private $type;
    private $updatedBy;

    public function __construct($rate, $updatedBy, $type = 'booking_commission') {
        $this->rate      = $rate;
        $this->updatedBy = $updatedBy;
        $this->type      = $type;
    }

    public function changeRate() {
        $conn = DBconnection::getInstance()->getConnection();

        $sql  = "INSERT INTO commission (rate, type, updated_by)
                 VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("dsi",
            $this->rate,
            $this->type,
            $this->updatedBy
        );

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    public function calculateCommission($totalPrice) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql  = "SELECT rate FROM commission
                 WHERE type = 'booking_commission'
                 ORDER BY updated_at DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row  = $result->fetch_assoc();
            $rate = $row['rate'];
            return ($totalPrice * $rate) / 100;
        } else {
            return 0;
        }
    }

    public static function getCurrentListingFee() {
        $conn = DBconnection::getInstance()->getConnection();

        $sql  = "SELECT rate FROM commission
                 WHERE type = 'listing_fee'
                 ORDER BY updated_at DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            return $result->fetch_assoc()['rate'];
        } else {
            return 500.00; 
        }
    }

    public static function viewCommissionOverview() {
        $conn = DBconnection::getInstance()->getConnection();

        $overview = [];

        $sql1  = "SELECT COALESCE(SUM(commission_amount), 0) as total_commission
                  FROM bookings WHERE status = 'completed'";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute();
        $overview['total_commission'] = $stmt1->get_result()->fetch_assoc()['total_commission'];

        $sql2  = "SELECT COALESCE(SUM(amount), 0) as total_fees
                  FROM payments WHERE type = 'listing_fee'
                  AND payment_status = 'completed'";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute();
        $overview['total_fees'] = $stmt2->get_result()->fetch_assoc()['total_fees'];

        $sql3  = "SELECT rate FROM commission
                  WHERE type = 'booking_commission'
                  ORDER BY updated_at DESC LIMIT 1";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->execute();
        $result3 = $stmt3->get_result();
        $overview['current_rate'] = $result3->num_rows > 0
            ? $result3->fetch_assoc()['rate']
            : 0;

        $overview['current_listing_fee'] = self::getCurrentListingFee();

        $sql4  = "SELECT c.*, u.firstname, u.lastname
                  FROM commission c
                  JOIN users u ON c.updated_by = u.id
                  WHERE c.type = 'booking_commission'
                  ORDER BY c.updated_at DESC";
        $stmt4 = $conn->prepare($sql4);
        $stmt4->execute();
        $result4 = $stmt4->get_result();

        $history = [];
        while ($row = $result4->fetch_assoc()) {
            $history[] = $row;
        }
        $overview['history'] = $history;

        $sql5  = "SELECT c.*, u.firstname, u.lastname
                  FROM commission c
                  JOIN users u ON c.updated_by = u.id
                  WHERE c.type = 'listing_fee'
                  ORDER BY c.updated_at DESC";
        $stmt5 = $conn->prepare($sql5);
        $stmt5->execute();
        $result5 = $stmt5->get_result();

        $feeHistory = [];
        while ($row = $result5->fetch_assoc()) {
            $feeHistory[] = $row;
        }
        $overview['fee_history'] = $feeHistory;

        return $overview;
    }

    public function getId()        { return $this->id; }
    public function getRate()      { return $this->rate; }
    public function getType()      { return $this->type; }
    public function getUpdatedBy() { return $this->updatedBy; }

    public function setId($id)             { $this->id = $id; }
    public function setRate($rate)         { $this->rate = $rate; }
    public function setType($type)         { $this->type = $type; }
    public function setUpdatedBy($adminId) { $this->updatedBy = $adminId; }
}
?>
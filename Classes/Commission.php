<?php
require_once 'DBconnection.php';

class Commission {

    private $id;
    private $rate;
    private $updatedBy;

    public function __construct($rate, $updatedBy) {
        $this->rate      = $rate;
        $this->updatedBy = $updatedBy;
    }

    // ===========================
    // CHANGE RATE
    // INSERT new row — never UPDATE, to preserve history
    // ===========================
    public function changeRate() {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "INSERT INTO commission (rate, updated_by)
                VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("di",
            $this->rate,
            $this->updatedBy
        );

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // CALCULATE COMMISSION
    // Returns commission amount for a given total price
    // Used when creating a booking
    // ===========================
    public function calculateCommission($totalPrice) {
        $conn = DBconnection::getInstance()->getConnection();

        // get latest rate
        $sql = "SELECT rate FROM commission ORDER BY updated_at DESC LIMIT 1";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $rate = $row['rate'];
            return ($totalPrice * $rate) / 100;
        } else {
            // default to 0 if no rate set yet
            return 0;
        }
    }

    // ===========================
    // VIEW COMMISSION OVERVIEW
    // Static-style method — fetches all data, no instance needed
    // ===========================
    public static function viewCommissionOverview() {
        $conn = DBconnection::getInstance()->getConnection();

        $overview = [];

        // total commission from bookings
        $sql1 = "SELECT COALESCE(SUM(commission_amount), 0) as total_commission
                 FROM bookings WHERE status = 'completed'";
        $stmt1 = $conn->prepare($sql1);
        $stmt1->execute();
        $result1 = $stmt1->get_result();
        $overview['total_commission'] = $result1->fetch_assoc()['total_commission'];

        // total listing fees collected
        $sql2 = "SELECT COALESCE(SUM(amount), 0) as total_fees
                 FROM payments WHERE type = 'listing_fee'
                 AND payment_status = 'completed'";
        $stmt2 = $conn->prepare($sql2);
        $stmt2->execute();
        $result2 = $stmt2->get_result();
        $overview['total_fees'] = $result2->fetch_assoc()['total_fees'];

        // current commission rate
        $sql3 = "SELECT rate FROM commission ORDER BY updated_at DESC LIMIT 1";
        $stmt3 = $conn->prepare($sql3);
        $stmt3->execute();
        $result3 = $stmt3->get_result();
        $overview['current_rate'] = $result3->num_rows > 0
            ? $result3->fetch_assoc()['rate']
            : 0;

        // full rate history
        $sql4 = "SELECT c.*, u.firstname, u.lastname
                 FROM commission c
                 JOIN users u ON c.updated_by = u.id
                 ORDER BY c.updated_at DESC";
        $stmt4 = $conn->prepare($sql4);
        $stmt4->execute();
        $result4 = $stmt4->get_result();

        $history = [];
        while ($row = $result4->fetch_assoc()) {
            $history[] = $row;
        }
        $overview['history'] = $history;

        return $overview;
    }

    // ===========================
    // GETTERS
    // ===========================
    public function getId()        { return $this->id; }
    public function getRate()      { return $this->rate; }
    public function getUpdatedBy() { return $this->updatedBy; }

    // ===========================
    // SETTERS
    // ===========================
    public function setId($id)             { $this->id = $id; }
    public function setRate($rate)         { $this->rate = $rate; }
    public function setUpdatedBy($adminId) { $this->updatedBy = $adminId; }
}
?>
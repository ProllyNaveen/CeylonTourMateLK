<?php
require_once 'DBconnection.php';
require_once 'Commission.php';

class Booking {

    private $id;
    private $touristId;
    private $listingId;
    private $status;
    private $startDatetime;
    private $endDatetime;
    private $guestCount;
    private $totalPrice;
    private $commissionAmount;

    public function __construct($touristId, $listingId, $startDatetime, $endDatetime, $guestCount) {
        $this->touristId     = $touristId;
        $this->listingId     = $listingId;
        $this->status        = 'pending';
        $this->startDatetime = $startDatetime;
        $this->endDatetime   = $endDatetime;
        $this->guestCount    = $guestCount;
    }

    // ===========================
    // MAKE BOOKING
    // roomIds only used for hotel bookings, pass empty array otherwise
    // ===========================
    public function makeBooking($roomIds = []) {
        $conn = DBconnection::getInstance()->getConnection();

        // get listing type and price
        $listingSql = "SELECT type, price FROM listings WHERE id = ? AND status = 'approved'";
        $listingStmt = $conn->prepare($listingSql);
        $listingStmt->bind_param("i", $this->listingId);
        $listingStmt->execute();
        $listingResult = $listingStmt->get_result();

        if ($listingResult->num_rows == 0) {
            return "invalidlisting";
        }
        $listing = $listingResult->fetch_assoc();

        // calculate total price based on listing type
        if ($listing['type'] == 'hotel') {
            if (empty($roomIds)) {
                return "noroomsselected";
            }

            // calculate number of nights
            $start = new DateTime($this->startDatetime);
            $end = new DateTime($this->endDatetime);
            $nights = $start->diff($end)->days;
            if ($nights < 1) $nights = 1;

            // check room availability and sum prices
            $this->totalPrice = 0;
            foreach ($roomIds as $roomId) {
                $roomSql = "SELECT price_per_night FROM rooms WHERE id = ? AND is_available = 1";
                $roomStmt = $conn->prepare($roomSql);
                $roomStmt->bind_param("i", $roomId);
                $roomStmt->execute();
                $roomResult = $roomStmt->get_result();

                if ($roomResult->num_rows == 0) {
                    return "roomunavailable";
                }
                $room = $roomResult->fetch_assoc();
                $this->totalPrice += ($room['price_per_night'] * $nights);
            }
        } else {
            // calculate number of days (round up any partial day)
					$start = new DateTime($this->startDatetime);
					$end   = new DateTime($this->endDatetime);
					$interval = $start->diff($end);
					$days = $interval->days;
					if ($interval->h > 0 || $interval->i > 0 || $interval->s > 0) {
						$days++;
					}
					if ($days < 1) $days = 1;

					if ($listing['type'] == 'tour_guide') {
						// price per day x days
						$this->totalPrice = $listing['price'] * $days;
					} else {
						// experience: price per person
						$this->totalPrice = $listing['price'] * $this->guestCount;
					}
        }

        // calculate commission
        $commission = new Commission(0, 0);
        $this->commissionAmount = $commission->calculateCommission($this->totalPrice);

        // insert booking
        $sql = "INSERT INTO bookings (tourist_id, listing_id, status, start_datetime,
                end_datetime, guest_count, total_price, commission_amount)
                VALUES (?, ?, 'pending', ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iissidd",
            $this->touristId,
            $this->listingId,
            $this->startDatetime,
            $this->endDatetime,
            $this->guestCount,
            $this->totalPrice,
            $this->commissionAmount
        );

        if ($stmt->execute()) {
            $this->id = $conn->insert_id;

            // insert into junction table for hotel bookings
            if ($listing['type'] == 'hotel') {
                foreach ($roomIds as $roomId) {
                    $junctionSql = "INSERT INTO booking_rooms (booking_id, room_id) VALUES (?, ?)";
                    $junctionStmt = $conn->prepare($junctionSql);
                    $junctionStmt->bind_param("ii", $this->id, $roomId);
                    $junctionStmt->execute();
                }
            }

            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // CANCEL BOOKING
    // ===========================
    public function cancelBooking($bookingId, $byAdmin = false) {
        $conn = DBconnection::getInstance()->getConnection();

        if ($byAdmin) {
            $checkSql = "SELECT status FROM bookings WHERE id = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("i", $bookingId);
        } else {
            $checkSql = "SELECT status FROM bookings WHERE id = ? AND tourist_id = ?";
            $checkStmt = $conn->prepare($checkSql);
            $checkStmt->bind_param("ii", $bookingId, $this->touristId);
        }
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows == 0) {
            return "unauthorized";
        }

        $booking = $result->fetch_assoc();
        if ($booking['status'] == 'completed' || $booking['status'] == 'cancelled') {
            return "cannotcancel";
        }

        $sql = "UPDATE bookings SET status = 'cancelled' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bookingId);

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // CONFIRM BOOKING (by partner)
    // ===========================
    public function confirmBooking($bookingId, $partnerId) {
    $conn = DBconnection::getInstance()->getConnection();

    $checkSql = "SELECT b.status, b.tourist_id, l.title FROM bookings b
                  JOIN listings l ON b.listing_id = l.id
                  WHERE b.id = ? AND l.user_id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $bookingId, $partnerId);
    $checkStmt->execute();
    $result = $checkStmt->get_result();

    if ($result->num_rows == 0) {
        return "unauthorized";
    }

    $booking = $result->fetch_assoc();
    if ($booking['status'] != 'pending') {
        return "notpending";
    }

    $sql  = "UPDATE bookings SET status = 'confirmed' WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $bookingId);

    if ($stmt->execute()) {
        // auto send notification to tourist
        require_once 'Notification.php';
        $notification = new Notification(
            $partnerId,
            $booking['tourist_id'],
            "Your booking for \"" . $booking['title'] . "\" has been confirmed! Please proceed to payment from My Bookings."
        );
        $notification->sendNotification();
        return "success";
    } else {
        return "error";
    }
}

    // ===========================
    // COMPLETE BOOKING (by partner, after service delivered)
    // ===========================
    public function completeBooking($bookingId, $partnerId) {
        $conn = DBconnection::getInstance()->getConnection();

        $checkSql = "SELECT b.status FROM bookings b
                      JOIN listings l ON b.listing_id = l.id
                      WHERE b.id = ? AND l.user_id = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ii", $bookingId, $partnerId);
        $checkStmt->execute();
        $result = $checkStmt->get_result();

        if ($result->num_rows == 0) {
            return "unauthorized";
        }

        $booking = $result->fetch_assoc();
        if ($booking['status'] != 'confirmed') {
            return "notconfirmed";
        }

        $sql = "UPDATE bookings SET status = 'completed' WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bookingId);

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // VIEW BOOKING STATUS
    // ===========================
    public function viewBookingStatus($bookingId) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "SELECT * FROM bookings WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $bookingId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    // ===========================
    // VIEW ALL BOOKINGS (admin only)
    // Static — fetches every booking on the platform
    // ===========================
    public static function viewAllBookings($status = null) {
        $conn = DBconnection::getInstance()->getConnection();

        if ($status) {
            $sql = "SELECT b.*, u.firstname, u.lastname, l.title, l.type
                    FROM bookings b
                    JOIN users u ON b.tourist_id = u.id
                    JOIN listings l ON b.listing_id = l.id
                    WHERE b.status = ?
                    ORDER BY b.created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $status);
        } else {
            $sql = "SELECT b.*, u.firstname, u.lastname, l.title, l.type
                    FROM bookings b
                    JOIN users u ON b.tourist_id = u.id
                    JOIN listings l ON b.listing_id = l.id
                    ORDER BY b.created_at DESC";
            $stmt = $conn->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        return $bookings;
    }

    // ===========================
    // GET BOOKINGS FOR PARTNER
    // Static — fetches bookings for a partner's listings
    // ===========================
    public static function getBookingsForPartner($partnerId, $status = null) {
        $conn = DBconnection::getInstance()->getConnection();

        if ($status) {
            $sql = "SELECT b.*, u.firstname, u.lastname, l.title,
                    (b.total_price - b.commission_amount) as earnings
                    FROM bookings b
                    JOIN users u ON b.tourist_id = u.id
                    JOIN listings l ON b.listing_id = l.id
                    WHERE l.user_id = ? AND b.status = ?
                    ORDER BY b.created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $partnerId, $status);
        } else {
            $sql = "SELECT b.*, u.firstname, u.lastname, l.title,
                    (b.total_price - b.commission_amount) as earnings
                    FROM bookings b
                    JOIN users u ON b.tourist_id = u.id
                    JOIN listings l ON b.listing_id = l.id
                    WHERE l.user_id = ?
                    ORDER BY b.created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $partnerId);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        return $bookings;
    }

    // ===========================
    // GET BOOKINGS FOR TOURIST
    // Static — fetches a tourist's own bookings
    // ===========================
    public static function getBookingsForTourist($touristId, $status = null) {
        $conn = DBconnection::getInstance()->getConnection();

        if ($status) {
            $sql = "SELECT b.*, l.title, l.type
                    FROM bookings b
                    JOIN listings l ON b.listing_id = l.id
                    WHERE b.tourist_id = ? AND b.status = ?
                    ORDER BY b.created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $touristId, $status);
        } else {
            $sql = "SELECT b.*, l.title, l.type
                    FROM bookings b
                    JOIN listings l ON b.listing_id = l.id
                    WHERE b.tourist_id = ?
                    ORDER BY b.created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $touristId);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $bookings = [];
        while ($row = $result->fetch_assoc()) {
            $bookings[] = $row;
        }
        return $bookings;
    }

    // ===========================
    // GETTERS
    // ===========================
    public function getId()               { return $this->id; }
    public function getTouristId()        { return $this->touristId; }
    public function getListingId()        { return $this->listingId; }
    public function getStatus()           { return $this->status; }
    public function getStartDatetime()    { return $this->startDatetime; }
    public function getEndDatetime()      { return $this->endDatetime; }
    public function getGuestCount()       { return $this->guestCount; }
    public function getTotalPrice()       { return $this->totalPrice; }
    public function getCommissionAmount() { return $this->commissionAmount; }

    // ===========================
    // SETTERS
    // ===========================
    public function setId($id)                     { $this->id = $id; }
    public function setTouristId($id)               { $this->touristId = $id; }
    public function setListingId($id)               { $this->listingId = $id; }
    public function setStatus($status)              { $this->status = $status; }
    public function setStartDatetime($dt)           { $this->startDatetime = $dt; }
    public function setEndDatetime($dt)             { $this->endDatetime = $dt; }
    public function setGuestCount($count)           { $this->guestCount = $count; }
    public function setTotalPrice($price)           { $this->totalPrice = $price; }
    public function setCommissionAmount($amount)    { $this->commissionAmount = $amount; }
}
?>
<?php
require_once 'DBconnection.php';

class Review {

    private $id;
    private $touristId;
    private $listingId;
    private $bookingId;
    private $rating;
    private $comment;

    public function __construct($touristId, $listingId, $bookingId, $rating, $comment) {
        $this->touristId = $touristId;
        $this->listingId = $listingId;
        $this->bookingId = $bookingId;
        $this->rating    = $rating;
        $this->comment   = $comment;
    }

    // ===========================
    // WRITE REVIEW
    // ===========================
    public function writeReview() {
        $conn = DBconnection::getInstance()->getConnection();

        // check if review already exists for this booking
        $checkSql = "SELECT id FROM reviews WHERE booking_id = ? AND tourist_id = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ii", $this->bookingId, $this->touristId);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            return "already";
        }

        $sql = "INSERT INTO reviews (tourist_id, listing_id, booking_id, rating, comment)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiis",
            $this->touristId,
            $this->listingId,
            $this->bookingId,
            $this->rating,
            $this->comment
        );

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // DELETE REVIEW
    // ===========================
    public function deleteReview($reviewId) {
    $conn = DBconnection::getInstance()->getConnection();

    // verify this review belongs to the tourist making the request
    $checkSql = "SELECT id FROM reviews WHERE id = ? AND tourist_id = ?";
    $checkStmt = $conn->prepare($checkSql);
    $checkStmt->bind_param("ii", $reviewId, $this->touristId);
    $checkStmt->execute();
    $checkStmt->store_result();

    if ($checkStmt->num_rows == 0) {
        return "unauthorized";
    }

    $sql = "DELETE FROM reviews WHERE id = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("i", $reviewId);

    if ($stmt->execute()) {
        return "success";
    } else {
        return "error";
    }
}

    // ===========================
    // GET REVIEWS FOR LISTING
    // Static — fetches all reviews for a specific listing
    // ===========================
    public static function getReviewsByListing($listingId) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "SELECT r.*, u.firstname, u.lastname, u.profile_pic
                FROM reviews r
                JOIN users u ON r.tourist_id = u.id
                WHERE r.listing_id = ?
                ORDER BY r.created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $listingId);
        $stmt->execute();
        $result = $stmt->get_result();

        $reviews = [];
        while ($row = $result->fetch_assoc()) {
            $reviews[] = $row;
        }
        return $reviews;
    }

    // ===========================
    // CHECK IF REVIEWED
    // Static — checks if tourist already reviewed a booking
    // ===========================
    public static function hasReviewed($bookingId, $touristId) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "SELECT id FROM reviews WHERE booking_id = ? AND tourist_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $bookingId, $touristId);
        $stmt->execute();
        $stmt->store_result();

        return $stmt->num_rows > 0;
    }

    // ===========================
    // GETTERS
    // ===========================
    public function getId()        { return $this->id; }
    public function getTouristId() { return $this->touristId; }
    public function getListingId() { return $this->listingId; }
    public function getBookingId() { return $this->bookingId; }
    public function getRating()    { return $this->rating; }
    public function getComment()   { return $this->comment; }

    // ===========================
    // SETTERS
    // ===========================
    public function setId($id)             { $this->id = $id; }
    public function setTouristId($id)      { $this->touristId = $id; }
    public function setListingId($id)      { $this->listingId = $id; }
    public function setBookingId($id)      { $this->bookingId = $id; }
    public function setRating($rating)     { $this->rating = $rating; }
    public function setComment($comment)   { $this->comment = $comment; }
}
?>
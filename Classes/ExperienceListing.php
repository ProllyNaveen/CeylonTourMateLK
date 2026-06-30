<?php
require_once 'Listing.php';

class ExperienceListing extends Listing {

    private $experienceType;
    private $duration;
    private $maxGroupSize;
    private $availability;

    public function __construct($userId, $title, $description, $location, $city, $price,
                                 $experienceType, $duration, $maxGroupSize, $availability) {
        parent::__construct($userId, $title, $description, $location, $city, $price, 'experience');

        $this->experienceType = $experienceType;
        $this->duration         = $duration;
        $this->maxGroupSize     = $maxGroupSize;
        $this->availability     = $availability;
    }

    // ===========================
    // ADD EXPERIENCE LISTING
    // Inserts parent listing row, then the experience_listings subtype row
    // ===========================
    public function addExperienceListing() {
        $conn = DBconnection::getInstance()->getConnection();

        $listingId = $this->addListing();

        if (!$listingId) {
            return "error";
        }

        $sql = "INSERT INTO experience_listings (listing_id, experience_type, duration,
                max_group_size, availability)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issis",
            $listingId,
            $this->experienceType,
            $this->duration,
            $this->maxGroupSize,
            $this->availability
        );

        if ($stmt->execute()) {
            return $listingId;
        } else {
            return "error";
        }
    }

    // ===========================
    // UPDATE EXPERIENCE LISTING
    // ===========================
    public function updateExperienceListing($listingId) {
        $conn = DBconnection::getInstance()->getConnection();

        $parentResult = $this->updateListing($listingId);
        if ($parentResult != "success") {
            return $parentResult;
        }

        $sql = "UPDATE experience_listings SET experience_type=?, duration=?,
                max_group_size=?, availability=? WHERE listing_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisi",
            $this->experienceType,
            $this->duration,
            $this->maxGroupSize,
            $this->availability,
            $listingId
        );

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // SET AVAILABILITY
    // Quick update from dashboard, without touching other fields
    // ===========================
    public function setAvailability($listingId, $availability) {
        $conn = DBconnection::getInstance()->getConnection();

        // verify ownership
        $checkSql = "SELECT l.id FROM listings l
                      JOIN experience_listings el ON l.id = el.listing_id
                      WHERE l.id = ? AND l.user_id = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ii", $listingId, $this->userId);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows == 0) {
            return "unauthorized";
        }

        $sql = "UPDATE experience_listings SET availability = ? WHERE listing_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $availability, $listingId);

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // GET EXPERIENCE LISTING DETAILS
    // Static — fetches listing + experience_listings joined
    // ===========================
    public static function getExperienceListingDetails($listingId) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "SELECT l.*, el.id as experience_listing_id, el.experience_type,
                el.duration, el.max_group_size, el.availability
                FROM listings l
                JOIN experience_listings el ON l.id = el.listing_id
                WHERE l.id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $listingId);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    // ===========================
    // GETTERS
    // ===========================
    public function getExperienceType() { return $this->experienceType; }
    public function getDuration()       { return $this->duration; }
    public function getMaxGroupSize()   { return $this->maxGroupSize; }
    public function getAvailability()   { return $this->availability; }

    // ===========================
    // SETTERS
    // ===========================
    public function setExperienceType($type) { $this->experienceType = $type; }
    public function setDuration($duration)   { $this->duration = $duration; }
    public function setMaxGroupSize($size)   { $this->maxGroupSize = $size; }
}
?>
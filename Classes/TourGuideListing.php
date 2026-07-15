<?php
require_once 'Listing.php';

class TourGuideListing extends Listing {

    private $languages;
    private $specialization;
    private $experienceYears;
    private $availability;

    public function __construct($userId, $title, $description, $location, $city, $price,
                                 $languages, $specialization, $experienceYears, $availability) {
        parent::__construct($userId, $title, $description, $location, $city, $price, 'tour_guide');

        $this->languages        = $languages;
        $this->specialization   = $specialization;
        $this->experienceYears  = $experienceYears;
        $this->availability     = $availability;
    }

    public function addTourGuideListing() {
        $conn = DBconnection::getInstance()->getConnection();

        $listingId = $this->addListing();

        if (!$listingId) {
            return "error";
        }

        $sql = "INSERT INTO tourguide_listings (listing_id, languages, specialization,
                experience_years, availability)
                VALUES (?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issis",
            $listingId,
            $this->languages,
            $this->specialization,
            $this->experienceYears,
            $this->availability
        );

        if ($stmt->execute()) {
            return $listingId;
        } else {
            return "error";
        }
    }

    public function updateTourGuideListing($listingId) {
        $conn = DBconnection::getInstance()->getConnection();

        $parentResult = $this->updateListing($listingId);
        if ($parentResult != "success") {
            return $parentResult;
        }

        $sql = "UPDATE tourguide_listings SET languages=?, specialization=?,
                experience_years=?, availability=? WHERE listing_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssisi",
            $this->languages,
            $this->specialization,
            $this->experienceYears,
            $this->availability,
            $listingId
        );

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    public function setAvailability($listingId, $availability) {
        $conn = DBconnection::getInstance()->getConnection();

        $checkSql = "SELECT l.id FROM listings l
                      JOIN tourguide_listings tl ON l.id = tl.listing_id
                      WHERE l.id = ? AND l.user_id = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ii", $listingId, $this->userId);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows == 0) {
            return "unauthorized";
        }

        $sql = "UPDATE tourguide_listings SET availability = ? WHERE listing_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $availability, $listingId);

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    public static function getTourGuideListingDetails($listingId) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "SELECT l.*, tl.id as tourguide_listing_id, tl.languages,
                tl.specialization, tl.experience_years, tl.availability
                FROM listings l
                JOIN tourguide_listings tl ON l.id = tl.listing_id
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
	
public function deleteTourGuideListing($listingId, $userId) {
    return $this->deleteListing($listingId, $userId);
}

    public function getLanguages()       { return $this->languages; }
    public function getSpecialization()  { return $this->specialization; }
    public function getExperienceYears() { return $this->experienceYears; }
    public function getAvailability()    { return $this->availability; }

    public function setLanguages($languages)      { $this->languages = $languages; }
    public function setSpecialization($spec)      { $this->specialization = $spec; }
    public function setExperienceYears($years)    { $this->experienceYears = $years; }
}
?>
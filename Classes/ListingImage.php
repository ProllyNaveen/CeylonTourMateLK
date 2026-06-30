<?php
require_once 'DBconnection.php';

class ListingImage {

    private $id;
    private $listingId;
    private $imagePath;

    public function __construct($listingId, $imagePath) {
        $this->listingId = $listingId;
        $this->imagePath = $imagePath;
    }

    // ===========================
    // SAVE
    // Created and called internally by Listing only (composition)
    // ===========================
    public function save() {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "INSERT INTO listing_images (listing_id, image_path) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $this->listingId, $this->imagePath);

        if ($stmt->execute()) {
            $this->id = $conn->insert_id;
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // GETTERS
    // ===========================
    public function getId()        { return $this->id; }
    public function getListingId() { return $this->listingId; }
    public function getImagePath() { return $this->imagePath; }

    // ===========================
    // SETTERS
    // ===========================
    public function setId($id)          { $this->id = $id; }
    public function setListingId($id)   { $this->listingId = $id; }
    public function setImagePath($path) { $this->imagePath = $path; }
}
?>
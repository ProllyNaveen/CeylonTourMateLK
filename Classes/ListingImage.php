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

    public function save() {
        $conn = DBconnection::getInstance()->getConnection();

        $sql  = "INSERT INTO listing_images (listing_id, image_path) VALUES (?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $this->listingId, $this->imagePath);

        if ($stmt->execute()) {
            $this->id = $conn->insert_id;
            return "success";
        } else {
            return "error";
        }
    }

    public static function getImagesByListing($listingId) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql  = "SELECT * FROM listing_images WHERE listing_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $listingId);
        $stmt->execute();
        $result = $stmt->get_result();

        $images = [];
        while ($row = $result->fetch_assoc()) {
            $images[] = $row;
        }
        return $images;
    }

    public static function deleteAllForListing($listingId) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql  = "DELETE FROM listing_images WHERE listing_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $listingId);
        $stmt->execute();
    }

    public function getId()        { return $this->id; }
    public function getListingId() { return $this->listingId; }
    public function getImagePath() { return $this->imagePath; }

    public function setId($id)          { $this->id = $id; }
    public function setListingId($id)   { $this->listingId = $id; }
    public function setImagePath($path) { $this->imagePath = $path; }
}
?>
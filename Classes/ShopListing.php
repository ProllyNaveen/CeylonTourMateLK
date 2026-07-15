<?php
require_once 'Listing.php';
require_once 'ListingImage.php';

class ShopListing extends Listing {

    private $shopType;
    private $openTime;
    private $closeTime;

    public function __construct($userId, $title, $description, $location, $city, $price,
                                 $shopType, $openTime, $closeTime) {
        parent::__construct($userId, $title, $description, $location, $city, $price, 'shop');

        $this->shopType  = $shopType;
        $this->openTime  = $openTime;
        $this->closeTime = $closeTime;
    }

    public function addShopListing() {
        $conn = DBconnection::getInstance()->getConnection();

        $listingId = $this->addListing();

        if (!$listingId) {
            return "error";
        }

        $sql = "INSERT INTO shop_listings (listing_id, shop_type, open_time, close_time)
                VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isss",
            $listingId,
            $this->shopType,
            $this->openTime,
            $this->closeTime
        );

        if ($stmt->execute()) {
            return $listingId;
        } else {
            return "error";
        }
    }

    public function updateShopListing($listingId) {
        $conn = DBconnection::getInstance()->getConnection();

        $parentResult = $this->updateListing($listingId);
        if ($parentResult != "success") {
            return $parentResult;
        }

        $sql = "UPDATE shop_listings SET shop_type=?, open_time=?, close_time=?
                WHERE listing_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssi",
            $this->shopType,
            $this->openTime,
            $this->closeTime,
            $listingId
        );

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }
	
	public function deleteShopListing($listingId, $userId) {
    return $this->deleteListing($listingId, $userId);
}
    public static function getShopListingDetails($listingId) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "SELECT l.*, sl.id as shop_listing_id, sl.shop_type, sl.open_time, sl.close_time
                FROM listings l
                JOIN shop_listings sl ON l.id = sl.listing_id
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

    public function getShopType()  { return $this->shopType; }
    public function getOpenTime()  { return $this->openTime; }
    public function getCloseTime() { return $this->closeTime; }

    public function setShopType($type)   { $this->shopType = $type; }
    public function setOpenTime($time)   { $this->openTime = $time; }
    public function setCloseTime($time)  { $this->closeTime = $time; }
}
?>
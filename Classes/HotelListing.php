<?php
require_once 'Listing.php';
require_once 'Room.php';

class HotelListing extends Listing {

    private $starRating;
    private $amenities;
    private $totalRooms;
    private $checkInTime;
    private $checkOutTime;

    public function __construct($userId, $title, $description, $location, $city, $price,
                                 $starRating, $amenities, $totalRooms, $checkInTime, $checkOutTime) {
        parent::__construct($userId, $title, $description, $location, $city, $price, 'hotel');

        $this->starRating   = $starRating;
        $this->amenities    = $amenities;
        $this->totalRooms   = $totalRooms;
        $this->checkInTime  = $checkInTime;
        $this->checkOutTime = $checkOutTime;
    }

    public function addHotelListing() {
        $conn = DBconnection::getInstance()->getConnection();

        $listingId = $this->addListing();

        if (!$listingId) {
            return "error";
        }

        $sql = "INSERT INTO hotel_listings (listing_id, star_rating, amenities,
                total_rooms, check_in_time, check_out_time)
                VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iisiss",
            $listingId,
            $this->starRating,
            $this->amenities,
            $this->totalRooms,
            $this->checkInTime,
            $this->checkOutTime
        );

        if ($stmt->execute()) {
            return $listingId;
        } else {
            return "error";
        }
    }

    public function updateHotelListing($listingId) {
        $conn = DBconnection::getInstance()->getConnection();

        $parentResult = $this->updateListing($listingId);
        if ($parentResult != "success") {
            return $parentResult;
        }

        $sql = "UPDATE hotel_listings SET star_rating=?, amenities=?, total_rooms=?,
                check_in_time=?, check_out_time=? WHERE listing_id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("isissi",
            $this->starRating,
            $this->amenities,
            $this->totalRooms,
            $this->checkInTime,
            $this->checkOutTime,
            $listingId
        );

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    public function addRoom($hotelListingId, $description, $roomType, $pricePerNight, $capacity) {
        $room = new Room($hotelListingId, $description, $roomType, $pricePerNight, $capacity);
        return $room->save();
    }

    public static function getHotelListingDetails($listingId) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "SELECT l.*, hl.id as hotel_listing_id, hl.star_rating, hl.amenities,
                hl.total_rooms, hl.check_in_time, hl.check_out_time
                FROM listings l
                JOIN hotel_listings hl ON l.id = hl.listing_id
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
public function deleteHotelListing($listingId, $userId) {
    return $this->deleteListing($listingId, $userId);
}

    public function getStarRating()   { return $this->starRating; }
    public function getAmenities()    { return $this->amenities; }
    public function getTotalRooms()   { return $this->totalRooms; }
    public function getCheckInTime()  { return $this->checkInTime; }
    public function getCheckOutTime() { return $this->checkOutTime; }

    public function setStarRating($rating)    { $this->starRating = $rating; }
    public function setAmenities($amenities)  { $this->amenities = $amenities; }
    public function setTotalRooms($total)     { $this->totalRooms = $total; }
    public function setCheckInTime($time)     { $this->checkInTime = $time; }
    public function setCheckOutTime($time)    { $this->checkOutTime = $time; }
}
?>
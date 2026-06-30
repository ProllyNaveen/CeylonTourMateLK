<?php
require_once 'DBconnection.php';

class Room {

    private $id;
    private $hotelListingId;
    private $description;
    private $roomType;
    private $pricePerNight;
    private $capacity;
    private $isAvailable;

    public function __construct($hotelListingId, $description, $roomType, $pricePerNight, $capacity) {
        $this->hotelListingId = $hotelListingId;
        $this->description    = $description;
        $this->roomType        = $roomType;
        $this->pricePerNight   = $pricePerNight;
        $this->capacity         = $capacity;
        $this->isAvailable      = 1;
    }

    // ===========================
    // SAVE
    // Created and called internally by HotelListing (composition)
    // ===========================
    public function save() {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "INSERT INTO rooms (hotel_listing_id, description, room_type,
                price_per_night, capacity, is_available)
                VALUES (?, ?, ?, ?, ?, 1)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issdi",
            $this->hotelListingId,
            $this->description,
            $this->roomType,
            $this->pricePerNight,
            $this->capacity
        );

        if ($stmt->execute()) {
            $this->id = $conn->insert_id;
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // SET ROOM AVAILABILITY
    // ===========================
    public function setRoomAvailability($roomId, $isAvailable) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "UPDATE rooms SET is_available = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ii", $isAvailable, $roomId);

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // UPDATE ROOM
    // ===========================
    public function updateRoom($roomId) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "UPDATE rooms SET description=?, room_type=?, price_per_night=?, capacity=?
                WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssdii",
            $this->description,
            $this->roomType,
            $this->pricePerNight,
            $this->capacity,
            $roomId
        );

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // DELETE ROOM
    // ===========================
    public function deleteRoom($roomId) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "DELETE FROM rooms WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $roomId);

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // GET ROOMS BY HOTEL LISTING
    // Static — used for manage rooms page and booking page
    // ===========================
    public static function getRoomsByHotelListing($hotelListingId, $onlyAvailable = false) {
        $conn = DBconnection::getInstance()->getConnection();

        if ($onlyAvailable) {
            $sql = "SELECT * FROM rooms WHERE hotel_listing_id = ? AND is_available = 1
                    ORDER BY room_type ASC";
        } else {
            $sql = "SELECT * FROM rooms WHERE hotel_listing_id = ?
                    ORDER BY room_type ASC";
        }
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $hotelListingId);
        $stmt->execute();
        $result = $stmt->get_result();

        $rooms = [];
        while ($row = $result->fetch_assoc()) {
            $rooms[] = $row;
        }
        return $rooms;
    }

    // ===========================
    // GETTERS
    // ===========================
    public function getId()             { return $this->id; }
    public function getHotelListingId() { return $this->hotelListingId; }
    public function getDescription()    { return $this->description; }
    public function getRoomType()       { return $this->roomType; }
    public function getPricePerNight()  { return $this->pricePerNight; }
    public function getCapacity()       { return $this->capacity; }
    public function getIsAvailable()    { return $this->isAvailable; }

    // ===========================
    // SETTERS
    // ===========================
    public function setId($id)                    { $this->id = $id; }
    public function setHotelListingId($id)        { $this->hotelListingId = $id; }
    public function setDescription($desc)         { $this->description = $desc; }
    public function setRoomType($type)            { $this->roomType = $type; }
    public function setPricePerNight($price)      { $this->pricePerNight = $price; }
    public function setCapacity($capacity)        { $this->capacity = $capacity; }
    public function setIsAvailable($available)    { $this->isAvailable = $available; }
}
?>
<?php
require_once 'DBconnection.php';

class Listing {

    protected $id;
    protected $userId;
    protected $title;
    protected $description;
    protected $location;
    protected $city;
    protected $price;
    protected $status;
    protected $type;

    public function __construct($userId, $title, $description, $location, $city, $price, $type) {
        $this->userId      = $userId;
        $this->title       = $title;
        $this->description = $description;
        $this->location    = $location;
        $this->city        = $city;
        $this->price       = $price;
        $this->status      = 'pending';
        $this->type        = $type;
    }

    // ===========================
    // ADD LISTING
    // Inserts the parent row, returns new id for subtype insert
    // ===========================
    public function addListing() {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "INSERT INTO listings (user_id, title, description, location, city,
                price, status, type)
                VALUES (?, ?, ?, ?, ?, ?, 'pending', ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("issssds",
            $this->userId,
            $this->title,
            $this->description,
            $this->location,
            $this->city,
            $this->price,
            $this->type
        );

        if ($stmt->execute()) {
            $this->id = $conn->insert_id;
            return $this->id;
        } else {
            return false;
        }
    }

    // ===========================
    // UPDATE LISTING
    // ===========================
    public function updateListing($listingId) {
        $conn = DBconnection::getInstance()->getConnection();

        // verify ownership
        $checkSql = "SELECT id FROM listings WHERE id = ? AND user_id = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ii", $listingId, $this->userId);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows == 0) {
            return "unauthorized";
        }

        $sql = "UPDATE listings SET title=?, description=?, location=?, city=?, price=?
                WHERE id=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssdi",
            $this->title,
            $this->description,
            $this->location,
            $this->city,
            $this->price,
            $listingId
        );

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // DELETE LISTING
    // Note: caller must delete subtype row and images first (FK order)
    // ===========================
    public function deleteListing($listingId, $userId) {
        $conn = DBconnection::getInstance()->getConnection();

        $checkSql = "SELECT id FROM listings WHERE id = ? AND user_id = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ii", $listingId, $userId);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows == 0) {
            return "unauthorized";
        }

        $sql = "DELETE FROM listings WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $listingId);

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // UPLOAD IMAGE
    // ===========================

	public function uploadImage($listingId, $imagePaths) {
		require_once 'ListingImage.php';

		if (!is_array($imagePaths)) {
			$imagePaths = [$imagePaths];
		}

		$results = [];
		foreach ($imagePaths as $path) {
			$image = new ListingImage($listingId, $path);
			$results[] = $image->save();
		}

		return $results;
	}

    // ===========================
    // GET AVERAGE RATING
    // ===========================
    public function getAverageRating($listingId) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "SELECT AVG(rating) as avg_rating, COUNT(id) as review_count
                FROM reviews WHERE listing_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $listingId);
        $stmt->execute();
        $result = $stmt->get_result();
        $row = $result->fetch_assoc();

        return [
            'avg_rating'   => $row['avg_rating'] ? round($row['avg_rating'], 1) : null,
            'review_count' => $row['review_count']
        ];
    }

    // ===========================
    // APPROVE / REJECT LISTING (admin only)
    // ===========================
    public function approveRejectListing($listingId, $action) {
        $conn = DBconnection::getInstance()->getConnection();

        $newStatus = ($action == "approve") ? "approved" : "rejected";

        $sql = "UPDATE listings SET status = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $newStatus, $listingId);

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // GET LISTINGS BY USER
    // Static — fetches a partner's own listings
    // ===========================
    public static function getListingsByUser($userId, $status = null) {
        $conn = DBconnection::getInstance()->getConnection();

        if ($status) {
            $sql = "SELECT * FROM listings WHERE user_id = ? AND status = ?
                    ORDER BY created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("is", $userId, $status);
        } else {
            $sql = "SELECT * FROM listings WHERE user_id = ?
                    ORDER BY created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $listings = [];
        while ($row = $result->fetch_assoc()) {
            $listings[] = $row;
        }
        return $listings;
    }

    // ===========================
    // GET PENDING LISTINGS (admin only)
    // Static — fetches all pending listings, optionally by type
    // ===========================
    public static function getPendingListings($type = null) {
        $conn = DBconnection::getInstance()->getConnection();

        if ($type) {
            $sql = "SELECT l.*, u.firstname, u.lastname
                    FROM listings l
                    JOIN users u ON l.user_id = u.id
                    WHERE l.status = 'pending' AND l.type = ?
                    ORDER BY l.created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $type);
        } else {
            $sql = "SELECT l.*, u.firstname, u.lastname
                    FROM listings l
                    JOIN users u ON l.user_id = u.id
                    WHERE l.status = 'pending'
                    ORDER BY l.created_at DESC";
            $stmt = $conn->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $listings = [];
        while ($row = $result->fetch_assoc()) {
            $listings[] = $row;
        }
        return $listings;
    }

    // ===========================
    // SEARCH LISTINGS (explore page)
    // Static — used for tourist browsing/search
    // ===========================
    public static function searchListings($keyword = null, $type = null, $city = null, $sort = 'latest') {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "SELECT l.*, u.firstname, u.lastname,
                AVG(r.rating) as avg_rating,
                COUNT(DISTINCT r.id) as review_count,
                (SELECT image_path FROM listing_images WHERE listing_id = l.id LIMIT 1) as thumbnail
                FROM listings l
                JOIN users u ON l.user_id = u.id
                LEFT JOIN reviews r ON l.id = r.listing_id
                WHERE l.status = 'approved'";

        $params = [];
        $types = "";

        if ($keyword) {
            $sql .= " AND (l.title LIKE ? OR l.description LIKE ? OR l.city LIKE ?)";
            $likeKeyword = "%$keyword%";
            $params[] = $likeKeyword;
            $params[] = $likeKeyword;
            $params[] = $likeKeyword;
            $types .= "sss";
        }
        if ($type) {
            $sql .= " AND l.type = ?";
            $params[] = $type;
            $types .= "s";
        }
        if ($city) {
            $sql .= " AND l.city = ?";
            $params[] = $city;
            $types .= "s";
        }

        $sql .= " GROUP BY l.id";

        switch ($sort) {
            case 'price_low':  $sql .= " ORDER BY l.price ASC"; break;
            case 'price_high': $sql .= " ORDER BY l.price DESC"; break;
            case 'rating':     $sql .= " ORDER BY avg_rating DESC"; break;
            default:           $sql .= " ORDER BY l.created_at DESC"; break;
        }

        $stmt = $conn->prepare($sql);
        if (!empty($params)) {
            $stmt->bind_param($types, ...$params);
        }
        $stmt->execute();
        $result = $stmt->get_result();

        $listings = [];
        while ($row = $result->fetch_assoc()) {
            $listings[] = $row;
        }
        return $listings;
    }

    // ===========================
    // GETTERS
    // ===========================
    public function getId()          { return $this->id; }
    public function getUserId()      { return $this->userId; }
    public function getTitle()       { return $this->title; }
    public function getDescription() { return $this->description; }
    public function getLocation()    { return $this->location; }
    public function getCity()        { return $this->city; }
    public function getPrice()       { return $this->price; }
    public function getStatus()      { return $this->status; }
    public function getType()        { return $this->type; }

    // ===========================
    // SETTERS
    // ===========================
    public function setId($id)               { $this->id = $id; }
    public function setUserId($id)           { $this->userId = $id; }
    public function setTitle($title)         { $this->title = $title; }
    public function setDescription($desc)    { $this->description = $desc; }
    public function setLocation($location)   { $this->location = $location; }
    public function setCity($city)           { $this->city = $city; }
    public function setPrice($price)         { $this->price = $price; }
    public function setStatus($status)       { $this->status = $status; }
    public function setType($type)           { $this->type = $type; }
}
?>
<?php
require_once 'DBconnection.php';

class Message {

    private $id;
    private $senderId;
    private $receiverId;
    private $listingId;
    private $message;
    private $isRead;

    public function __construct($senderId, $receiverId, $listingId, $message) {
        $this->senderId   = $senderId;
        $this->receiverId = $receiverId;
        $this->listingId  = $listingId;
        $this->message    = $message;
        $this->isRead     = 0;
    }

    // ===========================
    // SEND MESSAGE
    // ===========================
    public function sendMessage() {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "INSERT INTO messages (sender_id, receiver_id, listing_id, message, is_read)
                VALUES (?, ?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiis",
            $this->senderId,
            $this->receiverId,
            $this->listingId,
            $this->message
        );

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // REPLY TO MESSAGE
    // Same as sendMessage but semantically a reply
    // sender and receiver are swapped from the original
    // ===========================
    public function replyToMessage() {
        return $this->sendMessage();
    }

    // ===========================
    // GET CONVERSATIONS
    // Static — fetches all unique conversations for a user
    // ===========================
    public static function getConversations($userId) {
    $conn = DBconnection::getInstance()->getConnection();

    $sql = "SELECT 
                m.*,
                CASE WHEN m.sender_id = ? THEN r.firstname ELSE s.firstname END as firstname,
                CASE WHEN m.sender_id = ? THEN r.lastname ELSE s.lastname END as lastname,
                CASE WHEN m.sender_id = ? THEN r.profile_pic ELSE s.profile_pic END as profile_pic,
                CASE WHEN m.sender_id = ? THEN m.receiver_id ELSE m.sender_id END as other_user_id,
                l.title as listing_title,
                COUNT(CASE WHEN m.is_read = 0 AND m.receiver_id = ? THEN 1 END) as unread_count
            FROM messages m
            JOIN users s ON m.sender_id = s.id
            JOIN users r ON m.receiver_id = r.id
            JOIN listings l ON m.listing_id = l.id
            WHERE m.sender_id = ? OR m.receiver_id = ?
            GROUP BY
                CASE WHEN m.sender_id = ? THEN m.receiver_id ELSE m.sender_id END,
                m.listing_id
            ORDER BY m.created_at DESC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("iiiiiiii",
        $userId, $userId, $userId, $userId,
        $userId, $userId, $userId, $userId
    );
    $stmt->execute();
    $result = $stmt->get_result();

    $conversations = [];
    while ($row = $result->fetch_assoc()) {
        $conversations[] = $row;
    }
    return $conversations;
}

    // ===========================
    // GET THREAD
    // Static — fetches all messages in a conversation
    // ===========================
    public static function getThread($userId, $otherUserId, $listingId) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "SELECT m.*, u.firstname, u.lastname, u.profile_pic
                FROM messages m
                JOIN users u ON m.sender_id = u.id
                WHERE ((m.sender_id = ? AND m.receiver_id = ?)
                OR (m.sender_id = ? AND m.receiver_id = ?))
                AND m.listing_id = ?
                ORDER BY m.created_at ASC";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iiiii",
            $userId, $otherUserId,
            $otherUserId, $userId,
            $listingId
        );
        $stmt->execute();
        $result = $stmt->get_result();

        $thread = [];
        while ($row = $result->fetch_assoc()) {
            $thread[] = $row;
        }
        return $thread;
    }

    // ===========================
    // MARK THREAD AS READ
    // ===========================
    public static function markThreadAsRead($receiverId, $senderId, $listingId) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "UPDATE messages SET is_read = 1
                WHERE receiver_id = ? AND sender_id = ? AND listing_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iii", $receiverId, $senderId, $listingId);
        $stmt->execute();
    }

    // ===========================
    // GETTERS
    // ===========================
    public function getId()         { return $this->id; }
    public function getSenderId()   { return $this->senderId; }
    public function getReceiverId() { return $this->receiverId; }
    public function getListingId()  { return $this->listingId; }
    public function getMessage()    { return $this->message; }
    public function getIsRead()     { return $this->isRead; }

    // ===========================
    // SETTERS
    // ===========================
    public function setId($id)               { $this->id = $id; }
    public function setSenderId($id)         { $this->senderId = $id; }
    public function setReceiverId($id)       { $this->receiverId = $id; }
    public function setListingId($id)        { $this->listingId = $id; }
    public function setMessage($message)     { $this->message = $message; }
    public function setIsRead($isRead)       { $this->isRead = $isRead; }
}
?>
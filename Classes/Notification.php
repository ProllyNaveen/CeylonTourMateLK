<?php
require_once 'DBconnection.php';

class Notification {

    private $id;
    private $senderId;
    private $receiverId;
    private $message;
    private $isRead;

    public function __construct($senderId, $receiverId, $message) {
        $this->senderId   = $senderId;
        $this->receiverId = $receiverId;
        $this->message    = $message;
        $this->isRead     = 0;
    }

    // ===========================
    // SEND NOTIFICATION
    // ===========================
    public function sendNotification() {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "INSERT INTO notifications (sender_id, receiver_id, message, is_read)
                VALUES (?, ?, ?, 0)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iis",
            $this->senderId,
            $this->receiverId,
            $this->message
        );

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // MARK AS READ
    // ===========================
    public function markAsRead($receiverId) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "UPDATE notifications SET is_read = 1
                WHERE receiver_id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $receiverId);

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // GET NOTIFICATIONS
    // Static method — fetches all notifications for a user
    // ===========================
    public static function getNotifications($receiverId, $filter = null) {
        $conn = DBconnection::getInstance()->getConnection();

        if ($filter == "unread") {
            $sql = "SELECT n.*, u.firstname, u.lastname, u.profile_pic
                    FROM notifications n
                    JOIN users u ON n.sender_id = u.id
                    WHERE n.receiver_id = ? AND n.is_read = 0
                    ORDER BY n.id DESC";
        } elseif ($filter == "read") {
            $sql = "SELECT n.*, u.firstname, u.lastname, u.profile_pic
                    FROM notifications n
                    JOIN users u ON n.sender_id = u.id
                    WHERE n.receiver_id = ? AND n.is_read = 1
                    ORDER BY n.id DESC";
        } else {
            $sql = "SELECT n.*, u.firstname, u.lastname, u.profile_pic
                    FROM notifications n
                    JOIN users u ON n.sender_id = u.id
                    WHERE n.receiver_id = ?
                    ORDER BY n.id DESC";
        }

        $stmt = $conn->prepare($sql);
        $stmt->bind_param("i", $receiverId);
        $stmt->execute();
        $result = $stmt->get_result();

        $notifications = [];
        while ($row = $result->fetch_assoc()) {
            $notifications[] = $row;
        }
        return $notifications;
    }

    // ===========================
    // GETTERS
    // ===========================
    public function getId()         { return $this->id; }
    public function getSenderId()   { return $this->senderId; }
    public function getReceiverId() { return $this->receiverId; }
    public function getMessage()    { return $this->message; }
    public function getIsRead()     { return $this->isRead; }

    // ===========================
    // SETTERS
    // ===========================
    public function setId($id)               { $this->id = $id; }
    public function setSenderId($id)         { $this->senderId = $id; }
    public function setReceiverId($id)       { $this->receiverId = $id; }
    public function setMessage($message)     { $this->message = $message; }
    public function setIsRead($isRead)       { $this->isRead = $isRead; }
}
?>
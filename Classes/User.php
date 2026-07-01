<?php
require_once 'DBconnection.php';

class User {

    private $id;
    private $firstname;
    private $lastname;
    private $username;
    private $email;
    private $password;
    private $phone;
    private $profilePic;
    private $role;
    private $isSuspended;
    private $isApproved;
    private $securityQuestion;
    private $securityAnswer;

    public function __construct($firstname, $lastname, $username, $email, $password,
                                $phone, $profilePic, $role, $isSuspended, $isApproved,
                                $securityQuestion, $securityAnswer) {
        $this->firstname        = $firstname;
        $this->lastname         = $lastname;
        $this->username         = $username;
        $this->email            = $email;
        $this->password         = $password;
        $this->phone            = $phone;
        $this->profilePic       = $profilePic;
        $this->role             = $role;
        $this->isSuspended      = $isSuspended;
        $this->isApproved       = $isApproved;
        $this->securityQuestion = $securityQuestion;
        $this->securityAnswer   = $securityAnswer;
    }

    // ===========================
    // REGISTER
    // ===========================
    public function register() {
        $conn = DBconnection::getInstance()->getConnection();

        // check if username or email already exists
        $checkSql = "SELECT id FROM users WHERE username = ? OR email = ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ss", $this->username, $this->email);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            return "exists";
        }

        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $sql = "INSERT INTO users (firstname, lastname, username, email, password, phone,
                profile_pic, role, is_suspended, is_approved, security_question, security_answer)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssssssssiiss",
            $this->firstname,
            $this->lastname,
            $this->username,
            $this->email,
            $hashedPassword,
            $this->phone,
            $this->profilePic,
            $this->role,
            $this->isSuspended,
            $this->isApproved,
            $this->securityQuestion,
            $this->securityAnswer
        );

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // LOGIN
    // ===========================
    public function login() {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "SELECT * FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $this->username, $this->username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            $user = $result->fetch_assoc();

            if ($user['is_suspended'] == 1) {
                return "suspended";
            }

            if ($user['is_approved'] == 0 && $user['role'] != 'tourist') {
                return "notapproved";
            }

            if (password_verify($this->password, $user['password'])) {
                session_start();
                $_SESSION['user_id']   = $user['id'];
                $_SESSION['username']  = $user['username'];
                $_SESSION['role']      = $user['role'];
                $_SESSION['firstname'] = $user['firstname'];
                return "success";
            } else {
                return "wrongpassword";
            }
        } else {
            return "notfound";
        }
    }

    // ===========================
    // LOGOUT
    // ===========================
    public function logout() {
        session_start();
        session_unset();
        session_destroy();
        return "success";
    }

    // ===========================
    // EDIT PROFILE
    // ===========================
    public function editProfile($currentPassword, $newPassword) {
        $conn = DBconnection::getInstance()->getConnection();

        $checkSql = "SELECT id FROM users WHERE (username = ? OR email = ?) AND id != ?";
        $checkStmt = $conn->prepare($checkSql);
        $checkStmt->bind_param("ssi", $this->username, $this->email, $this->id);
        $checkStmt->execute();
        $checkStmt->store_result();

        if ($checkStmt->num_rows > 0) {
            return "exists";
        }

        if ($newPassword != "") {
            $getSql = "SELECT password FROM users WHERE id = ?";
            $getStmt = $conn->prepare($getSql);
            $getStmt->bind_param("i", $this->id);
            $getStmt->execute();
            $getResult = $getStmt->get_result();
            $userData = $getResult->fetch_assoc();

            if (!password_verify($currentPassword, $userData['password'])) {
                return "wrongpassword";
            }
            $hashedNew = password_hash($newPassword, PASSWORD_DEFAULT);
        } else {
            $hashedNew = null;
        }

        if ($hashedNew) {
            $sql = "UPDATE users SET firstname=?, lastname=?, username=?, email=?,
                    phone=?, profile_pic=?, password=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssssssi",
                $this->firstname, $this->lastname, $this->username, $this->email,
                $this->phone, $this->profilePic, $hashedNew, $this->id
            );
        } else {
            $sql = "UPDATE users SET firstname=?, lastname=?, username=?, email=?,
                    phone=?, profile_pic=? WHERE id=?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("ssssssi",
                $this->firstname, $this->lastname, $this->username, $this->email,
                $this->phone, $this->profilePic, $this->id
            );
        }

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // FIND ACCOUNT (forgot password step 1)
    // ===========================
    public function findAccount() {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "SELECT id, security_question FROM users WHERE username = ? OR email = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ss", $this->username, $this->username);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            return $result->fetch_assoc();
        } else {
            return null;
        }
    }

    // ===========================
    // VERIFY SECURITY ANSWER (forgot password step 2)
    // ===========================
    public function verifySecurityAnswer($userId) {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "SELECT id FROM users WHERE id = ? AND security_answer = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("is", $userId, $this->securityAnswer);
        $stmt->execute();
        $result = $stmt->get_result();

        if ($result->num_rows == 1) {
            return "success";
        } else {
            return "wrong";
        }
    }

    // ===========================
    // RESET PASSWORD (forgot password step 3)
    // ===========================
    public function resetPassword($userId) {
        $conn = DBconnection::getInstance()->getConnection();

        $hashedPassword = password_hash($this->password, PASSWORD_DEFAULT);

        $sql = "UPDATE users SET password = ? WHERE id = ?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("si", $hashedPassword, $userId);

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // MANAGE ADMIN ACCOUNTS (admin only)
    // ===========================
    public function manageAdminAccounts() {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "SELECT id, firstname, lastname, username, email, created_at
                FROM users WHERE role = 'admin' ORDER BY created_at DESC";
        $stmt = $conn->prepare($sql);
        $stmt->execute();
        $result = $stmt->get_result();

        $admins = [];
        while ($row = $result->fetch_assoc()) {
            $admins[] = $row;
        }
        return $admins;
    }

    // ===========================
    // VIEW PENDING REQUESTS (admin only)
    // ===========================
    public function viewPendingRequests($role = null) {
        $conn = DBconnection::getInstance()->getConnection();

        if ($role) {
            $sql = "SELECT u.*, tl.license_image, tl.license_number
                    FROM users u
                    LEFT JOIN tourguide_licenses tl ON u.id = tl.user_id
                    WHERE u.is_approved = 0 AND u.role = ?
                    ORDER BY u.created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $role);
        } else {
            $sql = "SELECT u.*, tl.license_image, tl.license_number
                    FROM users u
                    LEFT JOIN tourguide_licenses tl ON u.id = tl.user_id
                    WHERE u.is_approved = 0
                    AND u.role != 'tourist' AND u.role != 'admin'
                    ORDER BY u.created_at DESC";
            $stmt = $conn->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $requests = [];
        while ($row = $result->fetch_assoc()) {
            $requests[] = $row;
        }
        return $requests;
    }

    // ===========================
    // APPROVE / REJECT REGISTRATION (admin only)
    // ===========================
    public function approveRejectRegistration($userId, $action) {
        $conn = DBconnection::getInstance()->getConnection();

        if ($action == "approve") {
            $sql = "UPDATE users SET is_approved = 1 WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
        } else {
            $sql = "DELETE FROM users WHERE id = ?";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("i", $userId);
        }

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    // ===========================
    // MANAGE USER ACCOUNTS (admin only)
    // ===========================
    public function manageUserAccounts($role = null) {
        $conn = DBconnection::getInstance()->getConnection();

        if ($role) {
            $sql = "SELECT * FROM users WHERE role = ? AND role != 'admin'
                    ORDER BY created_at DESC";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("s", $role);
        } else {
            $sql = "SELECT * FROM users WHERE role != 'admin'
                    ORDER BY created_at DESC";
            $stmt = $conn->prepare($sql);
        }

        $stmt->execute();
        $result = $stmt->get_result();

        $users = [];
        while ($row = $result->fetch_assoc()) {
            $users[] = $row;
        }
        return $users;
    }

    // ===========================
    // GETTERS
    // ===========================
    public function getId()        { return $this->id; }
    public function getFirstname() { return $this->firstname; }
    public function getLastname()  { return $this->lastname; }
    public function getUsername()  { return $this->username; }
    public function getEmail()     { return $this->email; }
    public function getRole()      { return $this->role; }

    // ===========================
    // SETTERS
    // ===========================
    public function setId($id)               { $this->id = $id; }
    public function setFirstname($firstname) { $this->firstname = $firstname; }
    public function setLastname($lastname)   { $this->lastname = $lastname; }
    public function setUsername($username)   { $this->username = $username; }
    public function setEmail($email)         { $this->email = $email; }
    public function setPhone($phone)         { $this->phone = $phone; }
    public function setProfilePic($pic)      { $this->profilePic = $pic; }
}
?>
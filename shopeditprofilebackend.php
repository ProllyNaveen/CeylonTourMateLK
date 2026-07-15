<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/User.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    require_once 'Classes/session.php';
    checkRole(['shop_owner']);

    $userId          = $_SESSION['user_id'];
    $firstname       = trim($_POST['firstname']);
    $lastname        = trim($_POST['lastname']);
    $username        = trim($_POST['username']);
    $email           = trim($_POST['email']);
    $phone           = trim($_POST['phone']);
    $currentPassword = $_POST['current_password'];
    $newPassword     = $_POST['new_password'];

    
    $profilePic = null;
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $fileType     = $_FILES['profile_pic']['type'];

        if (in_array($fileType, $allowedTypes)) {
            $uploadDir  = 'uploads/profiles/';
            $fileName   = time() . '_' . basename($_FILES['profile_pic']['name']);
            $uploadPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadPath)) {
                $profilePic = $uploadPath;
            }
        }
    }

    
    if (!$profilePic) {
        $conn      = DBconnection::getInstance()->getConnection();
        $picSql    = "SELECT profile_pic FROM users WHERE id = ?";
        $picStmt   = $conn->prepare($picSql);
        $picStmt->bind_param("i", $userId);
        $picStmt->execute();
        $picResult = $picStmt->get_result();
        $picRow    = $picResult->fetch_assoc();
        $profilePic = $picRow['profile_pic'];
    }

    $user = new User(
        $firstname, $lastname, $username, $email, null,
        $phone, $profilePic, null, null, null, null, null
    );
    $user->setId($userId);

    $result = $user->editProfile($currentPassword, $newPassword);

    switch ($result) {
        case "exists":
            header("Location: shopeditprofile.php?exists=1");
            break;
        case "wrongpassword":
            header("Location: shopeditprofile.php?wrongpassword=1");
            break;
        case "success":
            $_SESSION['firstname'] = $firstname;
            header("Location: shopeditprofile.php?success=1");
            break;
        default:
            header("Location: shopeditprofile.php?error=1");
            break;
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: shopeditprofile.php?error=1");
    exit();
}
?>
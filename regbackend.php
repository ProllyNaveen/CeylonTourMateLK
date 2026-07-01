<?php
require_once 'Classes/DBconnection.php';
require_once 'Classes/User.php';
require_once 'Classes/TourGuideLicense.php';

try {
    if (session_status() == PHP_SESSION_NONE) {
        session_start();
    }

    // get form data
    $firstname        = trim($_POST['firstname']);
    $lastname         = trim($_POST['lastname']);
    $username         = trim($_POST['username']);
    $email            = trim($_POST['email']);
    $password         = $_POST['password'];
    $phone            = trim($_POST['phone']);
    $role             = $_POST['role'];
    $securityQuestion = $_POST['security_question'];
    $securityAnswer   = trim($_POST['security_answer']);

    // handle profile pic upload
    $profilePic = 'images/default.png';
    if (isset($_FILES['profile_pic']) && $_FILES['profile_pic']['error'] == 0) {
        $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg'];
        $fileType = $_FILES['profile_pic']['type'];

        if (in_array($fileType, $allowedTypes)) {
            $uploadDir  = 'uploads/profiles/';
            $fileName   = time() . '_' . basename($_FILES['profile_pic']['name']);
            $uploadPath = $uploadDir . $fileName;

            if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $uploadPath)) {
                $profilePic = $uploadPath;
            }
        }
    }

    // partners need admin approval, tourists are auto approved
    $isApproved = ($role == 'tourist') ? 1 : 0;

    // create User object and register
    $user = new User(
        $firstname, $lastname, $username, $email, $password,
        $phone, $profilePic, $role, 0, $isApproved,
        $securityQuestion, $securityAnswer
    );

    $result = $user->register();

    if ($result == "exists") {
        header("Location: regform.php?exists=1");
        exit();
    }

    if ($result != "success") {
        header("Location: regform.php?error=1");
        exit();
    }

    // if tour guide, handle license upload
    if ($role == 'tour_guide') {
        // get the newly registered user's id
        $conn = DBconnection::getInstance()->getConnection();
        $idSql = "SELECT id FROM users WHERE username = ?";
        $idStmt = $conn->prepare($idSql);
        $idStmt->bind_param("s", $username);
        $idStmt->execute();
        $idResult = $idStmt->get_result();
        $newUser = $idResult->fetch_assoc();
        $newUserId = $newUser['id'];

        $licenseNumber = trim($_POST['license_number']);
        $licenseImage  = '';

        if (isset($_FILES['license_image']) && $_FILES['license_image']['error'] == 0) {
            $allowedTypes = ['image/jpeg', 'image/png', 'image/jpg', 'application/pdf'];
            $fileType = $_FILES['license_image']['type'];

            if (in_array($fileType, $allowedTypes)) {
                $uploadDir  = 'uploads/licenses/';
                $fileName   = time() . '_' . basename($_FILES['license_image']['name']);
                $uploadPath = $uploadDir . $fileName;

                if (move_uploaded_file($_FILES['license_image']['tmp_name'], $uploadPath)) {
                    $licenseImage = $uploadPath;
                }
            }
        }

        $license = new TourGuideLicense($newUserId, $licenseNumber, $licenseImage);
        $licenseResult = $license->submitLicense();

        if ($licenseResult != "success") {
            header("Location: regform.php?error=1");
            exit();
        }
    }

    // redirect based on role
    if ($role == 'tourist') {
        // tourist auto approved, send to login
        header("Location: login.php?registered=1");
    } else {
        // partners need admin approval first
        header("Location: login.php?pending=1");
    }
    exit();

} catch (Exception $e) {
    error_log($e->getMessage());
    header("Location: regform.php?error=1");
    exit();
}
?>
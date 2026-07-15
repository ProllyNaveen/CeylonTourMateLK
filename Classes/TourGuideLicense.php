<?php
require_once 'DBconnection.php';

class TourGuideLicense {

    private $id;
    private $userId;
    private $licenseNumber;
    private $licenseImage;

    public function __construct($userId, $licenseNumber, $licenseImage) {
        $this->userId        = $userId;
        $this->licenseNumber = $licenseNumber;
        $this->licenseImage  = $licenseImage;
    }

    public function submitLicense() {
        $conn = DBconnection::getInstance()->getConnection();

        $sql = "INSERT INTO tourguide_licenses (user_id, license_number, license_image)
                VALUES (?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("iss",
            $this->userId,
            $this->licenseNumber,
            $this->licenseImage
        );

        if ($stmt->execute()) {
            return "success";
        } else {
            return "error";
        }
    }

    public function getId()            { return $this->id; }
    public function getUserId()        { return $this->userId; }
    public function getLicenseNumber() { return $this->licenseNumber; }
    public function getLicenseImage()  { return $this->licenseImage; }

    public function setId($id)                    { $this->id = $id; }
    public function setUserId($userId)            { $this->userId = $userId; }
    public function setLicenseNumber($number)     { $this->licenseNumber = $number; }
    public function setLicenseImage($image)       { $this->licenseImage = $image; }
}
?>
<?php

class DBconnection {

    private static $instance = null;
    private $connection;

    private $host     = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "ceylontourmatelk";

    // ===========================
    // PRIVATE CONSTRUCTOR
    // Throws an exception instead of die() so calling code can catch it gracefully
    // ===========================
    private function __construct() {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->connection->connect_error) {
            throw new Exception("Database connection failed: " . $this->connection->connect_error);
        }

        $this->connection->set_charset("utf8mb4");
    }

    // ===========================
    // GET INSTANCE
    // Returns the single shared instance of this class (Singleton pattern)
    // ===========================
    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DBconnection();
        }
        return self::$instance;
    }

    // ===========================
    // GET CONNECTION
    // Returns the actual mysqli connection object to run queries on
    // ===========================
    public function getConnection() {
        return $this->connection;
    }

}
?>
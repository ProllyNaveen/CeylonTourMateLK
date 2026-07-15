<?php

class DBconnection {

    private static $instance = null;
    private $connection;

    private $host     = "localhost";
    private $username = "root";
    private $password = "";
    private $database = "ceylontourmatelk";

    private function __construct() {
        $this->connection = new mysqli($this->host, $this->username, $this->password, $this->database);

        if ($this->connection->connect_error) {
            throw new Exception("Database connection failed: " . $this->connection->connect_error);
        }

        $this->connection->set_charset("utf8mb4");
    }

    public static function getInstance() {
        if (self::$instance == null) {
            self::$instance = new DBconnection();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

}
?>
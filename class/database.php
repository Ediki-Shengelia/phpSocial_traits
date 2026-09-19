<?php

date_default_timezone_set("Asia/Tbilisi");

require_once __DIR__ . '/../includes/config.php';

class Database
{
    public $conn;
    private $host = DB_HOST;
    private $user = DB_USER;
    private $pass = DB_PASS;
    private $name = DB_NAME;
    private $port = DB_PORT;
    public function getConnection()
    {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->name};port={$this->port}";
            $this->conn = new PDO($dsn, $this->user, $this->pass);
            $this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        } catch (PDOException $exception) {
            die("Connection Error" . $exception->getMessage());
        }
    }
    public function __construct()
    {
        $this->getConnection();
    }
    public function prepare($sql, $params = [])
    {
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt;
        // Usage:
        // $stmt = $database->prepare("SELECT * FROM users WHERE email = ?", [$email]);
        // $user = $stmt->fetch(PDO::FETCH_ASSOC);
    }
    public function the_insert_if()
    {
        return $this->conn->lastInsertId();
    }
}
$database = new Database();

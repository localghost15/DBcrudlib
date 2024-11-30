<?php

namespace App\Config;

use PDO;
use PDOException;

class MySQLDatabase implements DatabaseInterface
{
    private $host = 'localhost';
    private $dbname = 'your_database';
    private $username = 'root';
    private $password = '';
    private $pdo;

    public function connect()
    {
        try {
            $this->pdo = new PDO("mysql:host=$this->host;dbname=$this->dbname;charset=utf8", $this->username, $this->password);
            $this->pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            return $this->pdo;
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }
}

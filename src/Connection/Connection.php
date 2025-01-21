<?php

namespace App\Connection;

use PDO;
use PDOException;

class Connection
{
    private $host;
    private $dbname;
    private $user;
    private $password;

    public function __construct()
    {
        $this->host = $_ENV['DB_HOST'] ?: 'localhost';
        $this->dbname = $_ENV['DB_NAME'] ?: 'database';
        $this->user = $_ENV['DB_USER'] ?: 'root';
        $this->password = $_ENV['DB_PASSWORD'] ?: '';
    }

    public function getConnect(): PDO
    {
        try {
            $dsn = "pgsql:host={$this->host};dbname={$this->dbname}";
            $pdo = new PDO($dsn, $this->user, $this->password);

            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            return $pdo;
        } catch (PDOException $e) {
            throw new PDOException("Error al conectar a la base de datos: " . $e->getMessage());
        }
    }
}

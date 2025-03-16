<?php

namespace App\Connection;

use App\Tools\PrintDump;
use ConfigGeneral;
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
        $conf = new ConfigGeneral();

        $this->host = $conf->getDbHost() ?: $_ENV['DB_HOST'];
        $this->dbname = $conf->getDbName() ?: $_ENV['DB_NAME'];
        $this->user = $conf->getDbUser() ?: $_ENV['DB_USER'];
        $this->password = $conf->getDbPass()  ?: $_ENV['DB_PASSWORD'];
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

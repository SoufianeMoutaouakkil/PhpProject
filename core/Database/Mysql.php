<?php

namespace Core\Database;

use \PDO;

class Mysql extends Database
{

    private $dbName;
    private $dbUser;
    private $dbPass;
    private $dbHost;
    protected PDO $db;
    private static $sInstance;

    public static function getInstance()
    {
        if (self::$sInstance === null) {
            $class = __CLASS__;
            self::$sInstance = new $class;
        }
        return self::$sInstance;
    }

    private function __construct()
    {
        $config = $_ENV["DATABASE_CONFIG"]["MYSQL"];
        $this->dbName = $config["DB_NAME"];
        $this->dbUser = $config["DB_USER"];
        $this->dbPass = $config["DB_PASSWORD"];
        $this->dbHost = $config["DB_HOST"];
        $this->setPDO();
    }

    protected function setPDO()
    {
        if (!isset($this->db)) {
            $pdo = new PDO(
                'mysql:dbname=' . $this->dbName . ';host=' . $this->dbHost,
                $this->dbUser,
                $this->dbPass
            );
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->db = $pdo;
        }
    }
}

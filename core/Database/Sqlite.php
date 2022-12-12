<?php

namespace Core\Database;

use \PDO;

class Sqlite extends Database
{

    private $dsn;
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
        $config = $_ENV["DATABASE_CONFIG"]["SQLITE"];
        $this->dsn = $config["DSN"];
        $this->setPDO();
    }

    protected function setPDO()
    {
        if (!isset($this->db)) {
            $pdo = new PDO($this->dsn);
            $this->db = $pdo;
        }
    }
}

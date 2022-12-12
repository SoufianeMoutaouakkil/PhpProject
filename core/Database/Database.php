<?php

namespace Core\Database;

use InvalidArgumentException;
use \PDO;

abstract class Database
{

    protected PDO $db;

    public static function dbFactory(string $dbServer = "")
    {
        if ($dbServer === "") {
            $dbServer = $_ENV["DATABASE_SERVER"];
        }

        if (!in_array($dbServer, self::allowedDbServer())) {
            throw new InvalidArgumentException("The provided database server is not supported in this Application");
        }

        $dbServerClass = "Core\\Database\\" . ucfirst(strtolower($dbServer));
        
        $dbInstance = $dbServerClass::getInstance();

        $dbInstance->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        return $dbInstance;

    }

    public function query($statement, $className = null, $one = false)
    {
        $req = $this->db->query($statement);
        if (
            str_starts_with($statement, 'UPDATE') ||
            str_starts_with($statement, 'INSERT') ||
            str_starts_with($statement, 'DELETE') ||
            str_starts_with($statement, 'CREATE') ||
            str_starts_with($statement, 'ALTER') ||
            str_starts_with($statement, 'DROP')
        ) {
            return $req;
        }
        if ($className === null) {
            $req->setFetchMode(PDO::FETCH_OBJ);
        } else {
            $req->setFetchMode(PDO::FETCH_CLASS, $className);
        }
        if ($one) {
            $datas = $req->fetch();
        } else {
            $datas = $req->fetchAll();
        }
        return $datas;
    }

    public function prepare($statement, $attributes, $className = null, $one = false)
    {
        $req = $this->db->prepare($statement);
        $res = $req->execute($attributes);
        if (
            strpos($statement, 'UPDATE') === 0 ||
            strpos($statement, 'INSERT') === 0 ||
            strpos($statement, 'DELETE') === 0
        ) {
            return $res;
        }

        if ($className === null) {
            $req->setFetchMode(PDO::FETCH_OBJ);
        } else {
            $req->setFetchMode(PDO::FETCH_CLASS, $className);
        }

        if ($one) {
            $datas = $req->fetch();
        } else {
            $datas = $req->fetchAll();
        }
        
        return $datas;
    }

    public function lastInsertId()
    {
        return $this->db->lastInsertId();
    }

    private static function allowedDbServer()
    {
        return [
            "MYSQL",
            "SQLITE",
        ];
    }

    public function resetMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $migDir = $_ENV["MIGRATIONS_DIR"] ?? $_ENV["ROOT_DIR"] . DIRECTORY_SEPARATOR . 'migrations';
        $resetedMigrations = [];

        rsort($appliedMigrations);

        foreach ($appliedMigrations as $migration) {
            $migrationFile = $migDir . DIRECTORY_SEPARATOR . $migration;
            if (is_file($migrationFile)) {
                require_once $migrationFile;
                $className = pathinfo($migrationFile, PATHINFO_FILENAME);
                $instance = new $className();
                $instance->down();
                $resetedMigrations[] = $migration;
            }
        }

        if (!empty($resetedMigrations)) {
            $this->deleteMigrations($resetedMigrations);
        } else {
            echo "There are no migrations to reset";
        }
    }
    public function applyMigrations()
    {
        $this->createMigrationsTable();
        $appliedMigrations = $this->getAppliedMigrations();

        $migDir = $_ENV["MIGRATIONS_DIR"] ?? $_ENV["ROOT_DIR"] . DIRECTORY_SEPARATOR . 'migrations';

        $newMigrations = [];
        $files = scandir($migDir);
        $toApplyMigrations = array_diff($files, $appliedMigrations);
        sort($toApplyMigrations);
        
        foreach ($toApplyMigrations as $migration) {
            echo $migration . PHP_EOL;
            if ($migration === '.' || $migration === '..') {
                continue;
            }

            require_once $migDir . DIRECTORY_SEPARATOR . $migration;
            $className = pathinfo($migration, PATHINFO_FILENAME);
            $instance = new $className();
            $instance->up();
            $newMigrations[] = $migration;
        }

        if (!empty($newMigrations)) {
            $this->saveMigrations($newMigrations);
        } else {
            echo "There are no migrations to apply";
        }
    }

    protected function createMigrationsTable()
    {
        $this->db->exec("CREATE TABLE IF NOT EXISTS migrations (
            id INT AUTO_INCREMENT PRIMARY KEY,
            migration VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);");
    }

    protected function getAppliedMigrations()
    {
        $statement = $this->db->prepare("SELECT migration FROM migrations");
        $statement->execute();

        return $statement->fetchAll(PDO::FETCH_COLUMN);
    }

    protected function deleteMigrations(array $resetedMigrations)
    {
        $str = implode(',', array_map(fn($m) => "'$m'", $resetedMigrations));

        $statement = $this->db->prepare("DELETE FROM migrations WHERE migration IN ($str);");
        $statement->execute();
    }

    protected function saveMigrations(array $newMigrations)
    {
        $str = implode(',', array_map(fn($m) => "('$m')", $newMigrations));
        $statement = $this->db->prepare("INSERT INTO migrations (migration) VALUES
            $str
        ");
        $statement->execute();
    }
}

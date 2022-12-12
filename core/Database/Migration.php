<?php

namespace Core\Database;

abstract class Migration
{
    protected $sql;
    private $db;

    abstract public function up();
    abstract public function down();
    
    public function __construct()
    {
        $this->db = Database::dbFactory();
    }
    protected function exec()
    {
        $this->db->query($this->sql);
    }
}
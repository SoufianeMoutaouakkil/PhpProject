<?php

use Core\Database\Migration;

class M0001 extends Migration
{
    public function up()
    {
        $this->sql = "CREATE TABLE IF NOT EXISTS users (
                id INT AUTO_INCREMENT PRIMARY KEY,
                mail VARCHAR(255),
                login VARCHAR(255) NOT NULL,
                password VARCHAR(255) NOT NULL,
                firstname VARCHAR(255) NOT NULL,
                lastname VARCHAR(255) NOT NULL,
                status TINYINT DEFAULT 0,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP);";
        $this->exec();
    }

    public function down()
    {
        $this->sql = "DROP TABLE IF EXISTS users;";
        $this->exec();
    }
}

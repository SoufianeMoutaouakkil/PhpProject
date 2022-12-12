<?php

use Core\Database\Migration;

class M0002 extends Migration
{
    public function up()
    {
        $password = md5("admin");
        $this->sql = "INSERT INTO users (id, login, password, firstname, lastname)
            VALUES (1, 'ALPFG135', '$password', 'Soufiane', 'Moutaouakkil');";
        $this->exec();
    }

    public function down()
    {
        $this->sql = "DELETE FROM users WHERE login = 'ALPFG135';";
        $this->exec();
    }
}

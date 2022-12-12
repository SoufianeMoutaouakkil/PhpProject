<?php

use Altendev\App;
use Core\Database\Database;

require_once __DIR__ . '/../vendor/autoload.php';
$app = App::app();
$db = Database::dbFactory();

$db->applyMigrations();

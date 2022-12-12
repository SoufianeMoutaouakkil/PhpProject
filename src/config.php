<?php
use Altendev\Controller\ParcController;
use Altendev\Controller\TestController;
use Altendev\Controller\UserController;

return
[
    "DATABASE_SERVER" => "MYSQL",
    "DATABASE_CONFIG" => [
        "MYSQL" => [
            "DB_USER" => "root",
            "DB_PASSWORD" => "",
            "DB_HOST" => "localhost",
            "DB_NAME" => "altendev"
        ],
        "SQLITE" => [
            "DSN" => "sqlite:mydb.sqlite3"
        ]
    ],

    "ROUTES" => [
        "get" => [
            "/" => [TestController::class, "home"],
            "/parc" => [ParcController::class, "form"],
            "/login" => [UserController::class, "login"],
            "/logout" => [UserController::class, "logout"],
            "paramsPath/{paramName:\d}" => [TestController::class, "digitParam"],
            "paramsPath/{paramName}" => [TestController::class, "param"],
        ],
        "post" => [
            "/login" => [UserController::class, "login"],
            "parc/{pm}" => [ParcController::class, "apiGetPm"],
            "path1" => [TestController::class, "postMethod1"],
        ]
    ],

    "VIEW" => [
        "VIEW_DIR" => dirname(__FILE__) . "/views"
    ],
    "ROOT_DIR" => dirname(__FILE__, 2),

];

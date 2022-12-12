<?php

namespace Altendev;

use Core\Session;

use Altendev\Entity\UserEntity;
use Core\Http\Request;
use Core\Http\Response;
use Core\Config\Config;
use Core\Router\Router;
use Core\Router\Route;
use Altendev\Model\UserModel;
use Core\Database\Database;

use Core\Router\Exception\NotFoundException;
use Core\View\View;

class App
{

    public UserEntity|null $user;
    private Router $router;
    private Request $req;
    private Config $config;
    private Database $db;
    private static $sInstance = null;
    private Session $session;

    public static function app()
    {
        if (is_null(self::$sInstance)) {
            self::$sInstance = new App();
        }
        return self::$sInstance;
    }

    private function __construct()
    {
        $configPath = dirname(__FILE__) . "/config.php";

        $this->session = new Session;
        $this->session->start();

        $this->config = Config::getInstance(["main" => $configPath]);
        
        $this->router = Router::getInstance($_ENV["ROUTES"]);
        $this->req = new Request;
        $this->db = Database::dbFactory();
        $this->user = $this->getUser();
    }

    public function getUser()
    {
        $userId = $this->session->get("user-id");
        if ($userId !== null) {
            $userModel = new UserModel($this->db);
            $user = $userModel->find($userId);
            return $user === false ? null: $user;
        }
    }

    public function isGuest()
    {
        return $this->user === null;
    }

    public function run()
    {
        $method = $this->req->method;
        $path = $this->req->path;
        $isApi = $this->req->isApi;
        try {
            $route = $this->router->resolve($method, $path, $isApi);
            $this->req->setParams($route->params);
            $ctrlName = $route->controller;
    
            $ctrl = new $ctrlName();
            $ctrl->action = $route->action;
            $ctrl->execute($this->req);

        } catch (NotFoundException $e) {
            if ($this->req->isApi) {
                $res = new Response([], null, null, 404);
            } else {
                $view = new View("error.404");
                $res = new Response([], null, $view);
            }
            $res->send();
            die();
        }
    }
}

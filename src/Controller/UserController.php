<?php

namespace Altendev\Controller;

use Altendev\Entity\UserEntity;
use Altendev\Model\UserModel;
use Core\Controller\Controller;
use Core\Database\Database;
use Core\Http\Request;
use Core\Http\Response;
use Core\Session;
use Core\View\View;

class UserController extends Controller
{
    public function login(Request $req)
    {
        $entity = new UserEntity();
        
        if ($req->isApi) {
            $res = $this->apiLogin($req, $entity);
        } elseif ($req->isPost()) {
            $res = $this->postLogin($req, $entity);
        } else {
            $view = new View("user.login");
            $res = new Response(["entity" => $entity], null, $view);
        }
        
        $res->send();
    }

    public function postLogin(Request $req, UserEntity $entity)
    {
        $data = $req->getData();
        $entity->loadData($data);
        $model = new UserModel(Database::dbFactory());
        $user = $model->findByField("login", $entity->login);
        if (!$user) {
            $entity->addError('login', "Ce login n'exist pas!");
        } else {
            if (md5($entity->password) !== $user->password) {
                $entity->addError('password', "Le mot de passe est incorrect!");
            } else {
                $session = new Session();
                $session->set("user-id", $user->id);
                header("location: /");
                exit;
            }
        }
        $view = new View("user.login");
        return new Response(["entity" => $entity], null, $view);
    }

    public function apiLogin(Request $req, UserEntity $entity)
    {
        $logged = false;
        $data = $req->getData();
        $entity->loadData($data);
        $model = new UserModel(Database::dbFactory());
        $user = $model->findByField("login", $entity->login);
        if (!$user) {
            $entity->addError('login', "Ce login n'exist pas!");
        } else {
            if (md5($entity->password) !== $user->password) {
                $entity->addError('password', "Le mot de passe est incorrect!");
            } else {
                $logged = true;
                $session = new Session();
                $session->set("user-id", $user->id);
            }
        }

        return new Response([
            "entity" => $entity->toArray(),
            "logged" => $logged
        ]);
    }

    public function logout(Request $req)
    {
        (new Session())->remove("user-id");
        header("location: /login");
        exit;
    }

}

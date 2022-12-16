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
    private UserModel $model;
    public function __construct()
    {
        $this->model = new UserModel();
    }
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
        $user = $this->model->findByField("login", $entity->login);
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
        $user = $this->model->findByField("login", $entity->login);
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
        if ($req->isApi) {
            (new Response(["done" => true]))->send();
        } else {
            header("location: /login");
        }
        exit;
    }

    public function profile(Request $req)
    {
        $entity = $this->getUser();

        $view = new View("user.profile");
        $res = new Response(["entity" => $entity], null, $view);
        $res->send();
    }

    public function profileInfo(Request $req)
    {
        $entity = $this->getUser();
        $res = new Response(["entity" => $entity->toArray()]);
        $res->send();
    }

    public function postProfile(Request $req)
    {
        $entity = new UserEntity();
        $entity->loadData($req->data);
        $userId = $this->getUser()->id;
        $entity->id = $userId;
        $fields = $entity->updateFields();

        if ($entity->validate("update")) {
            $this->model->update($userId, $fields);
            $isUpdated = true;
        } else {
            $isUpdated = false;
        }
        $res = new Response(["updated" => $isUpdated, "entity" => $entity]);
        $res->send();
    }
    

    public function apiProfile(Request $req, UserEntity $entity)
    {
        # some instructions
    }

    private function getUser($id = null) : UserEntity
    {
        $userId = $id === null ? (new Session())->get("user-id") : $id;
        
        return $this->model->find($userId);
    }
}

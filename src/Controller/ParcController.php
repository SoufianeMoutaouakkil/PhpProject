<?php

namespace Altendev\Controller;

use Core\Session;
use Core\View\View;
use Core\Http\Request;
use Core\Http\Response;
use Altendev\Model\ParcModel;
use Altendev\Entity\ParcEntity;
use Core\Controller\Controller;
use Core\Database\Database;

class ParcController extends Controller
{
    public function form(Request $req)
    {
        $entity = new ParcEntity();
        $res = new Response(["entity"=>$entity], null, new View("parc.form"));
        $res->send();
    }

    public function apiGetPm(Request $req)
    {
        $pm = $req->getData()["pm"] ?? "";
        $model = new ParcModel(Database::dbFactory());
        $entity = $model->findByField("pm", $pm);
        if ($req->isApi) {
            (new Response(
                [
                    "entity" => $entity
                ]
            ))->send();
        }
    }
}

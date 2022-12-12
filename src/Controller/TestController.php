<?php

namespace Altendev\Controller;

use Altendev\Model\ParcModel;
use Core\Controller\Controller;
use Core\File\Excel;
use Core\Http\Response;
use Core\View\View;

class TestController extends Controller
{

    public function postMethod1()
    {
        #To use in tests
    }

    public function getMethod1()
    {
        #To use in tests
    }

    public function method2()
    {
        #To use in tests
    }

    public function param()
    {
        #To use in tests
    }

    public function digitParam()
    {
        #To use in tests
    }

    public function home()
    {
        $view = new View("home", "index");

        (new Response([], null, $view))->send();
    }

}

<?php

namespace Core\Controller;

use Core\Http\Request;

class Controller
{
    public $action;

    public function execute(Request $req)
    {
        return $this->{$this->action}($req);
    }
}

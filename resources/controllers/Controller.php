<?php

namespace ZypeMedia\Controllers;

use Themosis\Route\BaseController;
use ZypeMedia\Validators\Request;
use Themosis\Facades\Config;

class Controller extends BaseController
{

    public $options;
    public $request;

    public function __construct()
    {
        $this->options = Config::get('zype');
        $this->request = Request::capture();
    }

}

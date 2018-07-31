<?php

namespace ZypeMedia\Controllers;

use Themosis\Route\BaseController;
use ZypeMedia\Validators\Request;

class Controller extends BaseController
{

    public $options;
    public $request;

    public function __construct()
    {
        global $zype_wp_options;
        $this->options = $zype_wp_options;
        $this->request = Request::capture();
        // $this->request = $GLOBALS['themosis']->container->request;
    }

}

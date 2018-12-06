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

    protected function update_options()
    {
        update_option('zype_wp', $this->options);
        $this->options = get_option('zype_wp');
    }
}

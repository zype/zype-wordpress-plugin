<?php

namespace ZypeMedia\Services;

use Themosis\Facades\Config;
use ZypeMedia\Validators\Request;

class Component
{

    private static $objects = array();
    public static $options;
    public static $request;

    public function __construct()
    {
        self::$options = Config::get('zype');
        self::$request = Request::capture();

        if (!isset(self::$objects[get_class($this)])) {
            self::$objects[get_class($this)] = $this;
        }

        return self::$objects[get_class($this)];
    }

}

<?php

namespace ZypeMedia\Services;

use Themosis\Facades\Config;

class Component
{

    private static $objects = array();
    public $options;
    public $request;

    public function __construct()
    {
        $this->options = Config::get('zype');
        $this->request = $GLOBALS['themosis']->container->request;

        if (!isset(self::$objects[get_class($this)])) {
            self::$objects[get_class($this)] = $this;
        }

        return self::$objects[get_class($this)];
    }

}

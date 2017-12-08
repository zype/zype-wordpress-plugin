<?php

namespace ZypeMedia\Services;

class Component {
    public $options;

    private static $objects = array();

    public function __construct()
    {
        global $zype_wp_options;
        $this->options = $zype_wp_options;

        if (!isset(self::$objects[get_class($this)])) {
            self::$objects[get_class($this)] = $this;
        }

        return self::$objects[get_class($this)];
    }
}
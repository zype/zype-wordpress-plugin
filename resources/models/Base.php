<?php

namespace ZypeMedia\Models;

use Themosis\Facades\Config;
use ZypeMedia\Validators\Request;

class Base {

    public function __construct() {
        $this->options = get_option(ZYPE_WP_OPTIONS);
        $this->request = Request::capture();
    }

    protected function set_attributes($object)
    {
        foreach ($object as $key => $value) {
            $this->{$key} = $value;
        }
    }

    private function title_to_permalink($str, $replace = array(), $delimiter = '-')
    {
        if (!empty($replace)) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

    protected static function load_model($object)
    {
        $class_name = static::class;
        if(is_array($object)){
            return array_map(function ($element) use ($class_name) {
                return new $class_name($element);
            }, $object);
        }
        else {
            return new $class_name($object);
        }
    }
}

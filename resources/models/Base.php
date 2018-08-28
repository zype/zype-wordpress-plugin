<?php

namespace ZypeMedia\Models;

use Themosis\Facades\Config;
use ZypeMedia\Validators\Request;

class Base {

    public function __construct() {
        $this->options = Config::get('zype');
        $this->request = Request::capture();
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
}

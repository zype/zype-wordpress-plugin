<?php

namespace ZypeMedia\Models\V2;

use Themosis\Facades\Config;
use ZypeMedia\Validators\Request;

class Base {

    public function __construct($object) {
        $this->options = get_option(ZYPE_WP_OPTIONS);
        $this->request = Request::capture();
        $this->set_attributes($object);
    }

    protected function set_attributes($object)
    {
        foreach ($object as $key => $value) {
            $this->{$key} = $value;
        }
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

    protected static function get_all($params, $with_pagination, $wrapper_method)
    {
        $collection = false;
        $pagination = false;
        $res = self::call_wrapper_method($params, $wrapper_method);
        if ($res) {
            $collection = self::load_model($res->response);
            $pagination = $res->pagination;
            if (!$with_pagination && $pagination && $pagination->pages > 1) {
                for ($page = $pagination->current + 1; $page <= $pagination->pages; $page++) {
                    $params['page'] = $page;
                    $res = self::call_wrapper_method($params, $wrapper_method);
                    if ($res) {
                        $response = self::load_model($res->response);
                        $collection = array_merge($collection, $response);
                    }
                }
            }
        }

        return [
            'collection' => $collection,
            'pagination' => $pagination
        ];
    }

    private static function call_wrapper_method($params, $wrapper_method)
    {
        if(array_is_sequential($params)) {
            $res = call_user_func(array('\Zype', $wrapper_method), ...$params);
        }
        else {
            $res = call_user_func(array('\Zype', $wrapper_method), $params);
        }
        return $res;
    }
}

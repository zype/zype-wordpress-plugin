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

    public function type()
    {
        return get_class($this);
    }

    protected static function load_model($object, $class_to_instanciate = '')
    {
        $class_to_instanciate = $class_to_instanciate ?: static::class;
        if(is_array($object)) {
            return array_map(function ($element) use ($class_to_instanciate) {
                return new $class_to_instanciate($element);
            }, $object);
        }
        else {
            return new $class_to_instanciate($object);
        }
    }

    /**
     * @param array|string $params, it could be: string, associative array or sequential array
     * if is a sequential array, it would be composed by a string (first element) and an associative array (second element)
     *
     * @return all objects for given class
     */
    protected static function get_all($params, $with_pagination, $wrapper_method, $wrapper_class = '\Zype', $class_to_instanciate = '')
    {
        $collection = false;
        $pagination = false;
        $params = self::set_pagination_params($with_pagination, $params);
        $res = self::call_wrapper_method($params, $wrapper_method, $wrapper_class);
        if ($res && $res->success()) {
            $collection = self::load_model($res->response, $class_to_instanciate);
            $pagination = $res->pagination;
            if (!$with_pagination && $pagination && $pagination->pages > 1) {
                for ($page = $pagination->current + 1; $page <= $pagination->pages; $page++) {
                    $params['page'] = $page;
                    $res = self::call_wrapper_method($params, $wrapper_method, $wrapper_class);
                    if ($res) {
                        $response = self::load_model($res->response, $class_to_instanciate);
                        $collection = array_merge($collection, $response);
                    }
                }
            }
        }

        return $with_pagination ? [
            'collection' => $collection,
            'pagination' => $pagination
        ] : $collection;
    }

    private static function set_pagination_params($with_pagination, $params)
    {
        if($with_pagination) return $params;
        if(is_array($params)) {
            if(array_is_sequential($params)) {
                $params[1]['per_page'] = 500;
            }
            else {
                $params['per_page'] = 500;
            }
        }
        else {
            $params = [$params, ['per_page' => 500]];
        }
        return $params;
    }

    private static function call_wrapper_method($params, $wrapper_method, $wrapper_class)
    {
        if(is_array($params) && array_is_sequential($params)) {
            $res = call_user_func(array($wrapper_class, $wrapper_method), ...$params);
        }
        else {
            $res = call_user_func(array($wrapper_class, $wrapper_method), $params);
        }
        return $res;
    }
}

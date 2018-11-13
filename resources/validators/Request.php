<?php

namespace ZypeMedia\Validators;

use Themosis\Facades\Validator;
use Themosis\Foundation\Request as ThemosisRequest;

class Request extends ThemosisRequest
{

    public function validate($key, $rules = [], $default = '')
    {
        return $this->sanitize($this->get($key), $rules, $default);
    }

    public function validateServer($key, $rules = [], $default = '')
    {
        return $this->sanitize($this->server->get($key), $rules, $default);
    }

    public function validateCookie($key, $rules = [], $default = '')
    {
        return $this->sanitize($this->cookies->get($key), $rules, $default);
    }

    public function validateAll($rules = [], $default = '')
    {
        return $this->validateHash($this->all(), $rules, $default);
    }

    public function validateHash($hash, $rules = [], $default = '')
    {
        $data = [];

        foreach ($hash as $key => $val) {
            if(is_array($val) && $this->isAssoc($val)) {
                $data[$key] = $this->validateHash($hash[$key], $rules, $default);
            }
            else {
                $data[$key] = $this->sanitize($val, $rules, $default);
            }
        }
        return $data;
    }

    public function isAssoc($arr)
    {
        if (array() === $arr) return false;
        return array_keys($arr) !== range(0, count($arr) - 1);
    }

    public function sanitize($data, $rules = [], $default = '')
    {
        $value = Validator::single($data, $rules);

        if (!$value && $default) {
            return $default;
        }

        return $value;
    }

}

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
        $data = [];

        foreach ($this->all() as $key => $val) {
            $data[$key] = $this->sanitize($val, $rules, $default);
        }

        return $data;
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
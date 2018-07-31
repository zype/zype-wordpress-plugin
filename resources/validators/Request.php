<?php

namespace ZypeMedia\Validators;

use Themosis\Facades\Validator;
use Themosis\Foundation\Request as ThemosisRequest;

class Request extends ThemosisRequest
{

    public function validate($key, $rules = [], $default = '')
    {
        $value = Validator::single($this->get($key), $rules);

        if (!$value && $default) {
            return $default;
        }

        return $value;
    }

    public function validateAll($rules = [], $default = '')
    {
        $data = [];

        foreach ($this->all() as $key => $val) {
            $data[$key] = Validator::single($val, $rules);

            if (!$data[$key] && $default) {
                $data[$key] = $default;
            }
        }

        return $data;
    }

}
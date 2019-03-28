<?php

namespace ZypeMedia\Models\V2;

use Zype\Api\Account as AccountApi;

class Account extends Base
{
    public static function find()
    {
        $single = \Zype\Api\Account::retrieve();
        return $single ? self::load_model($single->response) : false;
    }
}

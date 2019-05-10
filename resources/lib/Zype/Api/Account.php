<?php

namespace Zype\Api;

use Themosis\Facades\Config;

class Account extends \Api
{
    const RESOURCE_PATH = 'account';

    /**
     * @return The site object
     */
    public static function retrieve($format = 'json')
    {
        $options = Config::get('zype');
        $path = self::get_path() . '.' . $format;
        return self::request("GET", $path, ['api_key' => $options['admin_key']], false, false);
    }
}

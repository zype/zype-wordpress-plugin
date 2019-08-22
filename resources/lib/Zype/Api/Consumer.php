<?php

namespace Zype\Api;

use Themosis\Facades\Config;

class Consumer extends \Api
{
    use \ApiOperations\All;
    use \ApiOperations\Create;
    use \ApiOperations\Retrieve;
    use \ApiOperations\Update;

    const RESOURCE_PATH = 'consumers';

    public static function braintree($consumer_id)
    {
        $options = Config::get('zype');
        $path = join([self::get_path(), $consumer_id, 'braintree'], '/');
        return parent::request("GET", $path, ['api_key' => $options['admin_key']]);
    }
}

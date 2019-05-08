<?php

namespace Zype\Api;

class Video extends \Api
{
    use \ApiOperations\All;
    use \ApiOperations\Create;
    use \ApiOperations\Retrieve;
    use \ApiOperations\Update;

    const RESOURCE_PATH = 'videos';

    public static function entitled($id, $access_token)
    {
        $path = self::get_path($id) . '/entitled';
        $cache = (current_time('timestamp') - self::$options['last_transaction_created_at']) > self::$options['cache_time'];
        return parent::request("GET", $path, ['access_token' => $access_token], false, $cache);
    }
}

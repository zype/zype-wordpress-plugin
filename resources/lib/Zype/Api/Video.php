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
        return parent::request("GET", $path, ['access_token' => $access_token], false, true);
    }
}

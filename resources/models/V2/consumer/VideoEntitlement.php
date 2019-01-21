<?php

namespace ZypeMedia\Models\V2\Consumer;

use Zype\Api\Video;

class VideoEntitlement extends \ZypeMedia\Models\V2\Base
{
    public function __construct($object)
    {
        parent::__construct($object);
    }

    public static function get($id, $access_token)
    {
        return \Zype\Api\Video::entitled($id, $access_token);
    }

    public static function all($access_token, $params, $with_pagination = true)
    {
        return parent::get_all(array($access_token, $params), $with_pagination, 'get_consumer_entitled_videos');
    }
}

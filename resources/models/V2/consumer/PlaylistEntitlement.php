<?php

namespace ZypeMedia\Models\V2\Consumer;

class PlaylistEntitlement extends \ZypeMedia\Models\V2\Base
{
    public function __construct($object)
    {
        parent::__construct($object);
    }

    public static function all($access_token, $params, $with_pagination = true)
    {
        return parent::get_all(array($access_token, $params), $with_pagination, 'get_consumer_entitled_playlists');
    }
}

<?php

namespace Zype\Api;

class Playlist extends \Api
{
    use \ApiOperations\All;
    use \ApiOperations\Create;
    use \ApiOperations\Retrieve;
    use \ApiOperations\Update;

    const RESOURCE_PATH = 'playlists';

    public static function videos($id, $query = [])
    {
        $path = self::get_path($id) . '/videos';
        return parent::request("GET", $path, $query, false, true);
    }

    public static function relationships()
    {
        $path = self::get_path() . '/relationships';
        return parent::request("GET", $path);
    }
}

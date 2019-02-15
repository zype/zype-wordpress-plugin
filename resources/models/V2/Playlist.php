<?php

namespace ZypeMedia\Models\V2;

use Zype\Api\Playlist as PlaylistApi;
use Zype\Api\Video;

class Playlist extends Base
{
    public function __construct($object)
    {
        parent::__construct($object);
    }

    public function transaction_required()
    {
        return $this->rental_required || $this->purchase_required;
    }

    public static function find($id)
    {
        $single = \Zype\Api\Playlist::retrieve($id);
        return $single ? self::load_model($single->response) : false;
    }

    public static function all($params, $with_pagination = true)
    {
        return parent::get_all($params, $with_pagination, 'all', 'Zype\Api\Playlist');
    }

    public static function has_video($playlist_id, $video_id)
    {
        $videos = parent::get_all(
            ['q' => $video_id, 'playlist_id.inclusive' => $playlist_id],
            false, 'all', 'Zype\Api\Video', '\ZypeMedia\Models\V2\Video'
        );
        $video_ids = array_map(function($video) {
            return $video->_id;
        }, $videos);
        return in_array($video_id, $video_ids);
    }

    public static function videos($params, $with_pagination = true)
    {
        return parent::get_all($params, $with_pagination, 'videos', 'Zype\Api\Playlist', '\ZypeMedia\Models\V2\Video');
    }
}

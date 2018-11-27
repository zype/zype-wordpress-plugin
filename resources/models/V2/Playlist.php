<?php

namespace ZypeMedia\Models\V2;

class Playlist extends Base
{
    public function __construct($object)
    {
        parent::__construct($object);
    }

    public static function find($id)
    {
        $single = \Zype::get_playlist($id);
        return $single ? self::load_model($single) : false;
    }

    public static function all($params, $with_pagination = true)
    {
        return parent::get_all($params, $with_pagination, 'get_playlists');
    }

    public function transaction_required()
    {
        return $this->rental_required || $this->purchase_required;
    }
}

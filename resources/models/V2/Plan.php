<?php

namespace ZypeMedia\Models\V2;

class Plan extends Base
{
    public static function find($id)
    {
        $single = \Zype::get_plan($id);
        return $single ? self::load_model($single) : false;
    }

    public static function all($params = [], $with_pagination = true)
    {
        return parent::get_all($params, $with_pagination, 'get_all_plans');
    }

    public function global_entitlement_type()
    {
        return $this->entitlement_type === 'global';
    }

    public function tiered_playlist($playlist_id)
    {
        return $this->entitlement_type === 'tiered' &&
            in_array($playlist_id, $this->playlist_ids);
    }
}

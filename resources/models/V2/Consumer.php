<?php

namespace ZypeMedia\Models\V2;

class Consumer extends Base
{
    public static function find($id)
    {
        $single = \Zype::get_consumer($id);
        return $single ? self::load_model($single) : false;
    }

    public function global_subscriptions_count()
    {
        return count($this->global_subscriptions());
    }

    public function global_subscriptions()
    {
        return array_filter($this->subscription_plans, function($sub) {
            return $sub->entitlement_type === 'global';
        });
    }

    public function tiered_subscriptions()
    {
        return array_filter($this->subscription_plans, function($sub) {
            return $sub->entitlement_type === 'tiered';
        });
    }
}

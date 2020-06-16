<?php

namespace ZypeMedia\Models\V2;

class Consumer extends Base
{
    public static function find($id)
    {
        $single = \Zype\Api\Consumer::retrieve($id);
        return $single ? self::load_model($single->response) : false;
    }

    public static function find_not_cached($id)
    {
        $single = \Zype\Api\Consumer::retrieve($id, ['cache' => false]);
        return $single ? self::load_model($single->response) : false;
    }

    public static function get_braintree_customer($id)
    {
        $single = \Zype\Api\Consumer::braintree($id);
        return $single;
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

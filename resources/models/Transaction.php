<?php

namespace ZypeMedia\Models;

class Transaction extends Base
{
    const PURCHASE = 'purchase';
    const PASS_PLAN = 'pass';
    const RENTAL = 'rental';

    public function __construct($use_admin = false)
    {
        parent::__construct();
        $this->options = get_option(ZYPE_WP_OPTIONS);
    }

    public function find($id)
    {
        $this->single = \Zype::get_transactions(['id' => $id]);
    }

    public function create_transaction($type, $object_id, $provider, $provider_nonce, $plan_id = null, $object_type = null)
    {
        $transaction = array(
            'consumer_id' => (new \ZypeMedia\Services\Auth())->get_consumer_id(),
            'transaction_type' => $type,
            'payment_nonce' => $provider_nonce
        );

        if($object_type === 'playlist') {
            $transaction['playlist_id'] = $object_id;
        }
        else {
            $transaction['video_id'] = $object_id;
        }

        if($type == self::PASS_PLAN) {
            if ($plan_id) {
                $transaction['pass_plan_id'] = $plan_id;
                $pass_plan = \ZypeMedia\Models\V2\PassPlan::find($plan_id);
                $transaction['amount'] = $pass_plan->amount;
            }
        }
        else {
            $object = $this->{"get_{$object_type}"}($object_id);
            $transaction['amount'] = $object->{"{$type}_price"};
        }

        $new_transaction = \Zype::create_transaction($transaction, $provider);

        $this->options['last_transaction_created_at'] = current_time('timestamp');
        update_option('zype_wp', $this->options);

        return $new_transaction;
    }

    public function valid_transaction($consumerId, $video = null)
    {
        $params = [
            'consumer_id' => $consumerId,
            'per_page' => 100
        ];

        # If no video is provided then we should check for pass_plans
        if($video) {
            $params['video_id'] = $video->_id;
        }
        else {
            $params['transaction_type'] = self::PASS_PLAN;
        }

        $this->all($params);
        $pass_required = $video ? $video->pass_required : true;
        $rental_required = $video ? $video->rental_required : false;
        $purchase_required = $video ? $video->purchase_required : false;

        foreach ($this->collection as $transaction) {

            $enabled_by_pass_plan = $pass_required && $transaction->transaction_type == self::PASS_PLAN && strtotime($transaction->expires_at) > time();
            $enabled_by_rental = $rental_required && $transaction->transaction_type == self::RENTAL && strtotime($transaction->expires_at) > time();
            $enabled_by_purchase = $purchase_required && $transaction->transaction_type == self::PURCHASE;
            $enabled = $enabled_by_pass_plan || $enabled_by_purchase || $enabled_by_rental;

            if ($enabled) {
                return true;
            }
        }

        return false;
    }

    public function all($params = [])
    {
        $perPage = isset($params['per_page']) ? $params['per_page'] : null;
        $page = isset($params['page']) ? $params['page'] : null;

        $res = \Zype::get_transactions($params, $page, $perPage);

        if ($res) {
            $this->collection = $res->response;
            $this->pagination = $res->pagination;
        } else {
            $this->collection = false;
            $this->pagination = false;
        }
    }

    private function get_video($video_id)
    {
        $vm = (new \ZypeMedia\Models\Video);
        $vm->find($video_id);
        return $vm->single?: false;
    }

    private function get_playlist($playlist_id)
    {
        $playlist = \ZypeMedia\Models\V2\Playlist::find($playlist_id);
        return $playlist?: false;
    }

}

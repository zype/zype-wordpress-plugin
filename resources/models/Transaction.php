<?php

namespace ZypeMedia\Models;

class Transaction extends Base
{

    const TYPE_PASS_PLAN = 'pass';
    const TYPE_RENTAL = 'rental';

    public function __construct($use_admin = false)
    {
        parent::__construct();
        $this->options = get_option(ZYPE_WP_OPTIONS);
    }

    public function find($id)
    {
        $this->single = \Zype::get_transactions(['id' => $id]);
    }

    public function createTransaction($type, $videoId, $provider, $providerNonce, $planId = null)
    {
        $transaction = array(
            'consumer_id' => (new \ZypeMedia\Services\Auth())->get_consumer_id(),
            'video_id' => $videoId,
            'transaction_type' => $type,
            'payment_nonce' => $providerNonce,
        );

        if ($planId) {
            $transaction['pass_plan_id'] = $planId;
        }
        $newTransaction = \Zype::create_transaction($transaction, $provider);

        return $newTransaction;
    }

    public function hasConsumerVideoValidTransaction($consumerId, $type = null, $videoId = null)
    {
        $this->all([
            'consumer_id' => $consumerId,
            'per_page' => 100,
        ]);

        foreach ($this->collection as $transaction) {

            // In case of rental transactions we should check for which video it was paid.
            if ($transaction->transaction_type == self::TYPE_RENTAL && $transaction->video_id != $videoId) {
                continue;
            }

            $validType = $type ? $type == $transaction->transaction_type : true;

            if ($validType && strtotime($transaction->expires_at) > time()) {
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
}

<?php

namespace ZypeMedia\Services;

class Access extends Component
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Method checks 3 levels of access: subscription, pass plan, rental.
     * The user should have at least 1 payment access.
     *
     * @param string      $videoId Video id.
     * @param null|string $userId  Consumer id.
     *
     * @return boolean
     */
    public function checkUserVideoAccess($videoId, $userId = null)
    {
        if (!$userId) {
            $userId = (new \ZypeMedia\Services\Auth)->get_consumer_id();
        }
        $transaction = (new \ZypeMedia\Models\Transaction);

        $vm = new \ZypeMedia\Models\Video;
        $vm->find($videoId);
        if (!$video = $vm->single) {
            return false;
        }

        $hasAccess = true;
        if ($video->subscription_required) {
            if ((new \ZypeMedia\Services\Auth)->subscriber()) {
                return true;
            }
            $hasAccess = false;
        }

        if ($video->pass_required) {
            if ($transaction->hasConsumerVideoValidTransaction($userId, \ZypeMedia\Models\Transaction::TYPE_PASS_PLAN)) {
                return true;
            }
            $hasAccess = false;
        }

        if ($video->rental_required) {
            if ($transaction->hasConsumerVideoValidTransaction($userId, \ZypeMedia\Models\Transaction::TYPE_RENTAL, $videoId)) {
                return true;
            }
            $hasAccess = false;
        }

        return $hasAccess;
    }
}

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
     * @param string $video_id Video id.
     * @param null|string $user_id Consumer id.
     *
     * @return boolean
     */
    public function checkUserVideoAccess($video_id, $user_id = null)
    {
        if (!$user_id) {
            $user_id = (new \ZypeMedia\Services\Auth)->get_consumer_id();
        }
        $transaction = (new \ZypeMedia\Models\Transaction);

        $vm = new \ZypeMedia\Models\Video;
        $vm->find($video_id);
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

        if($video->pass_required) {
            if ($transaction->valid_transaction($user_id)) {
                return true;
            }
            $hasAccess = false;
        }

        if ($video->rental_required || $video->purchase_required) {
            if ($transaction->valid_transaction($user_id, $video)) {
                return true;
            }
            $hasAccess = false;
        }

        return $hasAccess;
    }
}

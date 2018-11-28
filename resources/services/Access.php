<?php

namespace ZypeMedia\Services;

use ZypeMedia\Models\V2\Consumer\VideoEntitlement;
use ZypeMedia\Models\V2\Consumer\PlaylistEntitlement;

class Access extends Component
{

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Method checks 3 levels of access: subscription, pass plan, rental and purchase.
     * The user should have at least 1 payment access.
     *
     * @param string $video_id Video id.
     * @param null|string $playlist_id Playlist where the user is seeing the video.
     *
     * @return boolean
     */
    public function checkUserVideoAccess($video_id, $playlist_id = null)
    {
        $user_id = (new \ZypeMedia\Services\Auth)->get_consumer_id();
        $transaction = (new \ZypeMedia\Models\Transaction);

        $vm = new \ZypeMedia\Models\Video;
        $vm->find($video_id);
        if (!$video = $vm->single) {
            return false;
        }

        $hasAccess = true;
        if ($video->subscription_required) {
            if (\ZypeMedia\Services\Auth::subscriber($playlist_id)) {
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

        /*
         *  If $has_access is false I have to check if it still has a video entitlement.
         *  No matter if rental_required or purchase_required are true, bc it could
         *  belong to a playlist where rental_required or purchase_required are true
        */
        if (!$hasAccess || $video->rental_required || $video->purchase_required) {
            if ($this->check_entitlements($video_id, $playlist_id)) {
                return true;
            }
            else {
                $hasAccess = false;
            }
        }
        return $hasAccess;
    }

    private function check_entitlements($video_id, $playlist_id)
    {
        $za = new \ZypeMedia\Services\Auth;
        $access_token = $za->get_access_token();
        if($access_token) {
            $video_entitlements = VideoEntitlement::all($access_token, ['video_id' => $video_id], false);
            $entitled_videos_count = count($video_entitlements) > 0;
            $playlist_entitlements = [];
            # If it doesn't have entitlements for the video, we should check if the consumer has a playlist entitlement
            if(!$entitled_videos_count && $playlist_id) {
                $playlist_entitlements = PlaylistEntitlement::all($access_token, ['playlist_id' => $playlist_id], false);
            }
            return $entitled_videos_count || count($playlist_entitlements) > 0;
        }
        else {
            return false;
        }
    }
}

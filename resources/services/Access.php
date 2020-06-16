<?php

namespace ZypeMedia\Services;

use ZypeMedia\Models\V2\Consumer\VideoEntitlement;
use ZypeMedia\Models\V2\Consumer\PlaylistEntitlement;
use ZypeMedia\Models\V2\Account;
use ZypeMedia\Models\V2\Playlist;

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
        $site = Account::find();

        $vm = new \ZypeMedia\Models\Video;
        $vm->find($video_id);
        if (!$video = $vm->single) {
            return false;
        }

        $hasAccess = true;
        if ($video->subscription_required) {
            $is_subscribed = \ZypeMedia\Services\Auth::subscriber($playlist_id);
            if ($is_subscribed) {
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
        if (!$hasAccess || $this->should_check_entitlements($video, $site->playlist_bundle_enabled)) {
            if ($this->is_allowed_to_watch($video_id, $playlist_id)) {
                return true;
            }
            else {
                $hasAccess = false;
            }
        }
        return $hasAccess;
    }

    // If the site has enabled playlist_bundle_enabled,
    // we should check if the playlist has monetization options ON
    private function should_check_entitlements($video, $playlist_bundle_enabled) {
        return $video->rental_required || $video->purchase_required ||
            ($playlist_bundle_enabled && $this->playlist_monetization_required($video->_id));
    }

    private function playlist_monetization_required($video_id)
    {
        $playlists = Playlist::all(['video_id' => $video_id], false);
        $rental_playlists = array_filter($playlists, function ($playlist) {
            return $playlist->rental_required;
        });
        $purchase_playlists  = array_filter($playlists, function ($playlist) {
            return $playlist->purchase_required;
        });
        return count($rental_playlists) + count($purchase_playlists);
    }

    private function is_allowed_to_watch($video_id, $playlist_id)
    {
        $za = new \ZypeMedia\Services\Auth;
        $access_token = $za->get_access_token();
        if($access_token) {
            $video_entitlements = VideoEntitlement::get($video_id, $access_token);
            $is_entitled = $video_entitlements->code === 200;
            $playlist_entitlements = [];
            # If it doesn't have entitlements for the video, we should check if the consumer has a playlist entitlement
            if(!$is_entitled && $playlist_id) {
                $playlist_entitlements = PlaylistEntitlement::all($access_token, ['playlist_id' => $playlist_id], false);
            }
            return $is_entitled || count($playlist_entitlements) > 0;
        }
        else {
            return false;
        }
    }
}

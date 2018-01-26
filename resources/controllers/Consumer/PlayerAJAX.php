<?php

namespace ZypeMedia\Controllers\Consumer;

use ZypeMedia\Services\Access;

class PlayerAJAX extends BaseAJAX
{
    public function __construct()
    {
        $this->auther = new \ZypeMedia\Services\Auth;
        parent::__construct();
    }

    public function init()
    {
        add_action('wp_ajax_nopriv_zype_player', [
            $this,
            'player',
        ]);
        add_action('wp_ajax_zype_player', [
            $this,
            'player',
        ]);
    }

    public function player()
    {
        $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);

        $videoId = isset($post['video_id']) ? $post['video_id'] : 'null';
        $autoplay = 'autoplay=false';
        $key = 'api_key=' . $this->options['player_key'];
        $audio_only = '';

        if (isset($post['auto_play']) && $post['auto_play'] == 'false') {
            $autoplay = 'autoplay=false';
        }

        if (isset($post['audio_only']) && $post['audio_only'] == 'true') {
            $audio_only = '&audio=true';
        }

        if (isset($post['auth_required']) && $post['auth_required'] == 'true') {

            $hasUserAccessToVideo = (new Access())->checkUserVideoAccess($videoId);

            if ($this->auther->logged_in() && $hasUserAccessToVideo) {
                $key = 'access_token=' . $this->auther->get_access_token();
            } else {
                $this->authorization_required();
            }
        }

        $res = [
            'audio_only' => $post['audio_only'],
            'embed_url'  => $this->options['playerHost'] . '/embed/' . $videoId . '.js?' . $key . '&' . $autoplay . $audio_only,
        ];
        echo json_encode($res);
        wp_die();
    }

    public function authorization_required()
    {
        http_response_code(400);
        $res = ['authorization_required' => true];
        echo json_encode($res);
        wp_die();
    }
}

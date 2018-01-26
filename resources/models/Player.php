<?php

namespace ZypeMedia\Models;

class Player {

    public function __construct($video)
    {
        $this->video = $video;
    }

    public function embed($params)
    {
        $this->do_embed([
            'video' => $this->video,
            'auth_required' => isset($params['auth']) ? $params['auth'] : false,
            'auto_play' => false,
            'audio_only' => isset($params['audio_only']) ? $params['audio_only'] : false,
        ]);
    }

    public function auth_embed()
    {
        $this->do_embed([
            'video' => $this->video,
            'auth_required' => true,
            'auto_play' => false,
            'audio_only' => false,
        ]);
    }

    public function auth_embed_auto_play()
    {
        $this->do_embed([
            'video' => $this->video,
            'auth_required' => true,
            'auto_play' => false,
            'audio_only' => false,
        ]);
    }

    public function free_embed()
    {
        $this->do_embed([
            'video' => $this->video,
            'auth_required' => false,
            'auto_play' => false,
            'audio_only' => false,
        ]);
    }

    public function free_embed_auto_play()
    {
        $this->do_embed([
            'video' => $this->video,
            'auth_required' => false,
            'auto_play' => false,
            'audio_only' => false,
        ]);
    }

    public function do_embed($params) {
        print view('partial/player_embed', $params);
    }
}

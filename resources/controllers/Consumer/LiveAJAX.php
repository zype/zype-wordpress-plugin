<?php

namespace ZypeMedia\Controllers\Consumer;

class LiveAJAX extends BaseAJAX{
    public function init(){
        add_action('wp_ajax_nopriv_zype_is_on_air', [$this, 'is_on_air']);
        add_action('wp_ajax_zype_is_on_air', [$this, 'is_on_air']);
    }

    public function is_on_air(){
    $res = ['on_air' => \Zype::is_on_air()];
    echo json_encode($res);
    wp_die();
    }
}

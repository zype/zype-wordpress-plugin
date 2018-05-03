<?php

namespace ZypeMedia\Controllers\Consumer;

class FlashAJAX extends BaseAJAX{

    public function init(){
        add_action('wp_ajax_nopriv_zype_flash_messages', [$this, 'get_messages']);
        add_action('wp_ajax_zype_flash_messages', [$this, 'get_messages']);
    }

    public function get_messages(){
        if(isset($_SESSION['zype_flash_messages']) && is_array($_SESSION['zype_flash_messages'])){
            echo json_encode(filter_var_array($_SESSION['zype_flash_messages'], FILTER_SANITIZE_STRING));
            $_SESSION['zype_flash_messages'] = [];
        }
    }
}

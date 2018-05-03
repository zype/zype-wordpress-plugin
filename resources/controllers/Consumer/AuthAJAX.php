<?php
namespace ZypeMedia\Controllers\Consumer;

class AuthAJAX extends BaseAJAX
{
    public function __construct()
    {
        $this->auther = new \ZypeMedia\Services\Auth();
        parent::__construct();
    }

    public function init()
    {
        add_action('wp_ajax_nopriv_zype_logout', [
            $this,
            'logout',
        ]);
        add_action('wp_ajax_zype_logout', [
            $this,
            'logout',
        ]);

        add_action('wp_ajax_nopriv_zype_get_all_ajax', [
            $this,
            'get_all_ajax',
        ]);
        add_action('wp_ajax_zype_get_all_ajax', [
            $this,
            'get_all_ajax',
        ]);
    }

    public function logout()
    {
        $this->auther->logout();
        wp_die();
    }

    public function get_all_ajax()
    {
        $res = [
            'subscriber' => $this->auther->subscriber() ? true: true,
            'logged_in'  => $this->auther->logged_in(),
            'on_air'     => \Zype::is_on_air(),
        ];
        echo json_encode($res);
        wp_die();
    }
}

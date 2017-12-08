<?php

namespace ZypeMedia\Controllers\Consumer;

use ZypeMedia\Models\EstWidget;

if (!defined('ABSPATH')) die();

class EstWidgetAjax extends BaseAJAX
{
    public function __construct()
    {
        $this->auther = new \ZypeMedia\Services\Auth;
        parent::__construct();
    }

    public function init()
    {
        add_action('wp_ajax_nopriv_zype_authorize_from_widget', [
            $this,
            'authorize_from_widget',
        ]);
        add_action('wp_ajax_zype_authorize_from_widget', [
            $this,
            'authorize_from_widget',
        ]);
    }

    public function authorize_from_widget()
    {
        $res = [
            'logged_in' => false,
        ];

        $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);

        if (isset($post['authData']) && $accessToken = $post['authData']) {
            $authData = EstWidget::decrypt($accessToken);
            list($accessToken, $refreshToken) = explode('|', $authData);
            if ($isLoggedIn = $this->auther->authenticate_with_access_token($accessToken, $refreshToken)) {
                $res['logged_in'] = true;
            }
        }
        $this->renderAjax($res);
    }

}

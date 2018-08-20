<?php

namespace ZypeMedia\Providers;

use Themosis\Foundation\ServiceProvider;

use Themosis\Facades\Action;
use Themosis\Facades\Filter;
use Themosis\Facades\Config;
use Themosis\Facades\Asset;
use Themosis\Facades\Ajax;

use ZypeMedia\Services\Auth;
use ZypeMedia\Services\Access;

use ZypeMedia\Models\EstWidget;

use ZypeMedia\Controllers\Consumer;

class HooksService extends ServiceProvider {
    private static $auther;

    /**
     * Register plugin hooks.
     * Define a custom namespace.
     */
    public function register() {
        self::$auther = new Auth();

        // Filter Zype URL
        Filter::add('zype_url', function ($page) {
            if (Config::get("zype.{$page}_url")) {
                return home_url(Config::get("zype.{$page}_url"));
            }

            return home_url();
        });

        // Filter Category URL
        Filter::add('zype_category_url', function ($category, $value) {
            $cats = Config::get("zype.categories")?: [];

            if (array_key_exists($category, $cats)
                && array_key_exists($value, $cats[$category])
                && array_key_exists('url', $cats[$category][$value])
                && $cats[$category][$value]['url'] != ''
            ) {
                return home_url($cats[$category][$value]['url']);
            }

            return home_url(zype_to_permalink($category) . '/' . zype_to_permalink($value));
        }, 10, 2);

        // Filter ZObjects URL
        Filter::add('zype_zobject_url', function ($zobject) {
            if (in_array($zobject, Config::get("zype.zobjects"))) {
                return home_url($zobject);
            }

            return home_url();
        });

        // Zype assets
        Asset::add('zype_checkoutSuccess', 'javascripts/jquery.maskedinput.min.js', ['jquery'], ZYPE_WP_VERSION, 'all');
        Asset::add('zype_wp_js', 'javascripts/zype_wp.js', ['jquery'], ZYPE_WP_VERSION, true);
        Asset::add('zype_stripe_api', 'https://js.stripe.com/v2/stripe.js', false, ZYPE_WP_VERSION, 'all');
        Asset::add('zype_braintree_api', 'https://js.braintreegateway.com/web/dropin/1.9.4/js/dropin.min.js', false, ZYPE_WP_VERSION, 'all');
        Asset::add('slick-js', 'javascripts/slick/slick.js', ['jquery'], ZYPE_WP_VERSION, true);
        Asset::add('slider', 'javascripts/slider.js', ['jquery'], ZYPE_WP_VERSION, true);
        Asset::add('slick', 'javascripts/slick/slick.css', false, ZYPE_WP_VERSION, true);
        Asset::add('slick-theme', 'javascripts/slick/slick-theme.css', ['slick'], ZYPE_WP_VERSION, 'all');
        Asset::add('magnific-popup', 'css/magnific-popup.css', false, ZYPE_WP_VERSION, 'all');
        Asset::add('magnific-popup-js', 'javascripts/jquery.magnific-popup.min.js', ['jquery'], ZYPE_WP_VERSION, true);
        Asset::add('zype_login', 'css/zype_forms/loginform.css', false, ZYPE_WP_VERSION, 'all');
        Asset::add('zype_sign_up', 'css/zype_forms/regform.css', false, ZYPE_WP_VERSION, 'all');
        Asset::add('zype_single_video', 'css/zype_forms/single_video.css', false, ZYPE_WP_VERSION, 'all');
        Asset::add('zype_subscribe_button', 'css/zype_forms/subscription_button.css', false, ZYPE_WP_VERSION, 'all');
        Asset::add('zype_plans', 'css/zype_forms/plans.css', false, ZYPE_WP_VERSION, 'all');
        Asset::add('zype-style', 'css/style_plugin.css', ['slick-theme'], '1.0', 'all');
        Asset::add('zype_checkout', 'https://checkout.stripe.com/checkout.js', false, ZYPE_WP_VERSION, 'all');

        // Zype actions
        Action::add('wp_footer', [$this, 'inlineScripts']);
        Action::add('zype_js_wp_env', [$this, 'inlineScripts']);
        Action::add('admin_menu', [$this, 'adjustWpMenu'], 90);

        // Zype ajax
        Ajax::listen('zype_logout', [$this, 'logout'], 'both');
        Ajax::listen('zype_get_all_ajax', [$this, 'get_all_ajax'], 'both');
        Ajax::listen('zype_player', [$this, 'player'], 'both');
        Ajax::listen('zype_is_on_air', [$this, 'is_on_air'], 'both');
        Ajax::listen('zype_authorize_from_widget', [$this, 'authorize_from_widget'], 'both');
        Ajax::listen('zype_update_profile', [$this, 'update_profile'], 'both');
        Ajax::listen('zype_update_password', [$this, 'update_password'], 'both');
        Ajax::listen('zype_flash_messages', [$this, 'get_messages'], 'both');

        Ajax::listen('zype_auth_markup', [$this, 'zype_auth_markup'], 'both');
        Ajax::listen('zype_login', [$this, 'zype_login'], 'both');
        Ajax::listen('zype_login_ajax', [$this, 'zype_login_ajax'], 'both');
        Ajax::listen('zype_sign_up', [$this, 'zype_sign_up'], 'both');
        Ajax::listen('zype_sign_up_ajax', [$this, 'zype_sign_up'], 'both');
        Ajax::listen('zype_forgot_password', [$this, 'zype_forgot_password'], 'both');
    }

    public function zype_auth_markup() {
        if (\Input::get('type')) {
            echo do_shortcode(
                '[zype_auth type="' . \Input::get('type') . "\" plan_id=\"" . \Input::get('planid') . "\" root_parent=\"" . \Input::get('rootParent') . "\" redirect_url=\"" . \Input::get('redirectURL') . '"]'
            );
        }
        exit;
    }

    public function zype_login() {
        return (new Consumer\Auth())->login_submit_ajax();
    }

    public function zype_login_ajax() {
        return (new Consumer\Auth())->login_submit_ajax(false);
    }

    public function zype_sign_up() {
        return (new Consumer\Auth())->signup_submit_ajax();
    }

    public function zype_forgot_password() {
        return (new Consumer\Profile())->forgot_password_submit_ajax();
    }

    public function get_messages() {
        if(isset($_SESSION['zype_flash_messages']) && is_array($_SESSION['zype_flash_messages'])){
            header("Content-type:application/json");
            echo json_encode(filter_var_array($_SESSION['zype_flash_messages'], FILTER_SANITIZE_STRING));
            $_SESSION['zype_flash_messages'] = [];
        }
    }

    public function adjustWpMenu() {
        remove_submenu_page('zype', 'zype');
    }

    public function update_profile()
    {
        $za = new Auth;
        $consumer_id  = $za->get_consumer_id();
        $access_token = $za->get_access_token();

        $fields = $this->form_vars([
            'name',
            'email',
            'email_confirmation',
        ]);

        $updated = \Zype::update_consumer($consumer_id, $access_token, $fields);

        (new Auth)->sync_cookie();

        wp_redirect(home_url(Config::get('zype.profile_url')));
        exit;
    }

    public function update_password()
    {
        $za = new Auth;
        $consumer_id = $za->get_consumer_id();
        $access_token = $za->get_access_token();
        $email = $za->get_email();

        $updated = false;
        $auth = false;
        $new_password = false;

        if (isset($_POST['current_password'])) {
            $current_password = filter_var($_POST['current_password'], FILTER_SANITIZE_STRING);
            $auth = (new Auth)->login($email, $current_password);
        }

        if ($auth && isset($_POST['new_password']) && isset($_POST['new_password_confirmation'])) {
            $new_password = $this->validate_password($_POST['new_password'], $_POST['new_password_confirmation']);
            $access_token = $za->get_access_token();
        }

        if ($auth && $new_password) {
            $fields['password'] = $new_password;
            $updated = \Zype::update_consumer($consumer_id, $access_token, $fields);
        }

        wp_redirect(home_url(Config::get('zype.profile_url') . "/change-password"));
        exit;
    }

    private function validate_password($password, $password_confirmation)
    {
        $password = filter_var($password, FILTER_SANITIZE_STRING);
        $password_confirmation = filter_var($password_confirmation, FILTER_SANITIZE_STRING);

        if ($password != $password_confirmation) {
            return false;
        }
        if (strlen($password) < 8) {
            return false;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        return $password;
    }

    public function authorize_from_widget() {
        $res = [
            'logged_in' => false,
        ];

        $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);

        if (isset($post['authData']) && $accessToken = $post['authData']) {
            $authData = EstWidget::decrypt($accessToken);
            list($accessToken, $refreshToken) = explode('|', $authData);
            if ($isLoggedIn = self::$auther->authenticate_with_access_token($accessToken, $refreshToken)) {
                $res['logged_in'] = true;
            }
        }

        header("Content-type:application/json");
        echo json_encode($res);
        wp_die();
    }

    public function is_on_air(){
        $res = [
            'on_air' => \Zype::is_on_air()
        ];

        header("Content-type:application/json");
        echo json_encode($res);
        wp_die();
    }

    public function inlineScripts() {
        print view('scripts');
    }

    public function logout() {
        self::$auther->logout();
        wp_die();
    }

    public function get_all_ajax() {
        $res = [
            'subscriber' => self::$auther->subscriber() ? true: true,
            'logged_in'  => self::$auther->logged_in(),
            'on_air'     => \Zype::is_on_air(),
        ];

        header("Content-type:application/json");
        echo json_encode($res);
        wp_die();
    }

    public function player() {
        $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);

        $videoId = isset($post['video_id']) ? $post['video_id'] : 'null';
        $autoplay = 'autoplay=true';
        $key = 'api_key=' . Config::get('zype.player_key');
        $audio_only = '';

        if (isset($post['auto_play']) && $post['auto_play'] == 'false') {
            $autoplay = 'autoplay=false';
        }

        if (isset($post['audio_only']) && $post['audio_only'] == 'true') {
            $audio_only = '&audio=true';
        }

        if (isset($post['auth_required']) && $post['auth_required'] == 'true') {

            $hasUserAccessToVideo = (new Access())->checkUserVideoAccess($videoId);

            if (self::$auther->logged_in() && $hasUserAccessToVideo) {
                $key = 'access_token=' . self::$auther->get_access_token();
            } else {
                $this->authorization_required();
            }
        }

        $res = [
            'audio_only' => $post['audio_only'],
            'embed_url'  => Config::get('zype.playerHost') . '/embed/' . $videoId . '.js?' . $key . '&' . $autoplay . $audio_only,
        ];

        header("Content-type:application/json");
        echo json_encode($res);
        wp_die();
    }

    public function authorization_required() {
        // http_response_code(400);
        $res = ['authorization_required' => true];
        header("Content-type:application/json");
        echo json_encode($res);
        wp_die();
    }

    public function add_body_class($classes)
    {
        global $zype_search;

        $classes[] = $this->template;
        if ($zype_search['is_search'] === true) {
            $classes[] = 'page-search';
        }
        if (isset($this->title) && $this->template != 'plans' && $this->template != 'single') {
            $string = preg_replace("/[^a-z0-9_\s-]/", "", strtolower($this->title));
            $classes[] = str_replace(' ', '-', $string);
        }

        if (isset($this->page)) {
            $classes[] = strtolower($this->page);
        }
        if (isset($this->category_key)) {
            $classes[] = 'zype-category-' . strtolower($this->category_key);
            $classes[] = 'zype-category';
        }

        $classes[] = strtolower(end(explode('\\', get_called_class())));

        return $classes;
    }

    protected function form_vars($names)
    {
        $fields = [];
        foreach ($names as $name) {
            if (isset($_REQUEST[$name])) {
                $fields[$name] = filter_var($_REQUEST[$name], FILTER_SANITIZE_STRING);
            }
        }

        return $fields;
    }

    public function search()
    {
        global $zype_search;
        $zype_search              = [];
        $zype_search['is_search'] = false;
        if (isset($_GET['search'])) {
            $zype_search['term']      = filter_var($_GET['search'], FILTER_SANITIZE_STRING);
            $zype_search['is_search'] = true;
        }
    }

    public function sort()
    {
        global $zype_sort;
        $zype_sort              = [];
        $zype_sort['is_sorted'] = false;
        if (isset($_GET['sort'])) {
            $zype_sort['order']     = filter_var($_GET['sort'], FILTER_SANITIZE_STRING);
            $zype_sort['is_sorted'] = true;
        }
    }

    public function canonical_url($url)
    {
        $url = site_url() . filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_STRING);

        return $url;
    }
}

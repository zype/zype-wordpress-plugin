<?php

namespace ZypeMedia\Providers;

use Themosis\Facades\Action;
use Themosis\Facades\Ajax;
use Themosis\Facades\Asset;
use Themosis\Facades\Config;
use Themosis\Facades\Filter;
use Themosis\Foundation\ServiceProvider;
use ZypeMedia\Controllers\Consumer;
use ZypeMedia\Models\EstWidget;
use ZypeMedia\Services\Access;
use ZypeMedia\Services\Auth;
use ZypeMedia\Validators\Request;

class HooksService extends ServiceProvider
{
    private $auther;
    private $request;

    /**
     * Register plugin hooks.
     * Define a custom namespace.
     */
    public function register()
    {
        $this->auther = new Auth();
        $this->request = Request::capture();
        $this->options = Config::get('zype');

        // Filters
        Filter::add('zype_url', [$this, 'zype_url']);
        Filter::add('zype_category_url', [$this, 'zype_category_url'], 10, 2);
        Filter::add('zype_zobject_url', [$this, 'zype_zobject_url']);

        // Zype assets
        Asset::add('zype_checkoutSuccess', 'javascripts/imaskjs.min.js', ['jquery'], ZYPE_WP_VERSION, 'all');
        Asset::add('zype_wp_js', 'javascripts/zype_wp.js', ['jquery'], ZYPE_WP_VERSION, true);
        Asset::add('zype_stripe_api', 'javascripts/stripe.js', false, ZYPE_WP_VERSION, 'all');
        Asset::add('zype_braintree_api', 'javascripts/dropin.min.js', false, ZYPE_WP_VERSION, 'all');
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
        Asset::add('zype_change_credit_card', 'css/zype_forms/auth/change_credit_card.css', false, ZYPE_WP_VERSION, 'all');
        Asset::add('zype-style', 'css/style_plugin.css', ['slick-theme'], '1.0', 'all');
        Asset::add('zype_checkout', 'javascripts/checkout.js', false, ZYPE_WP_VERSION, 'all');

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
        Ajax::listen('zype_checkout', [$this, 'zype_checkout'], 'both');
        Ajax::listen('zype_login', [$this, 'zype_login'], 'both');
        Ajax::listen('zype_login_ajax', [$this, 'zype_login_ajax'], 'both');
        Ajax::listen('zype_sign_up', [$this, 'zype_sign_up'], 'both');
        Ajax::listen('zype_sign_up_ajax', [$this, 'zype_sign_up'], 'both');
        Ajax::listen('zype_forgot_password', [$this, 'zype_forgot_password'], 'both');
    }

    public function zype_url($page)
    {
        if (!empty($this->options["{$page}_url"])) {
            return home_url($this->options["{$page}_url"]);
        }

        return home_url();
    }

    public function zype_zobject_url($zobject)
    {
        if (in_array($zobject, $this->options["zobjects"])) {
            return home_url($zobject);
        }

        return home_url();
    }

    public function zype_category_url($category, $value)
    {
        $cats = !empty($this->options["categories"])? $this->options["categories"]: [];

        if (array_key_exists($category, $cats)
            && array_key_exists($value, $cats[$category])
            && array_key_exists('url', $cats[$category][$value])
            && $cats[$category][$value]['url'] != ''
        ) {
            return home_url($cats[$category][$value]['url']);
        }

        return home_url(zype_to_permalink($category) . '/' . zype_to_permalink($value));
    }

    public function zype_auth_markup()
    {
        if ($this->request->get('type')) {
            $type = $this->request->validate('type', ['textfield']);
            $plan_id = $this->request->validate('planid', ['textfield']);
            $root_parent = $this->request->validate('root_parent', ['textfield']);
            $redirect_url = $this->request->validate('redirectURL', ['textfield']);
            echo do_shortcode(
                '[zype_auth type="' . $type . "\" plan_id=\"" . $plan_id . "\" root_parent=\"" . $root_parent . "\" redirect_url=\"" . $redirect_url . '"]'
            );
        }
        exit;
    }

    public function zype_checkout()
    {
        $type = $this->request->validate('type', ['textfield']);
        $transaction_type = $this->request->validate('transaction_type', ['textfield']);
        $plan_id = $this->request->validate('plan_id', ['textfield']);
        $video_id = $this->request->validate('video_id', ['textfield']);
        $playlist_id = $this->request->validate('playlist_id', ['textfield']);
        $object_type = $this->request->validate('object_type', ['textfield']);
        $root_parent = $this->request->validate('root_parent', ['textfield']);
        $redirect_url = $this->request->validate('redirectURL', ['textfield']);
        $short_code = ajax_shortcode('zype_video_checkout', [
            'type'              => $type,
            'transaction_type'  => $transaction_type,
            'plan_id'           => $plan_id,
            'root_parent'       => $root_parent,
            'redirect_url'      => $redirect_url,
            'video_id'          => esc_attr($video_id),
            'playlist_id'       => esc_attr($playlist_id),
            'object_type'       => esc_attr($object_type)
        ]);
        echo do_shortcode($short_code);
        exit;
    }

    public function zype_login()
    {
        $redirect_url = $this->request->validate('redirect_url', ['textfield']);
        return (new Consumer\Auth())->login_submit_ajax($redirect_url);
    }

    public function zype_login_ajax()
    {
        return (new Consumer\Auth())->login_submit_ajax(false);
    }

    public function zype_sign_up() {
        $redirect_url = $this->request->validate('redirect_url', ['textfield']);
        return (new Consumer\Auth())->signup_submit_ajax($redirect_url);
    }

    public function zype_forgot_password()
    {
        return (new Consumer\Profile())->forgot_password_submit_ajax();
    }

    public function get_messages()
    {
        if (isset($_SESSION['zype_flash_messages']) && is_array($_SESSION['zype_flash_messages'])) {
            header("Content-type:application/json");
            echo json_encode(filter_var_array($_SESSION['zype_flash_messages'], FILTER_SANITIZE_STRING));
            $_SESSION['zype_flash_messages'] = [];
        }
    }

    public function adjustWpMenu()
    {
        remove_submenu_page('zype', 'zype');
    }

    public function update_profile()
    {
        $za = new Auth;
        $consumer_id = $za->get_consumer_id();
        $access_token = $za->get_access_token();

        $fields = $this->form_vars([
            'name',
            'email',
            'email_confirmation',
        ]);

        $updated = \Zype::update_consumer($consumer_id, $access_token, $fields);

        (new Auth)->sync_cookie();

        wp_redirect(home_url($this->options['profile_url']));
        exit;
    }

    protected function form_vars($names)
    {
        $fields = [];

        foreach ($names as $name) {
            if ($this->request->get($name)) {
                $fields[$name] = $this->request->validate($name, ['textfield']);
            }
        }

        return $fields;
    }

    public function update_password()
    {
        $current_password = $this->request->validate('current_password', ['textfield']);
        $new_password_raw = $this->request->validate('new_password', ['textfield']);
        $new_password_confirmation_raw = $this->request->validate('new_password_confirmation', ['textfield']);
        $errors = (new Consumer\Auth())->update_password($current_password, $new_password_raw, $new_password_confirmation_raw);
        echo json_encode(
            array(
                'errors' => $errors,
            )
        );
        exit();
    }

    public function authorize_from_widget()
    {
        $res = [
            'logged_in' => false,
        ];

        $post = $this->request->validateAll(['textfield']);

        if (isset($post['authData']) && $accessToken = $post['authData']) {
            $authData = EstWidget::decrypt($accessToken);
            list($accessToken, $refreshToken) = explode('|', $authData);
            if ($isLoggedIn = $this->auther->authenticate_with_access_token($accessToken, $refreshToken)) {
                $res['logged_in'] = true;
            }
        }

        header("Content-type:application/json");
        echo json_encode($res);
        wp_die();
    }

    public function is_on_air()
    {
        $res = [
            'on_air' => \Zype::is_on_air()
        ];

        header("Content-type:application/json");
        echo json_encode($res);
        wp_die();
    }

    public function inlineScripts()
    {
        print view('scripts', [
            'options' => $this->options
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
            'subscriber' => $this->auther->subscriber() ? true : true,
            'logged_in' => $this->auther->logged_in(),
            'on_air' => \Zype::is_on_air(),
        ];

        header("Content-type:application/json");
        echo json_encode($res);
        wp_die();
    }

    public function player()
    {
        $post = $this->request->validateAll(['textfield']);

        $videoId = isset($post['video_id']) ? $post['video_id'] : 'null';
        $autoplay = 'autoplay=true';
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
            'embed_url' => $this->options['playerHost'] . '/embed/' . $videoId . '.js?' . $key . '&' . $autoplay . $audio_only,
        ];

        header("Content-type:application/json");
        echo json_encode($res);
        wp_die();
    }

    public function authorization_required()
    {
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

    public function search()
    {
        global $zype_search;

        $zype_search = [];
        $zype_search['is_search'] = false;

        if ($search = $this->request->validate('search', ['textfield'])) {
            $zype_search['term'] = $search;
            $zype_search['is_search'] = true;
        }
    }

    public function sort()
    {
        global $zype_sort;

        $zype_sort = [];
        $zype_sort['is_sorted'] = false;

        if ($sort = $this->request->validate('sort', ['textfield'])) {
            $zype_sort['order'] = $sort;
            $zype_sort['is_sorted'] = true;
        }
    }

    public function canonical_url($url)
    {
        $request_uri = $this->request->validateServer('REQUEST_URI', ['textfield']);
        $url = site_url() . $request_uri;

        return $url;
    }
}

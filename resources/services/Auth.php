<?php

namespace ZypeMedia\Services;

use Firebase\JWT\JWT;
use Themosis\Facades\Config;
use ZypeMedia\Models\V2\Consumer;
use ZypeMedia\Models\V2\Plan;

class Auth extends Component
{
    private static $cookie = false;
    private static $remember_me = false;

    public function __construct()
    {
        parent::__construct();

        if (!self::$request->cookies->get('zype_wp')) {
            self::initialize_cookie();
        }
    }

    public static function initialize_cookie($send_header = false)
    {
        $cookie_d['zype_wp'] = true;
        self::encrypt_cookie($cookie_d, $send_header);
    }

    private static function encrypt_cookie($cookie_d, $send_header = true)
    {
        $cookie_e = JWT::encode($cookie_d, Config::get('zype.cookie_key'));

        if ($send_header) {
            setcookie('zype_wp', $cookie_e, self::cookie_time(), "/");
        }

        self::$request->cookies->set('zype_wp', $cookie_e);
        self::$cookie = $cookie_e;
    }

    private static function cookie_time()
    {
        if (self::remember_me()) {
            return time() + 60 * 60 * 24 * 30;
        }

        return null;
    }

    private static function remember_me()
    {
        if (self::$remember_me) {
            return true;
        } else {
            $cookie_d = self::decrypt_cookie();
            if (isset($cookie_d['remember_me'])) {
                return $cookie_d['remember_me'];
            }

            return false;
        }
    }

    private static function decrypt_cookie()
    {
        if (self::$request->cookies->get('zype_wp')) {
            try {
                self::$cookie = JWT::decode(self::$request->validateCookie('zype_wp', ['textfield']), Config::get('zype.cookie_key'), array('HS256'));
            } catch (\UnexpectedValueException $e) {
                self::initialize_cookie();
                self::$cookie = JWT::decode(self::$request->validateCookie('zype_wp', ['textfield']), Config::get('zype.cookie_key'), array('HS256'));
            }
            self::$cookie = json_decode(json_encode(self::$cookie), true);
        }

        return self::$cookie;
    }

    public static function get_origin()
    {
        $cookie = self::get_cookie();
        if (isset($cookie['origin'])) {
            return $cookie['origin'];
        }

        return false;
    }

    public static function get_cookie()
    {
        $cookie = self::decrypt_cookie();
        self::$cookie = filter_var_array($cookie, FILTER_SANITIZE_STRING);

        return self::$cookie;
    }

    public static function get_consumer_id()
    {
        $cookie = self::get_cookie();
        if (isset($cookie['user_id'])) {
            return $cookie['user_id'];
        }

        return false;
    }

    public static function get_consumer_braintree_id()
    {
        $cookie = self::get_cookie();
        $access_token = self::get_access_token();
        $consumer = \Zype::get_consumer($cookie['user_id'], $access_token);

        return $consumer->braintree_id;
    }

    public static function get_access_token()
    {
        if (self::logged_in()) {
            self::refresh_access_token();
            $cookie = self::get_cookie();
            if (isset($cookie['access_token'])) {
                return $cookie['access_token'];
            }

            return false;
        }

        return false;
    }

    public static function logged_in()
    {
        $cookie = self::get_cookie();
        if (isset($cookie['logged_in']) && $cookie['logged_in'] == true) {
            return true;
        }

        return false;
    }

    public static function refresh_access_token()
    {
        $cookie = self::get_cookie();
        if (time() > $cookie['expires_at']) {
            $res = \Zype::refresh_consumer_token($cookie['refresh_token']);
            if ($res && isset($res->access_token) && isset($res->refresh_token) && isset($res->expires_in)) {
                $expires = time() + $res->expires_in;
                self::set_cookie_vals([
                    'refresh_token' => $res->refresh_token,
                    'access_token' => $res->access_token,
                    'expires_at' => $expires,
                ]);
            } else {
                self::logout();
                wp_redirect(home_url());
            }
        }
    }

    public static function set_cookie_vals($vals)
    {
        $cookie_d = self::decrypt_cookie();
        $cookie_d = array_replace($cookie_d, $vals);
        self::encrypt_cookie($cookie_d);
    }

    public static function logout()
    {
        setcookie('zype_wp', null, strtotime('-1 day'), '/');
        self::$request->cookies->set('zype_wp', null);
        self::$cookie = null;
    }

    public static function get_email()
    {
        $cookie = self::get_cookie();
        if (isset($cookie['user_email'])) {
            return $cookie['user_email'];
        }

        return false;
    }

    public static function get_rss_token()
    {
        $cookie = self::get_cookie();
        if (isset($cookie['user_rss_token'])) {
            return $cookie['user_rss_token'];
        }

        return false;
    }

    public static function subscriber($playlist_id = '')
    {
        $cookie = self::get_cookie();

        if(isset($cookie['global_subscription_count']) && $cookie['global_subscription_count'] > 0) {
            return true;
        }
        if(!$playlist_id || !isset($cookie['tiered_subscriptions']) || empty($cookie['tiered_subscriptions'])) return false;

        $params = [
            'id[]' => array_column($cookie['tiered_subscriptions'], 'id')
        ];
        $plans = Plan::all($params, false);
        $playlist_subscription = array_reduce($plans, function($accum, $plan) use ($playlist_id) {
            return $accum = $accum || in_array($playlist_id, $plan->playlist_ids);
        }, false);

        return $playlist_subscription;
    }


    public static function remaning_plans()
    {
        $cookie = self::get_cookie();
        $plan_ids = Config::get('zype.subscribe_select');

        if(!isset($cookie['tiered_subscriptions']) || empty($cookie['tiered_subscriptions'])) return $plan_ids;
        $tiered_plan_ids = array_column($cookie['tiered_subscriptions'], 'id');
        return array_diff($plan_ids, $tiered_plan_ids);
    }

    public static function login($username, $password, $remember_me = false)
    {
        self::$remember_me = $remember_me;
        $login = \Zype::authenticate($username, $password);

        return self::parse_auth_response($login, $username);
    }

    private static function parse_auth_response($login, $username)
    {
        if (isset($login->access_token) && isset($login->refresh_token) && isset($login->expires_in)) {
            $consumer_id = \Zype::find_consumer_by_token($login->access_token);
            if ($consumer_id) {
                $consumer = Consumer::find($consumer_id);
                if ($consumer && strcasecmp($consumer->email, $username) === 0) {
                    $expires = time() + $login->expires_in;
                    self::set_cookie_vals([
                        'user_id' => $consumer->_id,
                        'user_rss_token' => $consumer->rss_token,
                        'user_email' => $consumer->email,
                        'global_subscription_count' => $consumer->global_subscriptions_count(),
                        'tiered_subscriptions' => $consumer->tiered_subscriptions(),
                        'user_name' => $consumer->name,
                        'refresh_token' => $login->refresh_token,
                        'access_token' => $login->access_token,
                        'expires_at' => $expires,
                        'logged_in' => true,
                        'remember_me' => self::remember_me(),
                    ]);

                    $is_saas_compatability_mode = Config::get('zype.zype_saas_compatability');

                    if (!$is_saas_compatability_mode)
                        return true;

                    if (!$wpUser = get_user_by_email($consumer->email)) {
                        $password = wp_generate_password(12, false);
                        $user_id = wp_create_user($consumer->email, $password, $consumer->email);

                        wp_update_user(
                            array(
                                'ID' => $user_id,
                                'nickname' => $consumer->email
                            )
                        );

                        $wpUser = new \WP_User($user_id);
                        update_user_option($user_id, 'show_admin_bar_front', false);
                        $wpUser->set_role('subscriber');
                    }

                    $status = false;
                    if ($wpUser && !is_user_logged_in()) {
                        wp_clear_auth_cookie();
                        wp_set_current_user($wpUser->ID);
                        wp_set_auth_cookie($wpUser->ID);
                    }

                    if (is_user_logged_in()) {
                        $status = true;
                    }

                    return $status;
                }
            }
        }

        return false;
    }

    public static function social_login($access_token, $user_id, $provider, $username)
    {
        $login = \Zype::social_authenticate($access_token, $user_id, $provider);

        return self::parse_auth_response($login, $username);
    }

    public static function authenticate_with_access_token($accessToken, $refreshToken = '')
    {
        $consumer_id = \Zype::find_consumer_by_token($accessToken);
        if ($consumer_id) {
            $consumer = Consumer::find($consumer_id);
            if ($consumer) {
                $expires = time();
                self::set_cookie_vals([
                    'user_id' => $consumer->_id,
                    'user_rss_token' => $consumer->rss_token,
                    'user_email' => $consumer->email,
                    'global_subscription_count' => $consumer->global_subscriptions_count(),
                    'tiered_subscriptions' => $consumer->tiered_subscriptions(),
                    'user_name' => $consumer->name,
                    'refresh_token' => $refreshToken,
                    'access_token' => $accessToken,
                    'expires_at' => $expires + (60 * 60 * 24),
                    'logged_in' => true,
                    'remember_me' => self::remember_me(),
                ]);

                return true;
            }
        }
    }

    public static function sync_cookie()
    {
        $cookie = self::get_cookie();
        $consumer = Consumer::find_not_cached($cookie['user_id']);
        if ($consumer) {
            self::set_cookie_vals([
                'user_id' => $consumer->_id,
                'user_email' => $consumer->email,
                'global_subscription_count' => $consumer->global_subscriptions_count(),
                'tiered_subscriptions' => $consumer->tiered_subscriptions(),
                'user_name' => $consumer->name,
            ]);
        }
    }

    public static function set_cookie_val($key, $value)
    {
        $cookie_d = self::decrypt_cookie();
        $cookie_d[$key] = filter_var($value, FILTER_SANITIZE_STRING);
        self::encrypt_cookie($cookie_d, false);
    }

    public static function generate_cookie_key()
    {
        return bin2hex(openssl_random_pseudo_bytes(24));
    }
}

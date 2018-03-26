<?php

namespace ZypeMedia\Services;

use Themosis\Facades\Config;
use \Firebase\JWT\JWT;

class Auth {
    private static $cookie = false;
    private static $remember_me = false;

    public function __construct() {
        if (!isset($_COOKIE['zype_wp'])) {
            self::initialize_cookie();
        }
    }

    public static function get_origin()
    {
        $cookie = self::get_cookie();
        if (isset($cookie['origin'])) {
            return $cookie['origin'];
        }

        return false;
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
        $cookie       = self::get_cookie();
        $access_token = self::get_access_token();
        $consumer     = \Zype::get_consumer($cookie['user_id'], $access_token);

        return $consumer->braintree_id;
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

    public static function logged_in()
    {
        $cookie = self::get_cookie();
        if (isset($cookie['logged_in']) && $cookie['logged_in'] == true) {
            return true;
        }

        return false;
    }

    public static function subscriber()
    {
        $cookie = self::get_cookie();

        if (isset($cookie['user_subscription_count']) && $cookie['user_subscription_count'] > -0) {
            return true;
        }

        return false;
    }

    public static function login($username, $password, $remember_me = false)
    {
        self::$remember_me = $remember_me;
        $login = \Zype::authenticate($username, $password);

        return self::parse_auth_response($login, $username);
    }

    public static function social_login($access_token, $user_id, $provider, $username)
    {
        $login = \Zype::social_authenticate($access_token, $user_id, $provider);

        return self::parse_auth_response($login, $username);
    }

    public static function authenticate_with_access_token($accessToken, $refreshToken = '')
    {
        $consumerId = \Zype::find_consumer_by_token($accessToken);
        if ($consumerId) {
            $consumer = \Zype::get_consumer($consumerId, $accessToken);
            if ($consumer) {
                $expires = time();
                self::set_cookie_vals([
                    'user_id'                 => $consumer->_id,
                    'user_rss_token'          => $consumer->rss_token,
                    'user_email'              => $consumer->email,
                    'user_subscription_count' => $consumer->subscription_count,
                    'user_name'               => $consumer->name,
                    'refresh_token'           => $refreshToken,
                    'access_token'            => $accessToken,
                    'expires_at'              => $expires + (60 * 60 * 24),
                    'logged_in'               => true,
                    'remember_me'             => self::remember_me(),
                ]);

                return true;
            }
        }
    }

    public static function sync_cookie()
    {
        $cookie       = self::get_cookie();
        $access_token = self::get_access_token();
        $consumer     = \Zype::get_consumer($cookie['user_id'], $access_token);
        if ($consumer) {
            self::set_cookie_vals([
                'user_id'                 => $consumer->_id,
                'user_email'              => $consumer->email,
                'user_subscription_count' => $consumer->subscription_count,
                'user_name'               => $consumer->name,
            ]);
        }
    }

    public static function logout()
    {
        self::initialize_cookie(true);
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
                    'access_token'  => $res->access_token,
                    'expires_at'    => $expires,
                ]);
            } else {
                self::logout();
                wp_redirect(home_url());
            }
        }
    }

    public static function initialize_cookie($send_header = false)
    {
        $cookie_d['zype_wp'] = true;
        self::encrypt_cookie($cookie_d, $send_header);
    }

    public static function set_cookie_val($key, $value)
    {
        $cookie_d       = self::decrypt_cookie();
        $cookie_d[$key] = filter_var($value, FILTER_SANITIZE_STRING);
        self::encrypt_cookie($cookie_d, false);
    }

    public static function set_cookie_vals($vals)
    {
        $cookie_d = self::decrypt_cookie();
        $cookie_d = array_replace($cookie_d, $vals);
        self::encrypt_cookie($cookie_d);
    }

    public static function get_cookie()
    {
        if (!self::$cookie) {
            $cookie = self::decrypt_cookie();
            self::$cookie = filter_var_array($cookie, FILTER_SANITIZE_STRING);
        }

        return self::$cookie;
    }

    public static function generate_cookie_key()
    {
        return bin2hex(openssl_random_pseudo_bytes(24));
    }

    private static function encrypt_cookie($cookie_d, $send_header = true)
    {
        $cookie_e = JWT::encode($cookie_d, Config::get('zype.cookie_key'));

        if ($send_header) {
            setcookie('zype_wp', $cookie_e, self::cookie_time(), "/");
        }

        $_COOKIE['zype_wp'] = $cookie_e;
        self::$cookie = $cookie_e;
    }

    private static function parse_auth_response($login, $username)
    {
        if (isset($login->access_token) && isset($login->refresh_token) && isset($login->expires_in)) {
            $consumerId = \Zype::find_consumer_by_token($login->access_token);
            if ($consumerId) {
                $consumer = \Zype::get_consumer($consumerId, $login->access_token);
                if ($consumer && strcasecmp($consumer->email, $username) === 0) {
                    $expires = time() + $login->expires_in;
                    self::set_cookie_vals([
                        'user_id'                 => $consumer->_id,
                        'user_rss_token'          => $consumer->rss_token,
                        'user_email'              => $consumer->email,
                        'user_subscription_count' => $consumer->subscription_count,
                        'user_name'               => $consumer->name,
                        'refresh_token'           => $login->refresh_token,
                        'access_token'            => $login->access_token,
                        'expires_at'              => $expires,
                        'logged_in'               => true,
                        'remember_me'             => self::remember_me(),
                    ]);

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

    private static function decrypt_cookie()
    {
        if (!empty($_COOKIE['zype_wp'])) {
            self::$cookie = JWT::decode($_COOKIE['zype_wp'], Config::get('zype.cookie_key'), array('HS256'));
            self::$cookie = json_decode(json_encode(self::$cookie), true);
        }

        return self::$cookie;
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

    private static function cookie_time()
    {
        if (self::remember_me()) {
            return time() + 60 * 60 * 24 * 30;
        }

        return null;
    }
}
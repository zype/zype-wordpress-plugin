<?php

namespace Zype\Core;

require_once(__DIR__ . '/Api.php');
require_once(__DIR__ . '/Response.php');

use Api;
use ZypeMedia\Validators\Request;

class Wrapper
{
    private static $options = array();
    private static $request = array();

    public function __construct($options = [])
    {
        self::$request = Request::capture();

        if (!$options) {
            require_once 'config.php';
        }

        self::$options = $options;
        new Api(self::$options);
    }

    public static function get_all_categories()
    {
        $api_params = [
            'api_key' => self::$options['read_only_key'],
            'per_page' => 500,
        ];

        self::apply_sort($api_params, 'alphabetical');

        return Api::get_categories($api_params);
    }

    private static function apply_sort(&$params, $default)
    {
        $s = self::is_sort() ? self::is_sort() : $default;
        switch ($s) {
            case 'alphabetical':
                $params['order'] = 'asc';
                $params['sort'] = 'title';
                break;

            case 'latest':
            case 'recent':
                $params['order'] = 'desc';
                $params['sort'] = 'updated_at';
                break;

            case 'published':
                $params['order'] = 'desc';
                $params['sort'] = 'published_at';
                break;
        }
    }

    private static function is_sort()
    {
        if (self::$request->get('sort')) {
            return self::$request->validate('sort', ['textfield']);
        }

        return false;
    }

    public static function get_all_zobject_types()
    {
        $api_params = [
            'api_key' => self::$options['read_only_key'],
            'per_page' => 500,
        ];

        self::apply_sort($api_params, 'alphabetical');

        return Api::get_zobject_types($api_params);
    }

    public static function get_playlist_videos($id, $page = 1, $per_page = 500)
    {
        $api_params = [
            'app_key' => self::$options['app_key'],
            'per_page' => $per_page,
            'page' => $page,
        ];

        return Api::get_playlist_videos($id, $api_params);
    }

    public static function get_playlists($params)
    {
        $sort = isset($params['sort']) ? $params['sort'] : 'alphabetical';

        $api_params = array_merge($params,[
            'api_key' => self::$options['read_only_key'],
            'sort' => $sort
        ]);


        self::apply_sort($api_params, $sort);
        self::apply_search($api_params);

        return Api::get_playlists($api_params);
    }

    public static function get_playlists_by($by, $page = null, $per_page = 500, $sort = null, $order = null)
    {
        if ($sort == null) {
            $sort = 'alphabetical';
        }

        $api_params = [
            'app_key' => self::$options['app_key'],
            'per_page' => $per_page,
            'page' => $page,
            'sort' => $sort,
            'order' => $order
        ];

        foreach ($by as $key => $value) {
            $api_params[$key] = $value;
        }

        self::apply_sort($api_params, $sort);
        self::apply_search($api_params);

        return Api::get_playlists($api_params);
    }

    private static function apply_search(&$params)
    {
        $s = self::is_search();
        if ($s) {
            $params['q'] = $s;
        }
    }

    private static function is_search()
    {
        if (self::$request->get('search')) {
            return self::$request->validate('search', ['textfield']);
        }

        return false;
    }

    public static function get_playlist($id)
    {
        $api_params = [
            'api_key' => self::$options['read_only_key'],
        ];

        return Api::get_playlist($id, $api_params);
    }

    public static function get_videos($params = [], $suppress_search = false)
    {
        $api_params = array_merge($params,[
            'api_key' => self::$options['read_only_key'],
        ]);

        if (!empty(self::$options['excluded_categories']) && (sizeof(self::$options['excluded_categories']) > 0)) {
            $api_params['category!'] = [];
            foreach (self::$options['excluded_categories'] as $excluded_category) {
                $api_params['category!'][$excluded_category] = 'true';
            }
        }

        if(empty($api_params['sort'])) {
            self::apply_sort($api_params, 'published');
        }

        if (!$suppress_search) {
            self::apply_search($api_params);
        }

        return Api::get_videos($api_params);
    }

    public static function get_zobjects($type, $page = null, $per_page = 2, $sort = null, $suppress_search = false)
    {
        if ($sort == null) {
            $sort = 'alphabetical';
        }

        $api_params = [
            'api_key' => self::$options['read_only_key'],
            'per_page' => $per_page,
            'page' => $page,
            'zobject_type' => $type,
        ];

        self::apply_sort($api_params, $sort);

        if (!$suppress_search) {
            self::apply_search($api_params);
        }

        return Api::get_zobjects($api_params);
    }

    public static function get_zobjects_by($type, $by, $page = null, $per_page = 2, $sort = null)
    {
        if ($sort == null) {
            $sort = 'alphabetical';
        }

        $api_params = [
            'api_key' => self::$options['read_only_key'],
            'per_page' => $per_page,
            'page' => $page,
            'zobject_type' => $type,
        ];

        foreach ($by as $key => $value) {
            $api_params[$key] = $value;
        }

        self::apply_sort($api_params, $sort);
        self::apply_search($api_params);

        return Api::get_zobjects($api_params);
    }

    public static function get_zobject($type, $id)
    {
        $api_params = [
            'api_key' => self::$options['read_only_key'],
            'zobject_type' => $type,
            'friendly_title' => $id,
        ];

        return Api::get_zobject($api_params);
    }

    public static function get_video($id)
    {
        $api_params = [
            'api_key' => self::$options['read_only_key'],
        ];

        return Api::get_video($id, $api_params);
    }

    public static function get_videos_by($by, $page = null, $per_page = 2, $admin = false, $no_cache = false, $exclude = false)
    {
        $api_params = [
            'api_key' => $admin ? self::$options['admin_key'] : self::$options['read_only_key'],
            'per_page' => $per_page,
            'page' => $page,
        ];

        foreach ($by as $key => $value) {
            $api_params[$key] = $value;
        }

        if ($exclude) {
            if (sizeof(self::$options['excluded_categories'] > 0)) {
                $api_params['category!'] = [];
                foreach (self::$options['excluded_categories'] as $excluded_category) {
                    $api_params['category!'][$excluded_category] = 'true';
                }
            }
        }

        self::apply_sort($api_params, 'published');
        self::apply_search($api_params);

        // no cache
        return Api::get_videos($api_params);
    }

    public static function get_zobject_videos($id, $page, $perPage = 2)
    {
        $api_params = [
            'api_key' => self::$options['read_only_key'],
            'per_page' => $perPage,
            'page' => $page,
            'zobject_id' => $id,
        ];

        self::apply_sort($api_params, 'published');
        self::apply_search($api_params);

        return Api::get_videos($api_params);
    }

    public static function get_video_zobjects($type = null, $id)
    {
        $api_params = [
            'api_key' => self::$options['read_only_key'],
            'per_page' => 500,
            'video_id' => $id,
        ];

        if ($type) {
            $api_params['zobject_type'] = $type;
        }

        self::apply_sort($api_params, 'alphabetical');

        return Api::get_all_zobjects($api_params);
    }

    public static function get_category_videos($cat_id, $page, $per_page = 10)
    {
        $api_params = [
            'api_key' => self::$options['read_only_key'],
            'per_page' => $per_page,
            'page' => $page,
            'category' => ['Highlight' => 'true'],
        ];

        self::apply_sort($api_params, 'published');
        self::apply_search($api_params);

        return Api::get_videos($api_params);
    }

    public static function authenticate($username, $password)
    {
        $api_params = [
            'grant_type' => 'password',
            'client_id' => self::$options['oauth_client_id'],
            'client_secret' => self::$options['oauth_client_secret'],
            // TODO: email param is added for compatibility with staging, it should be removed eventually
            // 'email'         => strtolower($username),
            'username' => strtolower($username),
            'password' => $password,
        ];

        return Api::authenticate($api_params);
    }

    public static function social_authenticate($access_token, $user_id, $provider)
    {
        $api_params = [
            'client_id' => self::$options['oauth_client_id'],
            'client_secret' => self::$options['oauth_client_secret'],
            'token' => $access_token,
            'provider' => $provider,
            'uid' => $user_id,
            'grant_type' => 'social_login',
        ];

        return Api::social_authenticate($api_params);
    }

    public static function refresh_consumer_token($refresh_token)
    {
        $api_params = [
            'grant_type' => 'refresh_token',
            'client_id' => self::$options['oauth_client_id'],
            'client_secret' => self::$options['oauth_client_secret'],
            'refresh_token' => $refresh_token,
        ];

        return Api::refresh_consumer_token($api_params);
    }

    public static function find_consumer_by_token($token)
    {
        $api_params = [
            'access_token' => $token,
        ];

        return Api::find_consumer_by_token($api_params);
    }

    public static function find_consumer_by_rss_token($rss_token)
    {
        $api_params = [
            'api_key' => self::$options['admin_key'],
            'rss_token' => $rss_token,
        ];

        return Api::find_consumer_by_rss_token($api_params);
    }

    public static function is_on_air()
    {
        $api_params = [
            'api_key' => self::$options['read_only_key'],
            'on_air' => 'true',
        ];

        return Api::is_on_air($api_params);
    }

    public static function get_consumer($id, $token = '')
    {
        $api_params = [];
        if($token) {
            $api_params['access_token'] = $token;
        }
        else {
            $api_params['api_key'] = self::$options['admin_key'];
        }

        return Api::get_consumer($id, $api_params);
    }

    public static function create_consumer($consumer)
    {
        $api_params = [
            'api_key' => self::$options['admin_key'],
            'consumer' => $consumer,
        ];

        return Api::create_consumer($api_params);
    }

    public static function get_consumer_subscription($id)
    {
        if ($id) {
            $api_params = [
                'api_key' => self::$options['admin_key'],
                'consumer_id' => $id,
            ];

            return Api::get_consumer_subscription($api_params);
        }

        return false;
    }

    public static function get_consumer_transactions($id)
    {
        if ($id) {
            $api_params = [
                'api_key' => self::$options['admin_key'],
                'consumer_id' => $id,
            ];

            return Api::get_consumer_transactions($api_params);
        }

        return false;
    }

    public static function get_consumer_entitled_videos($access_token, $params = [])
    {
        if ($access_token) {

            $api_params = array_merge($params,[
                'access_token' => $access_token
            ]);

            return Api::get_consumer_entitled_videos($api_params);
        }

        return false;
    }

    public static function get_consumer_entitled_playlists($access_token, $params = [])
    {
        if ($access_token) {

            $api_params = array_merge($params,[
                'access_token' => $access_token
            ]);

            return Api::get_consumer_entitled_playlists($api_params);
        }

        return false;
    }

    public static function get_video_entitlement($access_token, $video_id, $params = [])
    {
        if ($access_token) {

            $api_params = array_merge($params,[
                'access_token' => $access_token
            ]);

            return Api::get_video_entitlement($video_id ,$api_params);
        }

        return false;
    }

    public static function get_consumer_stripe_data($id)
    {
        if ($id) {
            $api_params = [
                'api_key' => self::$options['admin_key'],
            ];

            return Api::get_consumer_stripe_data($id, $api_params);
        }

        return false;
    }

    public static function get_consumer_braintree_data($id)
    {
        if ($id) {
            $api_params = [
                'api_key' => self::$options['admin_key'],
            ];

            return Api::get_consumer_braintree_data($id, $api_params);
        }

        return false;
    }

    public static function find_consumer_by_email($email)
    {
        $api_params = [
            'api_key' => self::$options['admin_key'],
            'email' => strtolower($email),
        ];

        return Api::find_consumer($api_params);
    }

    public static function find_consumer_by_email_and_password_token($email, $password_token)
    {
        $api_params = [
            'api_key' => self::$options['admin_key'],
            'email' => strtolower($email),
            'password_token' => $password_token,
        ];

        return Api::find_consumer($api_params);
    }

    public static function update_consumer($id, $token, $fields)
    {
        $api_params = [
            'access_token' => $token,
            'consumer' => $fields,
        ];

        return Api::update_consumer($id, $api_params);
    }

    public static function link_device($id, $pin)
    {
        $api_params = [
            'api_key' => self::$options['admin_key'],
            'consumer_id' => $id,
            'pin' => $pin,
        ];

        return Api::link_device($api_params);
    }

    public static function admin_update_consumer($id, $fields)
    {
        $api_params = [
            'api_key' => self::$options['admin_key'],
            'consumer' => $fields,
        ];

        return Api::update_consumer($id, $api_params);
    }

    public static function get_all_plans($params = [])
    {
        $api_params = array_merge($params,[
            'api_key' => self::$options['admin_key'],
            'per_page' => 500
        ]);

        self::apply_sort($api_params, 'alphabetical');

        return Api::get_plans($api_params);
    }

    public static function get_plan($id)
    {
        $api_params = [
            'api_key' => self::$options['admin_key'],
        ];

        return Api::get_plan($id, $api_params);
    }

    public static function get_all_pass_plans()
    {
        $api_params = [
            'api_key' => self::$options['admin_key'],
        ];

        return Api::get_all_pass_plans($api_params);
    }

    public static function get_pass_plan($id)
    {
        $api_params = [
            'api_key' => self::$options['admin_key'],
        ];

        return Api::get_pass_plan($id, $api_params);
    }

    public static function create_subscription($subscription)
    {
        $api_params = [
            'api_key' => self::$options['admin_key'],
            'subscription' => $subscription,
        ];

        return Api::create_subscription($api_params);
    }

    public static function get_transactions($params = array(), $page = null, $perPage = null)
    {
        $api_params = [
            'api_key' => self::$options['admin_key'],
            'per_page' => $perPage,
            'page' => $page,
        ];

        $api_params = array_merge($api_params, $params);

        return Api::get_transactions($api_params);
    }

    public static function create_transaction($transaction, $provider)
    {
        $api_params = [
            'api_key' => self::$options['admin_key'],
            'transaction' => $transaction,
            'provider' => $provider,
        ];

        return Api::create_transaction($api_params);
    }

    public static function cancel_subscription($subscription_id)
    {
        $api_params = [
            'api_key' => self::$options['admin_key']
        ];

        return Api::cancel_subscription($subscription_id, $api_params);
    }

    public static function change_subscription($subscription_id, $fields)
    {
        $api_params = [
            'api_key' => self::$options['admin_key'],
            'subscription' => $fields,
        ];

        return Api::change_subscription($subscription_id, $api_params);
    }

    public static function change_card($consumer_id, $stripe_card_token)
    {
        $api_params = [
            'api_key' => self::$options['admin_key'],
            'card' => [
                'stripe_card_token' => $stripe_card_token,
            ],
        ];

        return Api::change_card($consumer_id, $api_params);
    }
}

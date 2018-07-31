<?php

class Api
{

    private static $authClient;
    private static $resourceClient;
    private static $resourceClientHttp;
    private static $options;
    private static $body;

    /**
     * Api options.
     *
     * @var array
     */
    public function __construct($options = array())
    {
        self::$options = $options;

        $authPoint = trim(self::$options['authpoint'], '/') . '/';
        $endPoint = trim(self::$options['endpoint'], '/') . '/';

        self::$authClient = $authPoint;
        self::$resourceClient = $endPoint;
        self::$resourceClientHttp = str_replace('https://', 'http://', $endPoint);
    }

    public static function __callStatic($name, $arguments)
    {
        if (method_exists(__CLASS__, "_{$name}")) {
            try {
                return call_user_func_array(array(__CLASS__, "_{$name}"), $arguments);
            } catch (\Exception $e) {
                if (self::$body) {
                    if (
                        empty($_SESSION['zype_errors']) ||
                        !is_array($_SESSION['zype_errors'])
                    ) {
                        $_SESSION['zype_errors'] = [];
                    }

                    if ($error = self::$body->getMessage()) {
                        array_push($_SESSION['zype_errors'], $error);
                    }
                }

                return false;
            }
        }
    }

    private static function request($method, $endpoint, $query, $is_auth = false, $cache = false)
    {
        if ($is_auth) {
            $url = self::$authClient . $endpoint . ($method == 'GET' ? '?' . http_build_query($query) : '');
        } else {
            $url = self::$resourceClient . $endpoint . ($method == 'GET' ? '?' . http_build_query($query) : '');

            if ($cache && $response = get_transient('zype_api_' . substr(md5($url), 0, 15))) {
                return json_decode($response);
            }
        }

        $response = wp_remote_request($url, [
            'method' => $method,
            'body' => ($method != 'GET' ? $query : ''),
            'sslverify' => false,
            'timeout' => '120',
        ]);

        if ($cache) {
            set_transient('zype_api_' . substr(md5($url), 0, 15), wp_remote_retrieve_body($response), (self::$options['cache_time'] ?: 86400));
        }

        $response = new Response(wp_remote_retrieve_body($response));
        self::$body = $response->getBody();

        return self::$body;
    }


    private static function _get_playlist_videos($id, $query)
    {
        return self::request("GET", "playlists/{$id}/videos", $query, false, true)->response;
    }

    private static function _get_playlists($query)
    {
        return self::request("GET", "playlists", $query, false, true)->response;
    }

    private static function _get_playlist($id, $query)
    {
        return self::request("GET", "playlists/{$id}", $query, false, true)->response;
    }

    private static function _get_videos($query)
    {
        return self::request("GET", "videos", $query)->response;
    }

    private static function _get_all_pass_plans($query)
    {
        return self::request("GET", "pass_plans", $query)->response;
    }

    private static function _get_pass_plan($id, $query)
    {
        return self::request('GET', "pass_plans/{$id}", $query)->response;
    }

    private static function _get_video($id, $query)
    {
        return self::request("GET", "videos/{$id}", $query)->response;
    }

    private static function _get_zobject_types($query)
    {
        return self::request("GET", "zobject_types", $query)->response;
    }

    private static function _get_zobjects($query)
    {
        return self::request("GET", "zobjects", $query);
    }

    private static function _get_all_zobjects($query)
    {
        return self::get_zobjects($query)->response;
    }

    private static function _get_zobject($query)
    {
        return self::request("GET", "zobjects", $query)->response[0];
    }

    private static function _get_categories($query)
    {
        return self::request("GET", "categories", $query)->response;
    }

    private static function _authenticate($query)
    {
        return self::request("POST", "", $query, true, false);
    }

    private static function _social_authenticate($query)
    {
        return self::request("POST", "", $query, true, false);
    }

    private static function _refresh_consumer_token($query)
    {
        return self::request("POST", "", $query, true, false);
    }

    private static function _find_consumer_by_token($query)
    {
        return self::request("GET", "info", $query, true, false)->resource_owner_id;
    }

    private static function _find_consumer_by_rss_token($query)
    {
        $result = self::request("GET", "consumers", $query);

        return isset($result->response[0]) ? $result->response[0] : false;
    }

    private static function _find_consumer($query)
    {
        $result = self::request("GET", "consumers", $query);

        return isset($result->response[0]) ? $result->response[0] : false;
    }

    private static function _get_consumer($id, $query)
    {
        return self::request("GET", "consumers/{$id}", $query)->response;
    }

    private static function _update_consumer($id, $query)
    {
        return self::request("PUT", "consumers/{$id}", $query)->response;
    }

    private static function _link_device($query)
    {
        return self::request("PUT", "pin/link", $query)->response;
    }

    private static function _create_consumer($query)
    {
        return self::request("POST", "consumers", $query);
    }

    private static function _get_consumer_subscription($query)
    {
        $result = self::request("GET", "subscriptions", $query);

        return isset($result->response[0]) ? $result->response[0] : false;
    }

    private static function _get_consumer_transactions($query)
    {
        return self::request("GET", "transactions", $query)->response;
    }

    private static function _get_plans($query)
    {
        return self::request("GET", "plans", $query)->response;
    }

    private static function _get_plan($id, $query)
    {
        return self::request("GET", "plans/{$id}", $query)->response;
    }

    private static function _create_subscription($query)
    {
        return self::request("POST", "subscriptions", $query);
    }

    private static function _create_transaction($query)
    {
        return self::request("POST", "transactions", $query);
    }

    private static function _get_transactions($query)
    {
        return self::request("GET", "transactions", $query);
    }

    private static function _cancel_subscription($id, $query)
    {
        return self::request("DELETE", "subscriptions/{$id}", $query);
    }

    private static function _change_subscription($id, $query)
    {
        return self::request("PUT", "subscriptions/{$id}", $query);
    }

    private static function _change_card($id, $query)
    {
        return self::request("POST", "consumers/{$id}/cards", $query);
    }

    private static function _get_consumer_stripe_data($id, $query)
    {
        return self::request("GET", "consumers/{$id}/stripe", $query)->response;
    }

    private static function _get_consumer_braintree_data($id, $query)
    {
        return self::request("GET", "consumers/{$id}/braintree", $query)->response;
    }

    private static function _is_on_air($query)
    {
        $result = self::request("GET", "videos", $query);

        if (isset($result->response) && isset($result->response[0]) && isset($result->response[0]->_id)) {
            return 'yes';
        }

        return 'no';
    }
}

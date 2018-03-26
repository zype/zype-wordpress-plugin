<?php

use GuzzleHttp\Client;
use GuzzleHttp\HandlerStack;
use League\Flysystem\Adapter\Local;
use Kevinrob\GuzzleCache\CacheMiddleware;
use Kevinrob\GuzzleCache\Strategy\GreedyCacheStrategy;
use Kevinrob\GuzzleCache\Storage\FlysystemStorage;

class Api {
    /**
     * Http client.
     *
     * @var \GuzzleHttp\Client
     */
    private static $authClient;
    private static $resourceClient;
    private static $resourceClientHttp;
    private static $resourceClientCached;
    private static $options;

    /**
     * Api options.
     *
     * @var array
     */
    public function __construct($options = array()) {
        try {
            self::$options = $options;

            $authPoint = trim(self::$options['authpoint'], '/') . '/';
            $endPoint = trim(self::$options['endpoint'], '/') . '/';

            self::$authClient = new Client([
                'base_uri' => $authPoint,
                'timeout' => 120,
            ]);

            self::$resourceClient = new Client([
                'base_uri' => $endPoint,
                'timeout' => 120,
            ]);

            self::$resourceClientHttp = new Client([
                'base_uri' => str_replace('https://', 'http://', $endPoint),
                'timeout' => 120,
            ]);
            
            $cache_dir = new Local(__DIR__ . '/cache');

            if (defined('WP_CONTENT_DIR')) {
                $upload_dir = wp_upload_dir();
                $cache_dir = $upload_dir['basedir'] . '/zype';

                if (!file_exists($cache_dir)) {
                    wp_mkdir_p($cache_dir);
                }

                $cache_dir = new Local($cache_dir);
            }

            $stack = HandlerStack::create();
            $stack->push(
                new CacheMiddleware(
                    new GreedyCacheStrategy(
                        new FlysystemStorage(
                            $cache_dir
                        ),
                        self::$options['cache_time']?: 86400
                    )
                ),
                "greedy-cache"
            );

            self::$resourceClientCached = new Client([
                'handler'  => $stack,
                'base_uri' => $endPoint,
                'timeout' => 120,
            ]);
        } catch (\Exception $e) {}
    }

    public static function __callStatic($name, $arguments) {
        if (method_exists(__CLASS__, "_{$name}")) {
            try {
                return call_user_func_array(array(__CLASS__, "_{$name}"), $arguments);
            } catch (GuzzleHttp\Exception\ClientException $e) {
                $response = $e->getResponse();
                $msg = json_decode($response->getBody()->getContents());

                if ($msg) {
                    if (
                        empty($_SESSION['zype_errors']) ||
                        !is_array($_SESSION['zype_errors'])
                    ) {
                        $_SESSION['zype_errors'] = [];
                    }

                    if (!empty($msg->error)) {
                        array_push($_SESSION['zype_errors'], [
                            'type' => $msg->error,
                            'description' => $msg->error_description
                        ]);
                    } else if (!empty($msg->message)) {
                        array_push($_SESSION['zype_errors'], [
                            'type' => 'Request error',
                            'description' => $msg->message
                        ]);
                    }
                }

                return false;
            }
        }
    }

    private static function _get_playlist_videos($id, $query) {
        return self::request("GET", "playlists/{$id}/videos", $query)->response;
    }

    private static function _get_playlists($query) {
        return self::request("GET", "playlists", $query)->response;
    }

    private static function _get_playlist($id, $query) {
        return self::request("GET", "playlists/{$id}", $query)->response;
    }

    private static function _get_videos($query) {
        return self::request("GET", "videos", $query)->response;
    }

    private static function _get_all_pass_plans($query) {
       return self::request("GET", "pass_plans", $query)->response;
    }

    private static function _get_pass_plan($id, $query) {
        return self::request('GET', "pass_plans/{$id}", $query)->response;
    }

    private static function _get_video($id, $query) {
        return self::request("GET", "videos/{$id}", $query, false, false)->response;
    }

    private static function _get_zobject_types($query) {
        return self::request("GET", "zobject_types", $query)->response;
    }

    private static function _get_zobjects($query) {
        return self::request("GET", "zobjects", $query);
    }

    private static function _get_all_zobjects($query) {
        return self::get_zobjects($query)->response;
    }

    private static function _get_zobject($query) {
        return self::request("GET", "zobjects", $query)->response[0];
    }

    private static function _get_categories($query) {
        return self::request("GET", "categories", $query)->response;
    }

    private static function _authenticate($query) {
        return self::request("POST", "", $query, true, false);
    }

    private static function _social_authenticate($query) {
        return self::request("POST", "", $query, true, false);
    }

    private static function _refresh_consumer_token($query) {
        return self::request("POST", "", $query, true, false);
    }

    private static function _find_consumer_by_token($query) {
        return self::request("GET", "info", $query, true, false)->resource_owner_id;
    }

    private static function _find_consumer_by_rss_token($query) {
        $result = self::request("GET", "consumers", $query, false, false);

        return isset($result->response[0])? $result->response[0]: false;
    }

    private static function _find_consumer($query) {
        $result = self::request("GET", "consumers", $query, false, false);

        return isset($result->response[0])? $result->response[0]: false;
    }

    private static function _get_consumer($id, $query) {
        return self::request("GET", "consumers/{$id}", $query, false, false)->response;
    }

    private static function _update_consumer($id, $query) {
        return self::request("PUT", "consumers/{$id}", $query, false, false)->response;
    }

    private static function _link_device($query) {
        return self::request("PUT", "pin/link", $query, false, false)->response;
    }

    private static function _create_consumer($query) {
        return self::request("POST", "consumers", $query, false, false);
    }

    private static function _get_consumer_subscription($query) {
        $result = self::request("GET", "subscriptions", $query, false, false);

        return isset($result->response[0])? $result->response[0]: false;
    }

    private static function _get_consumer_transactions($query) {
        return self::request("GET", "transactions", $query, false, false)->response;
    }

    private static function _get_plans($query) {
        return self::request("GET", "plans", $query)->response;
    }

    private static function _get_plan($id, $query) {
        return self::request("GET", "plans/{$id}", $query)->response;
    }

    private static function _create_subscription($query) {
        return self::request("POST", "subscriptions", $query, false, false);
    }

    private static function _create_transaction($query) {
        return self::request("POST", "transactions", $query, false, false);
    }

    private static function _get_transactions($query) {
        return self::request("GET", "transactions", $query);
    }

    private static function _cancel_subscription($id, $query) {
        return self::request("DELETE", "subscriptions/{$id}", $query, false, false);
    }

    private static function _change_subscription($id, $query) {
        return self::request("PUT", "subscriptions/{$id}", $query, false, false);
    }

    private static function _change_card($id, $query) {
        return self::request("POST", "consumers/{$id}/cards", $query, false, false);
    }

    private static function _get_consumer_stripe_data($id, $query) {
        return self::request("GET", "consumers/{$id}/stripe", $query, false, false)->response;
    }

    private static function _get_consumer_braintree_data($id, $query) {
        return self::request("GET", "consumers/{$id}/braintree", $query, false, false)->response;
    }

    private static function _is_on_air($query) {
        $result = self::request("GET", "videos", $query, false, false);

        if (isset($result->response) && isset($result->response[0]) && isset($result->response[0]->_id)) {
            return 'yes';
        }

        return 'no';
    }

    private static function request($method, $endpoint, $query, $is_auth = false, $cache = true) {

        if ($is_auth) {
            $client = self::$authClient;
        } else if ($cache) {
            $client = self::$resourceClientCached;
        } else {
            $client = self::$resourceClient;
        }

        if (!$is_auth && in_array($method, ['PUT'])) {
            $client = self::$resourceClientHttp;
        }

        $response = $client->request($method, $endpoint, [
            ($method == 'GET'? 'query': 'json') => $query,
            'verify' => false
        ]);

        return json_decode($response->getBody());
    }
}

<?php

namespace ZypeMedia\Controllers;

use Themosis\Facades\Config;

class Admin extends Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function admin_videos_page()
    {
        $allow_rss_feed = false;
        $zm = (new \ZypeMedia\Models\zObject('rss feed settings'));
        $zm->all_by(['title' => 'default'], ['per_page' => 1]);
        if ($zm->collection && sizeof($zm->collection) > 0) {
            $allow_rss_feed = true;
        }

        $categories = \Zype::get_all_categories();
        echo view('admin.videos', [
            'options' => $this->options,
            'categories' => $categories,
            'allow_rss_feed' => $allow_rss_feed
        ]);

        wp_die();
    }

    public function admin_videos_page_save()
    {
        if (wp_verify_nonce($this->request->validate('_wpnonce'), 'zype_videos')) {
            $new_options = [
                'rss_enabled' => $this->request->validate('rss_enabled', ['bool']),
                'rss_url' => $this->request->validate('rss_url', ['textfield'], $this->options['rss_url']),
                'audio_only_enabled' => $this->request->validate('audio_only_enabled', ['bool']),
                'excluded_categories' => is_array($this->request->validate('excluded_categories')) ? $this->request->validate('excluded_categories') : [],
                'flush' => true,
            ];
            $this->options = array_replace($this->options, $new_options);
            $this->update_options();
            zype_wp_admin_message('updated', 'Changes successfully saved!');
        } else {
            zype_wp_admin_message('error', 'Something has gone wrong.');
        }
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit;
    }

    private function update_options()
    {
        update_option('zype_wp', $this->options);
        $this->options = get_option('zype_wp');
    }

    public function admin_grid_screen_page()
    {
        echo view('admin.grid_screen', [
            'options' => $this->options
        ]);

        wp_die();
    }

    public function admin_grid_screen_page_save()
    {
        if (wp_verify_nonce($this->request->validate('_wpnonce'), 'zype_grid_screen')) {
            $new_options = [
                'grid_screen_parent' => $this->request->validate('grid_screen_parent', ['textfield']),
            ];
            $this->options = array_replace($this->options, $new_options);
            $this->update_options();
            zype_wp_admin_message('updated', 'Changes successfully saved!');
        } else {
            zype_wp_admin_message('error', 'Something has gone wrong.');
        }
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit;
    }

    public function admin_api_keys_page()
    {
        echo view('admin.api_keys', [
            'options' => $this->options
        ]);

        wp_die();
    }

    public function admin_api_keys_page_save()
    {
        if (wp_verify_nonce($this->request->validate('_wpnonce'), 'zype_api_keys')) {
            $new_options = [
                'app_key' => $this->request->validate('app_key', ['textfield']),
                'admin_key' => $this->request->validate('admin_key', ['textfield']),
                'embed_key' => $this->request->validate('embed_key', ['textfield']),
                'player_key' => $this->request->validate('player_key', ['textfield']),
                'read_only_key' => $this->request->validate('read_only_key', ['textfield']),
                'braintree_environment' => $this->request->validate('braintree_environment', ['textfield']),
                'braintree_merchant_id' => $this->request->validate('braintree_merchant_id', ['textfield']),
                'braintree_private_key' => $this->request->validate('braintree_private_key', ['textfield']),
                'braintree_public_key' => $this->request->validate('braintree_public_key', ['textfield']),
                'stripe_pk' => $this->request->validate('stripe_pk', ['textfield']),
                'oauth_client_id' => $this->request->validate('oauth_client_id', ['textfield']),
                'oauth_client_secret' => $this->request->validate('oauth_client_secret', ['textfield']),
                'zype_saas_compatability' => $this->request->validate('zype_saas_compatability', ['bool']),
                'playlist_pagination' => $this->request->validate('playlist_pagination', ['bool'])
            ];

            $zype_environment = $this->request->validate('zype_environment');
            if ($zype_environment && isset($this->zypeEnvironmentSettings[$zype_environment])) {
                $new_options['endpoint'] = $this->zypeEnvironmentSettings[$zype_environment]['endpoint'];
                $new_options['authpoint'] = $this->zypeEnvironmentSettings[$zype_environment]['authpoint'];
                $new_options['estWidgetHost'] = $this->zypeEnvironmentSettings[$zype_environment]['estWidgetHost'];
                $new_options['zype_environment'] = $zype_environment;
                $new_options['playerHost'] = $this->zypeEnvironmentSettings[$zype_environment]['playerHost'];
            }

            $this->options = array_replace($this->options, $new_options);
            $this->update_options();

            zype_wp_admin_message('updated', 'Changes successfully saved!');
        } else {
            zype_wp_admin_message('error', 'Something has gone wrong.');
        }
        $this->check_keys();

        wp_redirect($_SERVER['HTTP_REFERER']);
        exit;
    }

    public function check_keys()
    {
        $invalid_keys = array();

        $wrapper = new \Zype\Core\Wrapper($this->options);//refresh options

        $playlists = \Zype\Core\Wrapper::get_playlists_by(array());
        if ($playlists === false)
            $invalid_keys[] = 'app_key';
        unset($playlists);

        $plans = \Zype\Core\Wrapper::get_all_plans();
        if ($plans === false)
            $invalid_keys[] = 'admin_key';
        unset($plans);

        $categories = \Zype\Core\Wrapper::get_all_categories();
        if ($categories === false)
            $invalid_keys[] = 'read_only_key';
        unset($categories);

        if (!$this->check_player_key())
            $invalid_keys[] = 'player_key';


        //embed_key
        //player_key

        if (!$this->check_stripe_pk())
            $invalid_keys[] = 'stripe_pk';

        // var_dump($this->options,$invalid_keys);exit;
        if (!empty($invalid_keys)) {
            $this->update_option('invalid_key', $invalid_keys);
        } else {
            $this->update_option('invalid_key', false);
        }
    }

    private function check_player_key()
    {
        $key = 'api_key=' . $this->options['player_key'];
        $video_id = 0;//not exists
        $url = $this->options['playerHost'] . '/embed/' . $video_id . '?' . $key . '&';

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = json_decode(curl_exec($ch));//expecting invalid video msg
        if ($response->message == 'Invalid or missing authentication.')
            return false;

        return true;

    }

    private function check_stripe_pk()
    {
        $publishableKey = $this->options['stripe_pk'];
        if (empty($publishableKey)) {
            return true;
        }
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/tokens");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $time = mktime(0, 0, 0, date('m') + 1, 1, date('Y'));
        $body = http_build_query(array(
            'card' => array(
                'number' => 4242424242424242,
                'exp_month' => date('m', $time),
                'exp_year' => date('Y', $time),
                'cvc' => 123
            )
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $publishableKey . ":");

        $response = json_decode(curl_exec($ch), true);//expecting invalid card msg

        curl_close($ch);
        if (isset($response["error"]) && substr($response["error"]["message"], 0, 24) == "Invalid API Key provided") {
            return false;
        }
        return true;
    }

    private function update_option($option, $value)
    {
        $this->options[$option] = $value;
        $this->update_options();
    }

    public function admin_video_search_page()
    {
        $search = $this->request->validate('search', ['textfield']);

        $query['active'] = true;
        $videos = \Zype::get_videos(null, 500);

        if ($search) {
            foreach ($videos as $k => $item) {
                if (!preg_match("/{$search}/i", $item->title)) {
                    unset($videos[$k]);
                }
            }
        }

        echo view('admin.video_search', [
            'videos' => $videos
        ]);

        wp_die();
    }

    public function admin_playlist_search_page()
    {
        $search = $this->request->validate('search', ['textfield']);

        $query['active'] = true;
        $playlists = \Zype::get_playlists_by($query, 1, 500, 'priority', 'asc');

        if ($search) {
            foreach ($playlists as $k => $item) {
                if (!preg_match("/{$search}/i", $item->title)) {
                    unset($playlists[$k]);
                }
            }
        }

        echo view('admin.playlist_search', [
            'playlists' => $playlists
        ]);

        wp_die();
    }

    public function admin_categories_page()
    {
        $categories = \Zype::get_all_categories();
        $zm = (new \ZypeMedia\Models\zObject('rss feed settings'));
        $zm->all(['per_page' => 500]);
        $feed_settings = $zm->collection ? $zm->collection : array();
        $available_feeds = [];
        foreach ($feed_settings as $feed_setting) {
            if ($feed_setting->category_name != '') {
                if (array_key_exists($feed_setting->category_name, $available_feeds)) {
                    array_push($available_feeds[$feed_setting->category_name], $feed_setting->category_value);
                } else {
                    $available_feeds[$feed_setting->category_name] = [$feed_setting->category_value];
                }
            }
        }

        echo view('admin.categories', [
            'options' => Config::get('zype'),
            'categories' => $categories,
            'feed_settings' => $feed_settings,
            'available_feeds' => $available_feeds,
        ]);

        wp_die();
    }

    public function admin_categories_page_save()
    {
        if (wp_verify_nonce($this->request->validate('_wpnonce'), 'zype_categories')) {
            $new_options = [
                'categories' => is_array($this->request->validate('categories')) ? $this->request->validate('categories') : [],
                'flush' => true,
            ];
            $this->options = array_replace($this->options, $new_options);
            $this->update_options();
            zype_wp_admin_message('updated', 'Changes successfully saved!');
        } else {
            zype_wp_admin_message('error', 'Something has gone wrong.');
        }
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit;
    }

    public function admin_zobjects_page()
    {
        $zobjects = \Zype::get_all_zobject_types();

        echo view('admin.zobjects', [
            'options' => $this->options,
            'zobjects' => $zobjects
        ]);

        wp_die();
    }

    public function admin_zobjects_page_save()
    {
        if (wp_verify_nonce($this->request->validate('_wpnonce'), 'zype_zobjects')) {
            $new_options = [
                'zobjects' => is_array($this->request->validate('zobjects')) ? $this->request->validate('zobjects') : [],
                'flush' => true,
            ];
            $this->options = array_replace($this->options, $new_options);
            $this->update_options();
            zype_wp_admin_message('updated', 'Changes successfully saved!');
        } else {
            zype_wp_admin_message('error', 'Something has gone wrong.');
        }
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit;
    }

    public function admin_livestream_page()
    {
        echo view('admin.livestream', [
            'options' => $this->options
        ]);

        wp_die();
    }

    public function admin_livestream_page_save()
    {
        if (wp_verify_nonce($this->request->validate('_wpnonce'), 'zype_livestream')) {
            $new_options = [
                'livestream_enabled' => $this->request->validate('livestream_enabled', ['bool']),
                'livestream_url' => $this->request->validate('livestream_url', ['url:http, https']) ?: $this->options['livestream_url'],
                'livestream_authentication_required' => $this->request->validate('livestream_authentication_required', ['bool']),
                'flush' => true,
            ];
            $this->options = array_replace($this->options, $new_options);
            $this->update_options();
            zype_wp_admin_message('updated', 'Changes successfully saved!');
        } else {
            zype_wp_admin_message('error', 'Something has gone wrong.');
        }
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit;
    }

    public function admin_braintree_page()
    {
        echo view('admin.braintree', [
            'options' => $this->options
        ]);

        wp_die();
    }

    public function admin_braintree_page_save()
    {
        if ($this->request->validate('subscribe')) {

            $new_options = ['subscribe_select' => $this->request->validate('subscribe')];

            $this->options = array_replace($this->options, $new_options);
            $this->update_options();
            zype_wp_admin_message('updated', 'Changes successfully saved!');
        }

        if (wp_verify_nonce($this->request->validate('_wpnonce'), 'zype_braintree')) {
            $new_options = [
                'braintree_environment' => $this->request->validate('braintree_environment', ['textfield'], $this->options['braintree_environment']),
                'braintree_merchant_id' => $this->request->validate('braintree_merchant_id', ['textfield'], $this->options['braintree_merchant_id']),
                'braintree_private_key' => $this->request->validate('braintree_private_key', ['textfield'], $this->options['braintree_private_key']),
                'braintree_public_key' => $this->request->validate('braintree_public_key', ['textfield'], $this->options['braintree_public_key']),
                'stripe_pk' => $this->request->validate('stripe_pk', ['textfield'], $this->options['stripe_pk']),

            ];
            $this->options = array_replace($this->options, $new_options);
            $this->update_options();
            zype_wp_admin_message('updated', 'Changes successfully saved!');
        } else {
            zype_wp_admin_message('error', 'Something has gone wrong.');
        }
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit;
    }

    public function admin_clear_live_cache_page_save()
    {
        if (wp_verify_nonce($this->request->validate('_wpnonce'), 'zype_clear_live_cache')) {
            $path = plugin_dir_path(__FILE__) . '../cache/';
            $files = glob($path . 'is_on_air*.json');

            if ($files) {
                if (unlink($files[0])) {
                    zype_wp_admin_message('updated', 'Cache deleted!');
                } else {
                    zype_wp_admin_message('error', 'Cache could not be deleted.');
                }
            } else {
                zype_wp_admin_message('error', 'There is no cache.');
            }
        } else {
            zype_wp_admin_message('error', 'Something has gone wrong.');
        }
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit;
    }

    public function admin_users_page()
    {
        echo view('admin.users', [
            'options' => $this->options
        ]);

        wp_die();
    }

    public function admin_users_page_save()
    {
        if (wp_verify_nonce($this->request->validate('_wpnonce'), 'zype_users')) {
            $new_options = [
                'authentication_enabled' => $this->request->validate('authentication_enabled', ['bool']),
                'subscriptions_enabled' => $this->request->validate('subscriptions_enabled', ['bool']),
                'device_link_enabled' => $this->request->validate('device_link_enabled', ['bool']),
                'auth_url' => $this->request->validate('auth_url', ['textfield'], $this->options['auth_url']),
                'logout_url' => $this->request->validate('logout_url', ['textfield'], $this->options['logout_url']),
                'profile_url' => $this->request->validate('profile_url', ['textfield'], $this->options['profile_url']),
                'device_link_url' => $this->request->validate('device_link_url', ['textfield'], $this->options['device_link_url']),
                'subscribe_url' => $this->request->validate('subscribe_url', ['textfield'], $this->options['subscribe_url']),
                'rental_url' => $this->request->validate('rental_url', ['textfield'], $this->options['rental_url']),
                'pass_url' => $this->request->validate('pass_url', ['textfield'], $this->options['pass_url']),
                'terms_url' => $this->request->validate('terms_url', ['textfield']),
                'flush' => true,
            ];
            if ($new_options['authentication_enabled'] == true && $this->options['cookie_key'] == '') {
                $new_options['cookie_key'] = \ZypeMedia\Services\Auth::generate_cookie_key();
            }
            $this->options = array_replace($this->options, $new_options);
            $this->update_options();
            zype_wp_admin_message('updated', 'Changes successfully saved!');
        } else {
            zype_wp_admin_message('error', 'Something has gone wrong.');
        }
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit;
    }

    public function admin_cookie_key_page_save()
    {
        if (wp_verify_nonce($this->request->validate('_wpnonce'), 'zype_cookie_key')) {
            $new_options['cookie_key'] = \ZypeMedia\Services\Auth::generate_cookie_key();
            $this->options = array_replace($this->options, $new_options);
            $this->update_options();
            zype_wp_admin_message('updated', 'Changes successfully saved!');
        } else {
            zype_wp_admin_message('error', 'Something has gone wrong.');
        }
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit;
    }

    public function admin_general_save()
    {
        if (wp_verify_nonce($this->request->validate('_wpnonce'), 'zype_general')) {
            $new_options = [
                'cache_time' => $this->request->validate('cache_time', ['num'], $this->options['cache_time']),
            ];
            $this->options = array_replace($this->options, $new_options);
            $this->update_options();
            zype_wp_admin_message('updated', 'Changes successfully saved!');
        } else {
            zype_wp_admin_message('error', 'Something has gone wrong.');
        }
        wp_redirect($_SERVER['HTTP_REFERER']);
        exit;
    }

    public function admin_api_explorer_page()
    {
        echo view('admin.api_explorer', [
            'options' => $this->options
        ]);

        wp_die();
    }

    public function admin_general_page()
    {
        echo view('admin.general', [
            'options' => $this->options
        ]);

        wp_die();
    }
}

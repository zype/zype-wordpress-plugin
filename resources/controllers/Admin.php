<?php

namespace ZypeMedia\Controllers;

use ZypeMedia\Models\V2\Plan;

class Admin extends Controller
{

    public function __construct()
    {
        parent::__construct();
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
                'braintree_environment' => strtolower($this->request->validate('braintree_environment', ['textfield'])),
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

        wp_redirect($this->request->validateServer('HTTP_REFERER', ['textfield']));
        exit;
    }

    public function check_keys()
    {
        $invalid_keys = array();

        $wrapper = new \Zype\Core\Wrapper($this->options);//refresh options

        $playlists = \Zype\Core\Wrapper::get_playlists_by([]);
        if (!$playlists->success())
            $invalid_keys[] = 'app_key';
        unset($playlists);

        $plans = Plan::all([], false);
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

        $response = wp_remote_request($url, [
            'method' => 'GET',
            'sslverify' => false,
            'timeout' => '120',
        ]);

        $body = wp_remote_retrieve_body($response);
        $code = wp_remote_retrieve_response_code($response);
        $response = new \Response($body, $code);

        if ($response->data->message == 'Invalid or missing authentication.')
            return false;

        return true;

    }

    private function check_stripe_pk()
    {
        $publishable_key = $this->options['stripe_pk'];
        if (empty($publishable_key)) {
            return true;
        }

        $time = mktime(0, 0, 0, date('m') + 1, 1, date('Y'));
        $body = http_build_query(array(
            'card' => array(
                'number' => 4242424242424242,
                'exp_month' => date('m', $time),
                'exp_year' => date('Y', $time),
                'cvc' => 123
            )
        ));

        $request = wp_remote_request('https://api.stripe.com/v1/tokens', [
            'headers'   => "Authorization: Basic " . base64_encode($publishable_key . ":"),
            'method'    => 'POST',
            'body'      => $body,
            'sslverify' => false,
            'timeout'   => '120',
        ]);

        $body = wp_remote_retrieve_body($request);
        $code = wp_remote_retrieve_response_code($request);
        $response = new \Response($body, $code);

        if (isset($response->data->error) && strpos($response->data->error->message, 'Invalid API Key provided') !== false) {
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
        $videos = \Zype::get_videos(['per_page' => 500])->response;

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
        $playlists = \Zype::get_playlists_by($query, 1, 500, 'priority', 'asc')->response;

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

    public function admin_braintree_page()
    {
        $plans = Plan::all([], false);
        $pass_plans = \Zype::get_all_pass_plans();

        echo view('admin.braintree', [
            'options' => $this->options,
            'plans' => $plans,
            'pass_plans' => $pass_plans,
        ]);

        wp_die();
    }

    public function admin_braintree_page_save()
    {
        // Set Subscriptions plans
        $subscription_plans = $this->request->validate('subscribe') ?: [];
        $new_options = ['subscribe_select' => $subscription_plans];

        $this->options = array_replace($this->options, $new_options);
        zype_wp_admin_message('updated', 'Subscription Plans successfully saved!');

        // Set Pass plans
        $pass_plans = $this->request->validate('pass_plans') ?: [];
        $new_options = ['pass_plans_select' => $pass_plans];

        $this->options = array_replace($this->options, $new_options);
        $this->update_options();
        zype_wp_admin_message('updated', 'Pass Plans successfully saved!');

        if (wp_verify_nonce($this->request->validate('_wpnonce'), 'zype_braintree')) {
            $new_options   = [
                'braintree_environment'         => $this->request->validate('braintree_environment', ['textfield'], $this->options['braintree_environment']),
                'braintree_merchant_id'         => $this->request->validate('braintree_merchant_id', ['textfield'], $this->options['braintree_merchant_id']),
                'braintree_private_key'         => $this->request->validate('braintree_private_key', ['textfield'], $this->options['braintree_private_key']),
                'braintree_public_key'          => $this->request->validate('braintree_public_key', ['textfield'], $this->options['braintree_public_key']),
                'stripe_pk'                     => $this->request->validate('stripe_pk', ['textfield'], $this->options['stripe_pk']),
                'sub_short_code_btn_text'       => $this->request->validate('sub_short_code_btn_text', ['textfield'], $this->options['sub_short_code_btn_text']),
                'sub_short_code_redirect_url'   => $this->request->validate('sub_short_code_redirect_url', ['textfield'], $this->options['sub_short_code_redirect_url']),
                'sub_short_code_text_after_sub' => $this->request->validate('sub_short_code_text_after_sub', ['textfield'], $this->options['sub_short_code_text_after_sub']),
                'my_library'                    => [
                    'sort'          => $this->request->validate('my_library_sort', ['textfield'], $this->options['my_library']['sort']),
                    'pagination'    => $this->request->validate('my_library_pagination', ['bool']),
                    'sign_in_text'  => $this->request->validate('my_library_sign_in_text', ['textfield'], $this->options['my_library']['sign_in_text'])
                ],
                'stripe'                    => [
                    'coupon_enabled' => $this->request->validate('stripe_coupon_enabled', ['textfield'], $this->options['stripe']['coupon_enabled']),
                ]
            ];
            $this->options = array_replace($this->options, $new_options);
            $this->update_options();
            zype_wp_admin_message('updated', 'Changes successfully saved!');
        } else {
            zype_wp_admin_message('error', 'Something has gone wrong.');
        }
        wp_redirect($this->request->validateServer('HTTP_REFERER', ['textfield']));
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
        wp_redirect($this->request->validateServer('HTTP_REFERER', ['textfield']));
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
                'purchase_url' => $this->request->validate('purchase_url', ['textfield'], $this->options['purchase_url']),
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
        wp_redirect($this->request->validateServer('HTTP_REFERER', ['textfield']));
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
        wp_redirect($this->request->validateServer('HTTP_REFERER', ['textfield']));
        exit;
    }

    public function admin_api_explorer_page()
    {
        echo view('admin.api_explorer', [
            'options' => $this->options
        ]);

        wp_die();
    }
}

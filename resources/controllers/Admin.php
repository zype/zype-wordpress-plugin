<?php

namespace ZypeMedia\Controllers;

use Themosis\Route\BaseController;
use Themosis\Facades\View;
use Themosis\Facades\Config;

class Admin extends BaseController {
    public $options = [];
    public static $defaults;

    public function __construct() {
        $this->options = Config::get('zype');
        self::$defaults = Config::get('zype');
    }

    public function admin_videos_page()
    {
        $this->flush_check();

        $allow_rss_feed = false;
        $zm             = (new \ZypeMedia\Models\zObject('rss feed settings'));
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
        if (wp_verify_nonce($_POST['_wpnonce'], 'zype_videos')) {
            $new_options   = [
                'rss_enabled'         => isset($_POST['rss_enabled']) ? true : false,
                'rss_url'             => empty($_POST['rss_url']) ? self::$defaults['rss_url'] : $_POST['rss_url'],
                'audio_only_enabled'  => isset($_POST['audio_only_enabled']) ? true : false,
                'excluded_categories' => is_array($_POST['excluded_categories']) ? $_POST['excluded_categories'] : [],

                'flush'               => true,
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

	public function admin_grid_screen_page()
	{
        echo view('admin.grid_screen', [
            'options' => $this->options
        ]);

        wp_die();
	}
	
	public function admin_grid_screen_page_save()
	{
		if (wp_verify_nonce($_POST['_wpnonce'], 'zype_grid_screen')) {
			$new_options   = [
				'grid_screen_parent' 	=> isset($_POST['grid_screen_parent']) ? $_POST['grid_screen_parent'] : '',
			];
			$this->options = array_replace($this->options, $new_options);
			$this->update_options();
			zype_wp_admin_message('updated', 'Changes successfully saved!');
		}
		else {
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
        if (wp_verify_nonce($_POST['_wpnonce'], 'zype_api_keys')) {

            $new_options = [
				'app_key'		   => isset($_POST['app_key']) ? trim($_POST['app_key']) : '',
                'admin_key'        => isset($_POST['admin_key']) ? trim($_POST['admin_key']) : '',
                'embed_key'        => isset($_POST['embed_key']) ? trim($_POST['embed_key']) : '',
                'player_key'       => isset($_POST['player_key']) ? trim($_POST['player_key']) : '',
                'read_only_key'    => isset($_POST['read_only_key']) ? trim($_POST['read_only_key']) : '',
				
                'braintree_environment'    => isset($_POST['braintree_environment']) ? trim($_POST['braintree_environment']) : '',
                'braintree_merchant_id'    => isset($_POST['braintree_merchant_id']) ? trim($_POST['braintree_merchant_id']) : '',
                'braintree_private_key'    => isset($_POST['braintree_private_key']) ? trim($_POST['braintree_private_key']) : '',
                'braintree_public_key'    => isset($_POST['braintree_public_key']) ? trim($_POST['braintree_public_key']) : '',
                'stripe_pk'    => isset($_POST['stripe_pk']) ? trim($_POST['stripe_pk']) : '',
    
                'oauth_client_id'    => isset($_POST['oauth_client_id']) ? trim($_POST['oauth_client_id']) : '',
                'oauth_client_secret'    => isset($_POST['oauth_client_secret']) ? trim($_POST['oauth_client_secret']) : '',            
            ];

            if (isset($_POST['zype_environment']) && isset($this->zypeEnvironmentSettings[$_POST['zype_environment']])) {
                $new_options['endpoint']         = $this->zypeEnvironmentSettings[$_POST['zype_environment']]['endpoint'];
                $new_options['authpoint']        = $this->zypeEnvironmentSettings[$_POST['zype_environment']]['authpoint'];
                $new_options['estWidgetHost']    = $this->zypeEnvironmentSettings[$_POST['zype_environment']]['estWidgetHost'];
                $new_options['zype_environment'] = $_POST['zype_environment'];
                $new_options['playerHost']       = $this->zypeEnvironmentSettings[$_POST['zype_environment']]['playerHost'];
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

    public function admin_video_search_page() {
        $search = !empty($_POST['search'])? trim($_POST['search']): '';

        $query['active'] = true;
        $videos = \Zype::get_videos(null, 500);

        if (isset($_POST['search_submit'])) {
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

    public function admin_playlist_search_page(){
        $search = !empty($_POST['search'])? trim($_POST['search']): '';

        $query['active'] = true;
        $playlists = \Zype::get_playlists_by($query, 1, 500, 'priority', 'asc');

        if (isset($_POST['search_submit'])) {
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
        $this->flush_check();
        $categories = \Zype::get_all_categories();
        $zm = (new \ZypeMedia\Models\zObject('rss feed settings'));
        $zm->all(['per_page' => 500]);
        $feed_settings   = $zm->collection ? $zm->collection : array();
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
        if (wp_verify_nonce($_POST['_wpnonce'], 'zype_categories')) {
            $new_options   = [
                'categories' => is_array($_POST['categories']) ? $_POST['categories'] : [],
                'flush'      => true,
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
        $this->flush_check();
        $zobjects = \Zype::get_all_zobject_types();

        echo view('admin.zobjects', [
            'options' => $this->options,
            'zobjects' => $zobjects
        ]);

        wp_die();
    }

    public function admin_zobjects_page_save()
    {
        if (wp_verify_nonce($_POST['_wpnonce'], 'zype_zobjects')) {
            $new_options   = [
                'zobjects' => is_array($_POST['zobjects']) ? $_POST['zobjects'] : [],
                'flush'    => true,
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
        $this->flush_check();
        echo view('admin.livestream', [
            'options' => $this->options
        ]);

        wp_die();
    }

    public function admin_livestream_page_save()
    {
        if (wp_verify_nonce($_POST['_wpnonce'], 'zype_livestream')) {
            $new_options   = [
                'livestream_enabled'                 => isset($_POST['livestream_enabled']) ? true : false,
                'livestream_url'                     => empty($_POST['livestream_url']) ? self::$defaults['livestream_url'] : $_POST['livestream_url'],
                'livestream_authentication_required' => isset($_POST['livestream_authentication_required']) ? true : false,
                'flush'                              => true,
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
        $this->flush_check();
        echo view('admin.braintree', [
            'options' => $this->options
        ]);

        wp_die();
    }

    public function admin_braintree_page_save()
    {
		if(isset($_POST['subscribe'])){
			
			$new_options = ['subscribe_select' => $_POST['subscribe']];
			
			$this->options = array_replace($this->options, $new_options);
            $this->update_options();
            zype_wp_admin_message('updated', 'Changes successfully saved!');
		}
		
        if (wp_verify_nonce($_POST['_wpnonce'], 'zype_braintree')) {
            $new_options   = [
                'braintree_environment' => empty($_POST['braintree_environment']) ? self::$defaults['braintree_environment'] : $_POST['braintree_environment'],
                'braintree_merchant_id' => empty($_POST['braintree_merchant_id']) ? self::$defaults['braintree_merchant_id'] : $_POST['braintree_merchant_id'],
                'braintree_private_key' => empty($_POST['braintree_private_key']) ? self::$defaults['braintree_private_key'] : $_POST['braintree_private_key'],
                'braintree_public_key'  => empty($_POST['braintree_public_key']) ? self::$defaults['braintree_public_key'] : $_POST['braintree_public_key'],
                'stripe_pk'             => empty($_POST['stripe_pk']) ? self::$defaults['stripe_pk'] : $_POST['stripe_pk'],

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
        if (wp_verify_nonce($_POST['_wpnonce'], 'zype_clear_live_cache')) {
            $path  = plugin_dir_path(__FILE__) . '../cache/';
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
        $this->flush_check();
        echo view('admin.users', [
            'options' => $this->options
        ]);

        wp_die();
    }

    public function admin_users_page_save()
    {
        if (wp_verify_nonce($_POST['_wpnonce'], 'zype_users')) {
            $new_options = [
                'authentication_enabled' => isset($_POST['authentication_enabled']) ? true : false,
                'subscriptions_enabled'  => isset($_POST['subscriptions_enabled']) ? true : false,
                'device_link_enabled'    => isset($_POST['device_link_enabled']) ? true : false,
                'logout_url'             => empty($_POST['logout_url']) ? self::$defaults['logout_url'] : $_POST['logout_url'],
                'profile_url'            => empty($_POST['profile_url']) ? self::$defaults['profile_url'] : $_POST['profile_url'],
                'device_link_url'        => empty($_POST['device_link_url']) ? self::$defaults['device_link_url'] : $_POST['device_link_url'],
                'subscribe_url'          => empty($_POST['subscribe_url']) ? self::$defaults['subscribe_url'] : $_POST['subscribe_url'],
                'rental_url'             => empty($_POST['rental_url']) ? self::$defaults['rental_url'] : $_POST['rental_url'],
                'pass_url'               => empty($_POST['pass_url']) ? self::$defaults['pass_url'] : $_POST['pass_url'],
                'flush'                  => true,
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
        if (wp_verify_nonce($_POST['_wpnonce'], 'zype_cookie_key')) {
            $new_options['cookie_key'] = \ZypeMedia\Services\Auth::generate_cookie_key();
            $this->options             = array_replace($this->options, $new_options);
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
        if (wp_verify_nonce($_POST['_wpnonce'], 'zype_general')) {
            $new_options = [
                'cache_time' =>  empty($_POST['cache_time']) ? self::$defaults['cache_time'] : $_POST['cache_time'],
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

    private function flush_check()
    {
    }

    private function update_options()
    {
        update_option('zype_wp', $this->options);
        $this->options = get_option('zype_wp');
    }

    private function update_option($option, $value)
    {
        $this->options[$option] = $value;
        $this->update_options();
    }

    public function admin_general_page()
    {
        echo view('admin.general', [
            'options' => $this->options
        ]);

        wp_die();
    }
    private function check_stripe_pk(){
        $ch = curl_init();
        $publishableKey = $this->options['stripe_pk'];
        curl_setopt($ch, CURLOPT_URL, "https://api.stripe.com/v1/tokens");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $time = mktime(0,0,0,date('m') +1,1,date('Y'));
        $body = http_build_query(array(
            'card' => array(
                'number' => 4242424242424242,
                'exp_month' => date('m',$time),
                'exp_year' => date('Y',$time),
                'cvc' => 123
            )
        ));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $body);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_USERPWD, $publishableKey . ":");

        $response = json_decode(curl_exec($ch),true);//expecting invalid card msg
        
        curl_close ($ch);
        if(isset($response["error"]) && substr($response["error"]["message"],0, 24 ) == "Invalid API Key provided"){
            return false;
        }
        return true;
    }
    
    private function check_player_key(){
        $key = 'api_key=' . $this->options['player_key'];
        $video_id = 0;//not exists
        $url = $this->options['playerHost'] . '/embed/' . $video_id . '?' . $key . '&';
        
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

        $response = json_decode(curl_exec($ch));//expecting invalid video msg
        if($response->message == 'Invalid or missing authentication.')
            return false;
        
        return true;
        
    }
    public function check_keys(){
        $invalid_keys = array();
        
        $wrapper = new \Zype\Core\Wrapper($this->options);//refresh options
        
        $playlists = \Zype\Core\Wrapper::get_playlists_by(array());
        if($playlists === false)
            $invalid_keys[] = 'app_key';
        unset($playlists);
        
        $plans = \Zype\Core\Wrapper::get_all_plans();
        if($plans === false)
            $invalid_keys[] = 'admin_key';
        unset($plans);
        
        $categories = \Zype\Core\Wrapper::get_all_categories();
        if($categories === false)
            $invalid_keys[] = 'read_only_key';
        unset($categories);
        
        if(!$this->check_player_key())
            $invalid_keys[] = 'player_key';
       
        
        //embed_key
        //player_key
        
       /* if(!$this->check_stripe_pk())
            $invalid_keys[] = 'stripe_pk';
            
        try{
            \Braintree_CredentialsParser::assertValidEnvironment( $this->options['braintree_environment'] );
            \Braintree_Configuration::environment(  $this->options['braintree_environment']      );
            \Braintree_Configuration::merchantId(   $this->options['braintree_merchant_id']      );
            \Braintree_Configuration::publicKey(    $this->options['braintree_public_key']       );
            \Braintree_Configuration::privateKey(   $this->options['braintree_private_key']      );
            $token = \Braintree_ClientToken::generate();
        }catch(\Exception $e){
            switch(get_class($e))://no break is not an error
                case 'Braintree_Exception_Configuration':
                    $invalid_keys[] = 'braintree_environment';
                case 'Braintree_Exception_Authentication':
                    $invalid_keys[] = 'braintree_merchant_id';
                case 'Braintree_Exception_Authorization':
                    $invalid_keys[] = 'braintree_public_key';
                    $invalid_keys[] = 'braintree_private_key';
            endswitch;
        }*/
        // var_dump($this->options,$invalid_keys);exit;
        if(!empty($invalid_keys)){
            $this->update_option('invalid_key',$invalid_keys);
        }else{
            $this->update_option('invalid_key',false);
        }
    }
}
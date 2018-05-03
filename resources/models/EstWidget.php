<?php

namespace ZypeMedia\Models;

use ZypeMedia\Services\Auth;
use Themosis\Facades\Config;
use \Firebase\JWT\JWT;

class EstWidget {
    public $estWidgetHost   = 'http://zype-sub-stg-sigma.elasticbeanstalk.com';
    public $widgetScriptUrl = 'http://zype-sub-stg-sigma.elasticbeanstalk.com/javascripts/subscription_embed.js';
    public $tagId           = 'videoEmbed';
    public $siteId;
    public $video;
    public $options;
    public static $authDataKey = '178e70caccab239b52c61315fda10424';

    public function __construct($video = null)
    {
        $this->options = get_option(ZYPE_WP_OPTIONS);

        $this->estWidgetHost   = $this->options['estWidgetHost'];
        $this->widgetScriptUrl = $this->options['estWidgetHost'] . '/javascripts/subscription_embed.js';

        $this->siteId = $this->options['embed_key'];
        $this->video  = $video;
    }

    public function embed($params = [])
    {
        add_action('wp_enqueue_scripts', function() {
            wp_enqueue_script('zype-est-widget', $this->widgetScriptUrl);
        }, 90);

        $this->do_embed();
    }

    public function do_embed()
    {
        $find = [
            'zype/partial/player_embed.php',
        ];
        $template = locate_template($find);
        if (!$template && file_exists(plugin_dir_path(__FILE__) . '../views/partial/est_widget.php')) {
            $template = plugin_dir_path(__FILE__) . '../views/partial/est_widget.php';
        }

        ob_start();
        include($template);
        echo ob_get_clean();
    }

    public function generateAuthData()
    {
        $authData = '';
        $cookie = ((new Auth())->get_cookie());
        if ($cookie && isset($cookie['access_token']) && isset($cookie['refresh_token'])) {
            $authData = $cookie['access_token'] . '|' . $cookie['refresh_token'];
            $authData = self::encrypt($authData);
        }
        return $authData;
    }

    public static function encrypt($text) {
        return JWT::encode(utf8_encode($text), Config::get('zype.cookie_key'));
    }

    public static function decrypt($text) {
        return JWT::decode($text, Config::get('zype.cookie_key'), array('HS256'));
    }

    private static function pkcs5_pad($text, $blocksize) {
        $pad = $blocksize - (strlen($text) % $blocksize);
        return $text . str_repeat(chr($pad), $pad);
    }
}

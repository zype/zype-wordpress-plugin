<?php

namespace ZypeMedia\Providers;

use Themosis\Foundation\ServiceProvider;
use Themosis\Facades\Config;

class ZypeService extends ServiceProvider
{
    /**
     * Register plugin library.
     * Define a custom namespace.
     */
    public function register()
    {
        if (file_exists(themosis_path('plugin.zypemedia.resources') . 'lib/Zype/Wrapper.php')) {
            require_once(themosis_path('plugin.zypemedia.resources') . 'lib/Zype/Wrapper.php');
        }

        if (!class_exists('\Zype\Core\Wrapper')) {
            throw new \Exception('Not found library Zype');
        }

        require_once(themosis_path('plugin.zypemedia.resources') . 'lib/Money.php');

        require_once(themosis_path('plugin.zypemedia.resources') . 'lib/ApiOperations/Create.php');
        require_once(themosis_path('plugin.zypemedia.resources') . 'lib/ApiOperations/Retrieve.php');
        require_once(themosis_path('plugin.zypemedia.resources') . 'lib/ApiOperations/Update.php');
        require_once(themosis_path('plugin.zypemedia.resources') . 'lib/ApiOperations/All.php');

        require_once(themosis_path('plugin.zypemedia.resources') . 'lib/Zype/Api/Account.php');
        require_once(themosis_path('plugin.zypemedia.resources') . 'lib/Zype/Api/Playlist.php');
        require_once(themosis_path('plugin.zypemedia.resources') . 'lib/Zype/Api/Video.php');
        require_once(themosis_path('plugin.zypemedia.resources') . 'lib/Zype/Api/Consumer.php');
        class_alias('Zype\Core\Wrapper', 'Zype', true);

        new \Zype(Config::get('zype'));
    }
}

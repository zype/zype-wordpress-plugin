<?php

namespace ZypeMedia\Providers;

use Themosis\Foundation\ServiceProvider;

class ZypeService extends ServiceProvider
{
    /**
     * Register plugin library.
     * Define a custom namespace.
     */
    public function register()
    {
        global $zype_wp_options;

        if (file_exists(themosis_path('plugin.zypemedia.resources') . 'lib/Zype/Wrapper.php')) {
            require_once(themosis_path('plugin.zypemedia.resources') . 'lib/Zype/Wrapper.php');
        }

        if (!class_exists('\Zype\Core\Wrapper')) {
            throw new \Exception('Not found library Zype');
        }

        class_alias('Zype\Core\Wrapper', 'Zype', true);

        new \Zype($zype_wp_options);
    }
}

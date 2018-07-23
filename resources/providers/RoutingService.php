<?php

namespace ZypeMedia\Providers;

use Themosis\Facades\Route;
use Themosis\Foundation\ServiceProvider;

class RoutingService extends ServiceProvider
{
    /**
     * Register plugin routes.
     * Define a custom namespace.
     */
    public function register()
    {
        Route::group([
            'namespace' => 'ZypeMedia\Controllers'
        ], function () {
            require themosis_path('plugin.zypemedia.resources') . 'routes.php';
            require themosis_path('plugin.zypemedia.resources') . 'shortcodes.php';
        });
    }
}

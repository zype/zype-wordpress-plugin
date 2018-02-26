<?php

use Themosis\Facades\Route;
use Themosis\Facades\Config;

// Prepare urls
$rss_url = Config::get('zype.rss_url');
$profile_url = Config::get('zype.profile_url');
$subscribe_url = Config::get('zype.subscribe_url');
$device_link_url = Config::get('zype.device_link_url');

/**
 * Plugin custom routes.
 */

// Subscribe route
if ($subscribe_url && Config::get('zype.subscriptions_enabled')) {
    Route::any("{$subscribe_url}", 'Consumer\Subscriptions@plans');
    Route::any("{$subscribe_url}/checkout", 'Consumer\Subscriptions@checkout');
    Route::any("{$subscribe_url}/submit", 'Consumer\Subscriptions@checkoutSuccess');
}

// Rss route
if ($rss_url && Config::get('zype.rss_enabled')) {
    Route::any("{$rss_url}/{zype_rss_id}", 'RSS@show');
}

// Categories/rss routes
if (Config::get('zype.categories')) {
    foreach (Config::get('zype.categories') as $categories) {
        foreach ($categories as $subcats) {
            Route::any($subcats['url'], 'Consumer\Category@index');

            if ($rss_url && Config::get('zype.rss_enabled')) {
                Route::any("{$subcats['url']}/{$rss_url}/{zype_rss_id}", 'RSS@show_category');
            }
        }
    }
}

// zObjects routes
if (Config::get('zype.zobjects')) {
    foreach (Config::get('zype.zobjects') as $zobject) {
        Route::any($zobject, 'Consumer\zObjects@index');
    }
}

if (Config::get('zype.authentication_enabled')) {
    // Sign-out route
    if (Config::get('zype.logout_url')) {
        Route::any(Config::get('zype.logout_url'), function() {
            \Auth::logout();
            wp_redirect(home_url());
            exit;
        });
    }

    // Profile routes
    if ($profile_url) {
        Route::any("{$profile_url}/change-password", 'Consumer\Profile@change_password');
        Route::any("{$profile_url}/reset-password/{hash}", 'Consumer\Profile@reset_password');
        Route::any("{$profile_url}/reset-password/{hash}/submit", 'Consumer\Profile@reset_password_submit');
        Route::any("{$profile_url}/subscription", 'Consumer\Profile@subscription');
        Route::any("{$profile_url}/subscription/change", 'Consumer\Profile@change_subscription');
        Route::any("{$profile_url}/subscription/change-card", 'Consumer\Profile@change_card');
        Route::any("{$profile_url}/subscription/cancel", 'Consumer\Profile@cancel_subscription');
        Route::any("{$profile_url}/rss-feeds", 'Consumer\Profile@rss_feeds');
        Route::any("{$profile_url}", 'Consumer\Profile@profile');
    }

    if ($device_link_url && Config::get('zype.device_link_enabled')) {
        Route::any("{$device_link_url}", 'Consumer\Profile@device_link');
        Route::any("{$device_link_url}/submit", 'Consumer\Profile@device_link_submit');
    }
}
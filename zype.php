<?php
/**
 * Plugin Name: Zype
 * Plugin URI: https://www.zype.com/
 * Description: Using the Zype plugin, you can sell subscriptions for premium video content, track analytics for video engagement, insert playlists and videos using shortcodes, and even broadcast live events with just a few clicks.
 * Version: 2.5.4
 * Author: Zype
 * Author URI: http://zype.com/
 * Text Domain: plugin-textdomain.
 * Domain Path: /languages
 */

if (!class_exists('Themosis')) {
    require_once 'core.php';
}

/**
 * Use statements. Add any useful class import
 * below.
 */

use Composer\Autoload\ClassLoader;

// use Tracy\Debugger;

// Debugger::enable();

/*
 * Default constants.
 */
defined('DS') ? DS : define('DS', DIRECTORY_SEPARATOR);

/*
 * Always define the following constant to help you handle
 * your plugin text domain. Make sure to define the same value as
 * the one defined in the plugin header comments text domain.
 *
 * Please update (search and replace) all constants calls found in this file.
 *
 */
defined('ZYPE_MEDIA') ? ZYPE_MEDIA : define('ZYPE_MEDIA', 'plugin-textdomain');
define('ZYPE_WP_VERSION', '2.5.4');
define('ZYPE_PATH', __FILE__ . DS);
define('ZYPE_WP_OPTIONS', 'zype_wp');

/*
 * Plugin variables.
 */
$vars = [
    'slug' => 'zype-media',
    'name' => 'Zype Media',
    'namespace' => 'zypemedia',
    'config' => 'zypemedia',
];

$zype_default_options = array(
    'admin_key' => '',
    'player_key' => '',
    'read_only_key' => '',
    'livestream_enabled' => false,
    'zobjects' => array(),
    'categories' => array(),
    'audio_only_enabled' => false,
    'excluded_categories' => array(),
    'authentication_enabled' => true,
    'subscriptions_enabled' => true,
    'device_link_enabled' => true,
    'zype_saas_compatability' => false,
    'zype_saas_comfortability' => false,
    'cookie_key' => 'reset_me',
    'oauth_client_id' => '',
    'oauth_client_secret' => '',
    'flush' => true,
    'auth_url' => 'sign-in',
    'livestream_url' => 'livestream',
    'video_url' => 'videorewr',
    'logout_url' => 'sign-out',
    'profile_url' => 'profile',
    'device_link_url' => 'link',
    'transaction_url' => 'transaction',
    'subscribe_url' => 'subscribe',
    'rental_url' => 'rental',
    'pass_url' => 'pass',
    'purchase_url' => 'purchase',
    'terms_url' => '',
    'playlist_pagination' => true,
    'braintree_environment' => '',
    'braintree_merchant_id' => '',
    'braintree_private_key' => '',
    'braintree_public_key' => '',
    'rss_url' => 'rss',
    'rss_enabled' => false,
    'stripe_pk' => '',
    'stripe' => [
        'coupon_enabled' => true
    ],
    'livestream_authentication_required' => false,
    'cache_time' => 600,
    'app_key' => '',
    'embed_key' => '',
    'endpoint' => 'https://api.zype.com',
    'authpoint' => 'https://login.zype.com/oauth/token',
    'estWidgetHost' => 'https://play.zype.com',
    'zype_environment' => 'Production',
    'playerHost' => 'https://player.zype.com',
    'grid_screen_url' => 'grid',
    'grid_screen_parent' => '',
    'invalid_keys' => true,
    'zype_wp_version' => ZYPE_WP_VERSION,
    'sub_short_code_btn_text' => 'SIGN UP',
    'sub_short_code_redirect_url' => 'ddd',
    'sub_short_code_text_after_sub' => 'MY ACCOUNT',
    'my_library' => [
        'sort' => 'created_at',
        'pagination' => true,
        'sign_in_text' => 'Please sign in to view your video library'
    ],
    'my_library_sort_options' => [
        'created_at' => [
            'title' => 'Newest to oldest (default)',
            'order' => 'desc'
        ],
        'title' => [
            'title' => 'A to Z',
            'order' => 'asc'
        ]
    ],
    'emails' => [
        'cancel_subscription' => [
            'text' => "We're very sorry to see you go! This email confirms your subscription has been canceled.\nPlease come back to visit if you'd like to subscribe again in the future.\nThanks.",
            'required' => [],
            'enabled' => true
        ],
        'forgot_password' => [
            'text' => "We received a request to reset your password. Please use the following link to set a new password for your account.\n{forgot_password_link}\nIf you did not request a password reset please disregard this email. Thanks for watching!",
            'required' => ['{forgot_password_link}'],
            'enabled' => true
        ],
        'new_account' => [
            'text' => "You can log in at the following URL using the email address and password you provided during account creation:\n{login_link}\nThanks again!",
            'required' => ['{login_link}'],
            'enabled' => true
        ],
        'new_rental' => [
            'text' => "Thank you for your rental to {object_name}, we know you'll enjoy it!\nYou can log in with the email address and password you provided at the following URL: \n{login_link}\nThanks again",
            'required' => ['{object_name}', '{login_link}'],
            'enabled' => true
        ],
        'new_purchase' => [
            'text' => "Thank you for your purchase to {object_name}, we know you'll enjoy it!\nYou can log in with the email address and password you provided at the following URL: \n{login_link}\nThanks again",
            'required' => ['{object_name}', '{login_link}'],
            'enabled' => true
        ],
        'new_pass' => [
            'text' => "Thank you for buying a pass plan, we know you'll enjoy it!\nYou can log in with the email address and password you provided at the following URL: \n{login_link}\nThanks again",
            'required' => ['{login_link}'],
            'enabled' => true
        ],
        'new_subscription' => [
            'text' => "Thank you for subscribing, we hope you'll enjoy it!\nYou can log in with the email address and password you provided at the following URL: \n{login_link}\nThanks again!",
            'required' => ['{login_link}'],
            'enabled' => true
        ]
    ],
    'colors' => [
        'light-theme' => [
            'modal' => [
                'background' => '#FFFFFF',
                'title' => '#272424',
                'close-btn' => '#AAAAAA',
                'price-table' => [
                    'border' => '#D9D8E0',
                    'background' => '#FFFFFF',
                    'transaction' => [
                        'title' => '#272424',
                        'description' => '#60626B',
                        'price' => '#60626B'
                    ],
                    'button' => [
                        'border' => '#00A5DF',
                        'text' => '#00A5DF',
                        'background' => '#FFFFFF'
                    ]
                ]
            ],
            'playlist' => [
                'arrow' => '#C9CFD8',
                'name' => [
                    'normal' => '#45484C',
                    'hover' => '#6E7075'
                ],
                'video_name' => '#5B5E64',
                'see_all' => [
                    'normal' => '#9D9FA5',
                    'hover' => '#BFC3CB'
                ]
            ]
        ],
        'dark-theme' => [
            'modal' => [
                'background' => '#000000',
                'title' => '#D8DBDB',
                'close-btn' => '#555555',
                'price-table' => [
                    'border' => '#26271F',
                    'background' => '#000000',
                    'transaction' => [
                        'title' => '#D8DBDB',
                        'description' => '#9F9D94',
                        'price' => '#9F9D94'
                    ],
                    'button' => [
                        'border' => '#00A5DF',
                        'text' => '#00A5DF',
                        'background' => '#000000'
                    ]
                ]
            ],
            'playlist' => [
                'arrow' => '#363027',
                'name' => [
                    'normal' => '#BAB7B3',
                    'hover' => '#918F8A'
                ],
                'video_name' => '#A4A19B',
                'see_all' => [
                    'normal' => '#62605A',
                    'hover' => '#403C34'
                ]
            ]
        ],
        'user' => [
            'modal' => [
                'background' => '#FFFFFF',
                'title' => '#272424',
                'close-btn' => '#AAAAAA',
                'price-table' => [
                    'border' => '#D9D8E0',
                    'background' => '#FFFFFF',
                    'transaction' => [
                        'title' => '#272424',
                        'description' => '#60626B',
                        'price' => '#60626B'
                    ],
                    'button' => [
                        'border' => '#00A5DF',
                        'text' => '#00A5DF',
                        'background' => '#FFFFFF'
                    ]
                ]
            ],
            'playlist' => [
                'arrow' => '#C9CFD8',
                'name' => [
                    'normal' => '#45484C',
                    'hover' => '#6E7075'
                ],
                'video_name' => '#5B5E64',
                'see_all' => [
                    'normal' => '#9D9FA5',
                    'hover' => '#BFC3CB'
                ]
            ]
        ]
    ]
);

$zype_wp_options = get_option(ZYPE_WP_OPTIONS);
$zype_check_keys = true;

if (!$zype_wp_options) {
    update_option(ZYPE_WP_OPTIONS, $zype_default_options);
} elseif (!array_key_exists('zype_wp_version', $zype_wp_options) || $zype_wp_options['zype_wp_version'] != ZYPE_WP_VERSION) {
    foreach ($zype_default_options as $key => $value) {
        if (!array_key_exists($key, $zype_wp_options)) {
            $zype_wp_options[$key] = $value;
        }
    }

    $zype_wp_options['zype_wp_version'] = ZYPE_WP_VERSION;
    update_option(ZYPE_WP_OPTIONS, $zype_wp_options);
} else {
    $zype_check_keys = false;
}

define('ZYPE_CHECK_KEYS', $zype_check_keys);

if (ZYPE_CHECK_KEYS) {
    $rules_stub = new WP_Rewrite();
    foreach (array_keys($rules_stub->wp_rewrite_rules()) as $key) {
        if (strpos($key, '#sD') !== false) {
            update_option('db_upgraded', true);
            break;
        }
    }
}

/*
 * Verify that the main framework is loaded.
 */
add_action('admin_notices', function () use ($vars) {
    if (!class_exists('\Themosis\Foundation\Application')) {
        printf('<div class="notice notice-error"><p>%s</p></div>', __('This plugin requires the Themosis framework in order to work.', ZYPE_MEDIA));
    }
});

/*
 * Setup the plugin paths.
 */
$paths['plugin.' . $vars['namespace']] = __DIR__ . DS;
$paths['plugin.' . $vars['namespace'] . '.resources'] = __DIR__ . DS . 'resources' . DS;
$paths['plugin.' . $vars['namespace'] . '.admin'] = __DIR__ . DS . 'resources' . DS . 'admin' . DS;

themosis_set_paths($paths);

/*
 * Setup plugin config files.
 */
container('config.finder')->addPaths([
    themosis_path('plugin.' . $vars['namespace'] . '.resources') . 'config' . DS,
]);

/*
 * Autoloading.
 */
$loader = new ClassLoader();
$classes = container('config.factory')->get('loading');
foreach ($classes as $prefix => $path) {
    $loader->addPsr4($prefix, $path);
}
$loader->register();

/*
 * Theme aliases.
 */
$aliases = container('config.factory')->get('aliases');
if (!empty($aliases) && is_array($aliases)) {
    foreach ($aliases as $alias => $fullname) {
        class_alias($fullname, $alias);
    }
}

/*
 * Register plugin public assets folder [dist directory].
 */
container('asset.finder')->addPaths([
    plugins_url('dist', __FILE__) => themosis_path('plugin.' . $vars['namespace']) . 'dist',
]);

/*
 * Register plugin views.
 */
container('view.finder')->addLocation(themosis_path('plugin.' . $vars['namespace'] . '.resources') . 'views');

/*
 * Service providers.
 */
$providers = container('config.factory')->get('providers');
foreach ($providers as $provider) {
    container()->register($provider);
}

/*
 * Admin files autoloading.
 * I18n.
 */
container('action')->add('plugins_loaded', function () use ($vars) {

    /**
     * I18n
     * Todo: #2 - Replace constant below.
     */
    load_plugin_textdomain(ZYPE_MEDIA, false, trailingslashit(dirname(plugin_basename(__FILE__))) . 'languages');

    /*
     * Plugin admin files.
     * Autoload files in alphabetical order.
     */
    $loader = container('loader')->add([
        themosis_path('plugin.' . $vars['namespace'] . '.admin'),
    ]);

    $loader->load();

});

/*
 * Add extra features below.
 */
// Start session
if (!session_id()) {
    session_start();
}

if (ZYPE_CHECK_KEYS) {
    $controller = new \ZypeMedia\Controllers\Admin();
    $controller->check_keys();
}

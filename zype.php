<?php

if (!class_exists('Themosis')) {
  require_once 'core.php';
}

/**
 * Plugin Name: Zype
 * Plugin URI: https://www.zype.com/
 * Description: Using the Zype plugin, you can sell subscriptions for premium video content, track analytics for video engagement, insert playlists and videos using shortcodes, and even broadcast live events with just a few clicks.
 * Version: 0.9.5
 * Author: Zype
 * Author URI: http://zype.com/
 * Text Domain: plugin-textdomain.
 * Domain Path: /languages
 */

/**
 * Use statements. Add any useful class import
 * below.
 */
use Composer\Autoload\ClassLoader;

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
define('ZYPE_WP_VERSION', '0.9.5');
define('ZYPE_PATH', __FILE__ . DS);
define('ZYPE_WP_OPTIONS', 'zype_wp');

/*
 * Plugin variables.
 * Configure your plugin.
 *
 * TODO: #3 - Define your own values.
 * TODO: #4 - Update the config files names located into the `resources/config` folder.
 * TODO: #5 - Update class namespaces.
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
    'zobjects' => array (),
    'categories' => array (),
    'audio_only_enabled' => false,
    'excluded_categories' => array (),
    'authentication_enabled' => true,
    'subscriptions_enabled' => true,
    'device_link_enabled' => true,
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
    'subscribe_url' => 'subscribe',
    'rental_url' => 'rental',
    'pass_url' => 'pass',
    'terms_url' => '',
    'braintree_environment' => '',
    'braintree_merchant_id' => '',
    'braintree_private_key' => '',
    'braintree_public_key' => '',
    'rss_url' => 'rss',
    'rss_enabled' => false,
    'stripe_pk' => '',
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
    'zype_wp_version' => ZYPE_WP_VERSION
);

global $zype_wp_options;
$zype_wp_options = get_option(ZYPE_WP_OPTIONS);

if (!$zype_wp_options) {
  update_option(ZYPE_WP_OPTIONS, $zype_default_options);
  define('ZYPE_CHECK_KEYS', true);
}
elseif (!array_key_exists('zype_wp_version', $zype_wp_options) || $zype_wp_options['zype_wp_version'] != ZYPE_WP_VERSION) {
    foreach ($zype_default_options as $key => $value) {
        if (!array_key_exists($key, $zype_wp_options)) {
            $zype_wp_options[$key] = $value;
        }
    }
    update_option(ZYPE_WP_OPTIONS, $zype_default_options);
    define('ZYPE_CHECK_KEYS', true);
}
else {
    define('ZYPE_CHECK_KEYS', false);
}

if(ZYPE_CHECK_KEYS) {
    $rules_stub = new WP_Rewrite();
    foreach (array_keys($rules_stub->wp_rewrite_rules()) as $key) {
        if(strpos($key, '#sD') !== false) {
            update_option('db_upgraded',  true);
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

    /*
     * Define your plugin theme support key. Once defined, make sure to add the key
     * into your theme `supports.config.php` in order to remove this admin notice.
     */
    // if (!current_theme_supports($vars['slug']) && current_user_can('switch_themes')) {
    //     printf('<div class="notice notice-warning"><p>%s<strong>%s</strong></p></div>', __('Your application does not handle the following plugin: ', ZYPE_MEDIA), $vars['name']);
    // }
});

/*
 * Setup the plugin paths.
 */
$paths['plugin.'.$vars['namespace']] = __DIR__.DS;
$paths['plugin.'.$vars['namespace'].'.resources'] = __DIR__.DS.'resources'.DS;
$paths['plugin.'.$vars['namespace'].'.admin'] = __DIR__.DS.'resources'.DS.'admin'.DS;

themosis_set_paths($paths);

/*
 * Setup plugin config files.
 */
container('config.finder')->addPaths([
    themosis_path('plugin.'.$vars['namespace'].'.resources').'config'.DS,
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
    plugins_url('dist', __FILE__) => themosis_path('plugin.'.$vars['namespace']).'dist',
]);

/*
 * Register plugin views.
 */
container('view.finder')->addLocation(themosis_path('plugin.'.$vars['namespace'].'.resources').'views');

/*
 * Update Twig Loader registered paths.
 */
container('twig.loader')->setPaths(container('view.finder')->getPaths());

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
  load_plugin_textdomain(ZYPE_MEDIA, false, trailingslashit(dirname(plugin_basename(__FILE__))).'languages');

    /*
     * Plugin admin files.
     * Autoload files in alphabetical order.
     */
    $loader = container('loader')->add([
        themosis_path('plugin.'.$vars['namespace'].'.admin'),
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

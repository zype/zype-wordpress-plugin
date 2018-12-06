<?php

use Themosis\Facades\Action;
use Themosis\Facades\Page;

### Icon
$icon_url = 'data:image/svg+xml;base64,' . base64_encode('<svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 40 40"><path fill="white" d="M34,4H6A2.00587,2.00587,0,0,0,4,6V34a2.00591,2.00591,0,0,0,2,2H34a2.00591,2.00591,0,0,0,2-2V6A2.00587,2.00587,0,0,0,34,4ZM27.24017,20.76819l-13.2,11A.99995.99995,0,0,1,12.4,31V9a1,1,0,0,1,1.64014-.76825l13.2,11a1,1,0,0,1,0,1.53644Z"/></svg>');
$options = Config::get('zype');

Page::make('zype', 'Zype')->set([
    'capability' => 'manage_options',
    'icon' => $icon_url,
    'position' => 20,
    'tabs' => false,
    'menu' => __("Zype")
]);

### Settings page start
Page::make('zype-api-keys', 'Settings', 'zype')->set([
    'capability' => 'manage_options',
    'icon' => $icon_url,
    'position' => 20,
    'tabs' => true,
    'menu' => __("Settings")
]);

Action::add('zype_page_zype-api-keys', 'ZypeMedia\Controllers\Admin@admin_api_keys_page');
Action::add('admin_action_zype_api_keys', 'ZypeMedia\Controllers\Admin@admin_api_keys_page_save');
if (isset($options['app_key']) && $options['app_key'] !== '' &&
    isset($options['admin_key']) && $options['admin_key'] !== '' &&
    isset($options['player_key']) && $options['player_key'] !== '' &&
    isset($options['read_only_key']) && $options['read_only_key'] !== '') {

} else {
    return;
}

if (!empty($options['invalid_key'])) {
    return;
}
### Settings page end

### Users page start
Page::make('zype-users', 'Users', 'zype')->set([
    'capability' => 'manage_options',
    'icon' => $icon_url,
    'position' => 20,
    'tabs' => true,
    'menu' => __("Users")
]);

Action::add('zype_page_zype-users', 'ZypeMedia\Controllers\Admin@admin_users_page');
Action::add('admin_action_zype_users', 'ZypeMedia\Controllers\Admin@admin_users_page_save');
### Users page end

### Email Settings page start
Page::make('zype-email-settings', 'Email Settings', 'zype')->set([
    'capability' => 'manage_options',
    'icon' => $icon_url,
    'position' => 20,
    'tabs' => true,
    'menu' => __("Email Settings")
]);

Action::add('zype_page_zype-email-settings', 'ZypeMedia\Controllers\Admin\EmailSettings@index');
Action::add('admin_action_zype_email_settings', 'ZypeMedia\Controllers\Admin\EmailSettings@submit');
### Email Settings page end

### Monetization page start
Page::make('zype-monetization', 'Monetization', 'zype')->set([
    'capability' => 'manage_options',
    'icon' => $icon_url,
    'position' => 20,
    'tabs' => true,
    'menu' => __("Monetization")
]);

Action::add('zype_page_zype-monetization', 'ZypeMedia\Controllers\Admin@admin_braintree_page');
Action::add('admin_action_zype_braintree', 'ZypeMedia\Controllers\Admin@admin_braintree_page_save');
### Monetization page end

### Customize UI page start
Page::make('zype-customize-ui', 'Customize UI', 'zype')->set([
    'capability' => 'manage_options',
    'icon' => $icon_url,
    'position' => 20,
    'tabs' => true,
    'menu' => __("Customize UI")
]);

Action::add('zype_page_zype-customize-ui', 'ZypeMedia\Controllers\Admin\CustomizeUi@index');
Action::add('admin_action_zype_customize_ui', 'ZypeMedia\Controllers\Admin\CustomizeUi@submit');
### Customize UI page end

### Video search page start
Page::make('zype-video', 'Video search', 'zype')->set([
    'capability' => 'manage_options',
    'icon' => $icon_url,
    'position' => 20,
    'tabs' => true,
    'menu' => __("Video search")
]);

Action::add('zype_page_zype-video', 'ZypeMedia\Controllers\Admin@admin_video_search_page');
### Video search page end

### Playlist search page start
Page::make('zype-playlist', 'Playlist search', 'zype')->set([
    'capability' => 'manage_options',
    'icon' => $icon_url,
    'position' => 20,
    'tabs' => true,
    'menu' => __("Playlist search")
]);

Action::add('zype_page_zype-playlist', 'ZypeMedia\Controllers\Admin@admin_playlist_search_page');
### Playlist search page end

### Notice hook start
Action::add('admin_notices', 'zype_wp_admin_notices');
### Notice hook end

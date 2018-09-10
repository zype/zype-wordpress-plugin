<?php

use \ZypeMedia\Controllers\Consumer;
use Themosis\Facades\Input;
use Themosis\Facades\Asset;

add_shortcode('zype_grid', function() {
    $video = new Consumer\Videos();
    switch (Input::get('zype_type')) {
        case 'video_single':
            return $video->single();
            break;
        case 'video_index':
            return $video->index();
            break;
    }

    $grid = new Consumer\Gridscreen();
    return $grid->index();
});

if (Config::get('zype.livestream_enabled')) {
    add_shortcode('zype_livestream', function() {
        return Consumer\Live::show();
    });
}

add_shortcode('zype_categories', function() {
    return Consumer\Category::categories_list();
});

add_shortcode('zype_auth', function($attrs = array()) {
    $type = !empty($attrs['type'])? $attrs['type']: Input::get('zype_auth_type', 'login');

    $loginController = new Consumer\Auth();
    $profileController = new Consumer\Profile();
    $subscriptionsController = new Consumer\Subscriptions();
    $ajax = isset($attrs['ajax']) && $attrs['ajax'] == 'true' ? true : false;
    $redirect_url = isset($attrs['redirect_url']) ? $attrs['redirect_url'] : '';
    $root_parent = isset($attrs['root_parent']) ? $attrs['root_parent'] : '';

    switch ($type) {
        case 'login':
            return $loginController->login($ajax, $root_parent);
        case 'register':
            return $loginController->signup($ajax, $root_parent);
        case 'forgot':
            return $profileController->forgot_password($root_parent);
        case 'plans':
            return $subscriptionsController->plansView($root_parent, $redirect_url);
        case 'checkout':
            return $subscriptionsController->checkoutView(Input::get('planid'), $redirect_url);
    }
});

add_shortcode('zype_signup', function($attrs = array()) {
    $ajax = isset($attrs['ajax']) && $attrs['ajax'] == 'true' ? true : false;
    $loginController = new Consumer\Auth();
    return $loginController->signup($ajax);
});

add_shortcode('zype_forgot', function() {
    $profileController = new Consumer\Profile();
    return $profileController->forgot_password();
});

add_shortcode('zype_video', function($attrs) {
    $id = $attrs['id'];
    $view = !empty($attrs['view'])? $attrs['view']: 'full';

    if (!$id) {
        return;
    }

    $videos = new Consumer\Videos();

    return $videos->single($id, $view);
});

add_shortcode('zype_playlist', function($attrs) {
    $id = $attrs['id'];

    if (!$id) {
        return;
    }

    if (Input::get('zype_type') != 'video_single') {
        global $parent_id;
        $parent_id = $id;

        $playlist = \Zype::get_playlist($id);
        if ($playlist) {
            global $items;
            $items = $playlist->playlist_item_count;
        }

        $Gridscreen = new Consumer\Gridscreen();
        return $Gridscreen->index();
    }

    $videos = new Consumer\Videos();
    return $videos->single();
});

add_shortcode('subscribe', function($attrs) {
    $subscriptionsController = new Consumer\Subscriptions();
    return $subscriptionsController->subscribe();
});

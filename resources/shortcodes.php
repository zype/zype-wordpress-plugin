<?php

use Themosis\Facades\Input;
use ZypeMedia\Controllers\Consumer;
use ZypeMedia\Validators\Request;

$request = Request::capture();

add_shortcode('zype_grid', function () use ($request) {
    $video = new Consumer\Videos();

    switch ($request->validate('zype_type', ['textfield'])) {
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
    add_shortcode('zype_livestream', function () {
        return Consumer\Live::show();
    });
}

add_shortcode('zype_categories', function () {
    return Consumer\Category::categories_list();
});

add_shortcode('zype_auth', function ($attrs = array()) use ($request) {
    $type = !empty($attrs['type']) ? $request->sanitize($attrs['type']) : $request->validate('zype_auth_type', ['textfield'], 'login');

    $loginController = new Consumer\Auth();
    $profileController = new Consumer\Profile();
    $subscriptionsController = new Consumer\Subscriptions();
    $ajax = $attrs['ajax'] == 'true' ? true : false;
    $redirect_url = !empty($attrs['redirect_url']) ? $request->sanitize($attrs['redirect_url']) : '';

    switch ($type) {
        case 'login':
            return $loginController->login($ajax);
        case 'register':
            return $loginController->signup($ajax);
        case 'forgot':
            return $profileController->forgot_password();
        case 'plans':
            $rootParent = !empty($attrs['root_parent']) ? $attrs['root_parent'] : '';
            return $subscriptionsController->plansView($rootParent, $redirect_url);
        case 'checkout':
            $planId = $request->validate('planid', ['textfield']);
            return $subscriptionsController->checkoutView($planId, $redirect_url);
    }
});

add_shortcode('zype_signup', function($attrs = array()) {
    $ajax = $attrs['ajax'] == 'true' ? true : false;
    $loginController = new Consumer\Auth();
    return $loginController->signup($ajax);
});

add_shortcode('zype_forgot', function () {
    $profileController = new Consumer\Profile();
    return $profileController->forgot_password();
});

add_shortcode('zype_video', function ($attrs) use ($request) {
    $id = $request->sanitize($attrs['id'], ['textfield']);
    $view = !empty($attrs['view']) ? $request->sanitize($attrs['view'], ['textfield']) : 'full';

    if (!$id) {
        return;
    }

    $videos = new Consumer\Videos();

    return $videos->single($id, $view);
});

add_shortcode('zype_playlist', function ($attrs) use ($request) {
    $id = $request->sanitize($attrs['id'], ['textfield']);

    if (!$id) {
        return;
    }

    if ($request->validate('zype_type', ['textfield']) != 'video_single') {
        $gridscreen = new Consumer\Gridscreen();
        return $gridscreen->index(null, $id);
    }

    $videos = new Consumer\Videos();
    return $videos->single();
});

add_shortcode('subscribe', function($attrs) {
    $subscriptionsController = new Consumer\Subscriptions();
    return $subscriptionsController->subscribe();
});

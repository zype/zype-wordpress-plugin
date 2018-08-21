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
    $rootParent = !empty($attrs['root_parent']) ? $request->sanitize($attrs['root_parent']) : '';

    $loginController = new Consumer\Auth();
    $profileController = new Consumer\Profile();
    $subscriptionsController = new Consumer\Subscriptions();

    switch ($type) {
        case 'login':
            return $loginController->login();
        case 'register':
            return $loginController->signup();
        case 'forgot':
            return $profileController->forgot_password();
        case 'plans':
            return $subscriptionsController->plansView($rootParent);
        case 'checkout':
            return $subscriptionsController->checkoutView($request->validate('planid', ['textfield']));
    }
});

add_shortcode('zype_signup', function () {
    $loginController = new Consumer\Auth();
    return $loginController->signup();
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

<?php

$options = array(
    'admin_key' => '',
    'player_key' => '',
    'read_only_key' => '',
    'livestream_enabled' => true,
    'zobjects' => array(),
    'categories' => array(
        'City' => array(
            'city01' => array(
                'index' => 'on',
                'detail' => 'on',
                'url' => 'city/city01',
            ),
        ),
        'Fashion' => array(
            'fashion01' => array(
                'url' => '',
            ),
        ),
        'General' => array(
            'Sub1' => array(
                'url' => '',
            ),
            'Sub2' => array(
                'url' => '',
            ),
        ),
    ),
    'audio_only_enabled' => false,
    'excluded_categories' => array(),
    'authentication_enabled' => true,
    'subscriptions_enabled' => false,
    'device_link_enabled' => false,
    'zype_saas_compatability' => false,
    'cookie_key' => 'reset_me',
    'oauth_client_id' => '',
    'oauth_client_secret' => '',
    'flush' => false,
    'livestream_url' => 'livestream',
    'video_url' => 'video',
    'auth_url' => 'sign-in',
    'logout_url' => 'signout',
    'profile_url' => 'profile',
    'auth_url' => 'signin',
    'device_link_url' => 'link',
    'transaction_url' => 'transaction',
    'subscribe_url' => 'subscribe',
    'rental_url' => 'rental',
    'pass_url' => 'pass',
    'purchase_url' => 'purchase',
    'terms_url' => '',
    'playlist_pagination' => true,
    'braintree_environment' => 'sandbox',
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
    'endpoint' => '',
    'authpoint' => 'https://login.zype.com/oauth/token',
    'estWidgetHost' => 'https://play.zype.com',
    'zype_environment' => 'Production',
    'playerHost' => 'https://player.zype.com',
    'grid_screen_url' => 'grid',
    'grid_screen_parent' => '',
    'sub_short_code_btn_text' => 'SIGN UP',
    'sub_short_code_redirect_url' => '',
    'sub_short_code_text_after_sub' => 'MY ACCOUNT',
    'emails' => [
        'cancel_subscription' => [
            'text' => "We're very sorry to see you go! This email confirms your subscription has been canceled.\nPlease come back to visit if you'd like to subscribe again in the future.\nThanks.",
            'required' => []
        ],
        'forgot_password' => [
            'text' => "We received a request to reset your password. Please use the following link to set a new password for your account.\n{forgot_password_link}\nIf you did not request a password reset please disregard this email. Thanks for watching!",
            'required' => ['{forgot_password_link}']
        ],
        'new_account' => [
            'text' => "You can log in at the following URL using the email address and password you provided during account creation:\n{login_link}\nThanks again!",
            'required' => ['{login_link}']
        ],
        'new_rental' => [
            'text' => "Thank you for your rental to {video_name_link}, we know you'll enjoy it!\nYou can log in with the email address and password you provided at the following URL: \n{login_link}\nThanks again",
            'required' => ['{video_name_link}', '{login_link}']
        ],
        'new_purchase' => [
            'text' => "Thank you for your purchase to {video_name_link}, we know you'll enjoy it!\nYou can log in with the email address and password you provided at the following URL: \n{login_link}\nThanks again",
            'required' => ['{video_name_link}', '{login_link}']
        ],
        'new_pass' => [
            'text' => "Thank you for buying a pass plan, we know you'll enjoy it!\nYou can log in with the email address and password you provided at the following URL: \n{login_link}\nThanks again",
            'required' => ['{login_link}']
        ],        
        'new_subscription' => [
            'text' => "Thank you for subscribing, we hope you'll enjoy it!\nYou can log in with the email address and password you provided at the following URL: \n{login_link}\nThanks again!",
            'required' => ['{login_link}']
        ],
    ]
);

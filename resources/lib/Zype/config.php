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
    'stripe' => [
        'coupon_enabled' => true
    ],
    'livestream_authentication_required' => false,
    'cache_time' => 600,
    'last_transaction_created_at' => 0,
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
            'text' => "Thank you for your rental to {video_name}, we know you'll enjoy it!\nYou can log in with the email address and password you provided at the following URL: \n{login_link}\nThanks again",
            'required' => ['{video_name}', '{login_link}'],
            'enabled' => true
        ],
        'new_purchase' => [
            'text' => "Thank you for your purchase to {video_name}, we know you'll enjoy it!\nYou can log in with the email address and password you provided at the following URL: \n{login_link}\nThanks again",
            'required' => ['{video_name}', '{login_link}'],
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

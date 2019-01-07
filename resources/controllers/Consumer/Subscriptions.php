<?php

namespace ZypeMedia\Controllers\Consumer;

use Themosis\Facades\Config;
use ZypeMedia\Services\Braintree;

class Subscriptions extends Base
{
    public function __construct()
    {
        parent::__construct();
        $this->form_message = null;
    }

    public function plans()
    {
        $plan = [];
        $this->options = Config::get('zype');
        if (isset($this->options['subscribe_select'])) {
            foreach ($this->options['subscribe_select'] as $option) {
                $plan[] = \Zype::get_plan($option);
            }
        }
        $this->title = 'Select a Plan';
        $plans = $plan;

        echo view('auth.pre_auth', ['title' => 'Auth']);
        echo view('auth.plans', [
            'plans' => $plans,
            'title' => $this->title,
            'options' => $this->options,
            'root_parent' => '',
            'redirect_url' => home_url($this->options['profile_url'])
        ]);
        echo view('auth.post_auth', ['title' => 'Auth']);

        exit();
    }

    public function plansView($root_parent, $redirect_url = null)
    {
        $stripe_pk = Config::get('zype.stripe_pk');
        $plan = [];
        $this->options = Config::get('zype');
        if (isset($this->options['subscribe_select'])) {
            foreach ($this->options['subscribe_select'] as $option) {
                $plan[] = \Zype::get_plan($option);
            }
        }

        $this->title = 'Select a Plan';
        $plans = $plan;

        $content = view('auth.plans', [
            'plans' => $plans,
            'title' => $this->title,
            'options' => $this->options,
            'stripe_pk' => $stripe_pk,
            'root_parent' => $root_parent,
            'redirect_url' => $redirect_url
        ]);

        return $content;
    }

    public function subscribe() {
        $sub_short_code_btn_text = $this->options['sub_short_code_btn_text'];
        $sub_short_code_redirect_url = $this->options['sub_short_code_redirect_url'];
        $sub_short_code_text_after_sub = $this->options['sub_short_code_text_after_sub'];
        $profile_url = home_url(Config::get('zype.profile_url'));

        $subscription_shortcode_id = 'subscribe-shortcode-' . (time() * rand(1, 1000000));
        $subscribe_button_id = 'subscribe-button-' . (time() * rand(1, 1000000));
        $content_id = 'subscribe-button-content-' . (time() * rand(1, 1000000));

        $login_shortcode = ajax_shortcode('zype_auth', [
            'type' => 'login',
            'root_parent' => $content_id,
            'ajax' => true,
            'redirect_url' => $sub_short_code_redirect_url,
            'show_plans' => true
        ]);
        $sign_up_shortcode = ajax_shortcode('zype_signup', [
            'root_parent' => $content_id,
            'ajax' => true,
            'redirect_url' => $sub_short_code_redirect_url,
            'show_plans' => true
        ]);
        $forgot_password_shortcode = ajax_shortcode('zype_forgot', [
            'root_parent' => $content_id
        ]);
        $plans_shortcode = ajax_shortcode('zype_auth', [
            'type' => 'plans',
            'root_parent' => $content_id,
            'redirect_url' => $sub_short_code_redirect_url
        ]);

        $content = view('subscribe_button', [
            'btn_text' => $sub_short_code_btn_text,
            'redirect_url' => $sub_short_code_redirect_url,
            'btn_text_after_sub' => $sub_short_code_text_after_sub,
            'subscription_shortcode_id' => $subscription_shortcode_id,
            'subscribe_button_id' => $subscribe_button_id,
            'content_id' => $content_id,
            'profile_url' => $profile_url,
            'shortcodes'    => [
                'login'         => $login_shortcode,
                'sign_up'       => $sign_up_shortcode,
                'forgot_pass'   => $forgot_password_shortcode,
                'plans'         => $plans_shortcode
            ]
        ]);

        return $content;
    }

    public function checkoutView($plan_id, $redirect_url = null, $root_parent = null)
    {
        $plan_id = $this->request->sanitize($plan_id, ['textfield']);

        if ($plan_id && $plan = \Zype::get_plan($plan_id)) {
            $za = new \ZypeMedia\Services\Auth;
            $consumer_id = $za->get_consumer_id();
            $access_token = $za->get_access_token();
            $consumer = \Zype::get_consumer($consumer_id, $access_token);

            $stripe_pk = Config::get('zype.stripe_pk');
            if ($consumer->braintree_id) {
                $braintree_token = (new Braintree())->generateBraintreeToken($consumer->braintree_id);
            }
        } else {
            zype_flash_message('error', 'Please select a valid plan.');

            // redirect to video single page
            $vm = (new \ZypeMedia\Models\Video);
            $vm->find($videoId);
            $video = $vm->single;
            wp_redirect($video->permalink);
            exit();
        }

        $title = 'Select a Payment Method';

        $error = false;
        if (empty ($plan->stripe_id) && empty ($braintree_token)) {
            $error = 'Sorry, but this plan is temporarily unavailable';
        } elseif (!empty ($plan->stripe_id) && empty ($stripe_pk)) {
            $error = 'Currently it is not possible to pay through Stripe';
        }

        if(isset($redirect_url) && !empty($redirect_url) && (strpos($redirect_url, 'http') !== 0)){
            $redirect_url = home_url($redirect_url);
        }

        $content = view('auth.subscription_checkout', [
            'plan' => $plan,
            'braintree_token' => $braintree_token,
            'root_parent' => $root_parent,
            'videoId' => $videoId,
            'stripe_pk' => $stripe_pk,
            'title' => $title,
            'error' => $error,
            'redirect_url' => $redirect_url
        ]);

        return $content;
    }

    public function checkoutSuccess()
    {
        $form = $this->request->validateAll(['textfield']);

        $data = array();

        if (empty($form['email'])) {
            $data['errors']['email'] = "Email is required";
        }

        if (empty($form['plan_id'])) {
            $data['errors']['plan'] = "Plan id is required";
        }

        if (empty($form['type'])) {
            $data['errors']['type'] = "Type is required";
        }

        if ($form['type'] == 'stripe' && empty($form['stripe_card_token'])) {
            $data['errors']['token'] = "Token is required";
        }

        if ($form['type'] == 'braintree' && empty($form['braintree_payment_nonce'])) {
            $data['errors']['token'] = "Nonce is required";
        }

        if (empty($data['errors'])) {
            $za = new \ZypeMedia\Services\Auth;
            $consumer_id = $za->get_consumer_id();
            $access_token = $za->get_access_token();
            $consumer = \Zype::get_consumer($consumer_id, $access_token);
            $plan = \Zype::get_plan($form['plan_id']);

            if ($consumer && $form['email'] == $consumer->email) {
                $sub = [
                    'consumer_id' => $consumer_id,
                    'plan_id' => $form['plan_id']
                ];

                switch ($form['type']) {
                    case 'braintree':
                        $sub['braintree_payment_nonce'] = $form['braintree_payment_nonce'];
                        $sub['braintree_id'] = $plan->braintree_id;
                        break;
                    case 'stripe':
                        $sub['stripe_card_token'] = $form['stripe_card_token'];
                        $sub['coupon_code'] = $form['stripe_coupon_code'];
                        break;
                }

                $new_sub = \Zype::create_subscription($sub);

                if ($new_sub && $new_sub->success()) {
                    $mailer = new \ZypeMedia\Services\Mailer;
                    $mail_res = $mailer->new_subscription($consumer->email);

                    $za->sync_cookie();

                    $data['success'] = true;
                } else {
                    $messageError = $new_sub->getMessage();
                    $description = $messageError['description']?: 'The purchase could not be completed. Please try again later.';
                    $data['errors']['cannot'] = $description;
                    $data['success'] = false;
                }
            } else {
                $data['errors']['email'] = 'You do not have an account, purchase is not possible.';
                $data['success'] = false;
            }
        } else {
            $data['success'] = false;
        }

        // exit('ok');
        echo json_encode($data);

        exit();
    }
}

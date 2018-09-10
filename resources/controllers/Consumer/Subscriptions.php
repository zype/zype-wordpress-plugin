<?php

namespace ZypeMedia\Controllers\Consumer;

use ZypeMedia\Services\Braintree;
use Themosis\Facades\Config;

class Subscriptions extends Base
{
    public function __construct()
    {
        parent::__construct();
        $this->form_message = null;
    }

    public function plans()
    {
        global $plans;
        $plan = [];
        $this->options = Config::get('zype');
        if(isset($this->options['subscribe_select'])){
            foreach($this->options['subscribe_select'] as $option){
                $plan[] = \Zype::get_plan($option);
            }
        }
        $this->title    = 'Select a Plan';
        $plans = $plan;

        print view('auth.plans', [
            'plans' => $plans,
            'title' => $this->title,
            'options' => $this->options
        ]);

        exit();
    }

    public function plansView($root_parent, $redirect_url = null)
    {
        global $plans;
        $stripe_pk = Config::get('zype.stripe_pk');
        $plan = [];
        $this->options = Config::get('zype');
        if(isset($this->options['subscribe_select'])){
            foreach($this->options['subscribe_select'] as $option){
                $plan[] = \Zype::get_plan($option);
            }
        }

        $this->title    = 'Select a Plan';
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

        $content = view('subscribe_button', [
            'btn_text' => $sub_short_code_btn_text,
            'redirect_url' => $sub_short_code_redirect_url,
            'btn_text_after_sub' => $sub_short_code_text_after_sub,
            'profile_url' => $profile_url
        ]);

        return $content;
    }

    public function checkout()
    {
        global $plan;
        global $braintree_token;
        global $stripe_pk;
        global $videoId;

        if (isset($_GET['plan_id']) && $plan = \Zype::get_plan(filter_var($_GET['plan_id'], FILTER_SANITIZE_STRING))) {
            $za           = new \ZypeMedia\Services\Auth;
            $consumer_id  = $za->get_consumer_id();
            $access_token = $za->get_access_token();
            $consumer     = \Zype::get_consumer($consumer_id, $access_token);

            $stripe_pk = Config::get('zype.stripe_pk');
            if($consumer->braintree_id) {
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

        print view('auth.subscription_checkout', [
            'plan' => $plan,
            'braintree_token' => $braintree_token,
            'videoId' => $videoId,
            'stripe_pk' => $stripe_pk,
            'title' => $title
        ]);

        exit();
    }

    public function checkoutView($plan_id, $redirect_url = null)
    {
        global $plan;
        global $braintree_token;
        global $stripe_pk;
        global $videoId;

        if (isset($plan_id) && $plan = \Zype::get_plan(filter_var($plan_id, FILTER_SANITIZE_STRING))) {
            $za           = new \ZypeMedia\Services\Auth;
            $consumer_id  = $za->get_consumer_id();
            $access_token = $za->get_access_token();
            $consumer     = \Zype::get_consumer($consumer_id, $access_token);

            $stripe_pk = Config::get('zype.stripe_pk');
            if($consumer->braintree_id) {
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
		if ( empty ( $plan->stripe_id ) && empty ( $braintree_token ) ) {
			$error = 'Sorry, but this plan a temporarily unavailable';
		} elseif( !empty ( $plan->stripe_id ) && empty ( $stripe_pk ) ) {
			$error = 'Currently it is not possible to pay through Stripe';
		}

        if(isset($redirect_url) && !empty($redirect_url) && (strpos($redirect_url, 'http') !== 0)){
            $redirect_url = home_url($redirect_url);
        }

        $content = view('auth.subscription_checkout', [
            'plan' => $plan,
            'braintree_token' => $braintree_token,
            'videoId' => $videoId,
            'stripe_pk' => $stripe_pk,
            'title' => $title,
            'error' => $error,
            'redirect_url' => $redirect_url
        ]);

        return $content;
    }

    public function checkoutSuccess() {
        $form = filter_var_array($_POST, FILTER_SANITIZE_STRING);

        $data = array();

        if (!isset($form['email'])) {
            $data['errors']['email'] = "Email is required";
        }

        if (!isset($form['plan_id'])) {
            $data['errors']['plan'] = "Plan id is required";
        }

        if (!isset($form['type'])) {
            $data['errors']['type'] = "Type is required";
        }

        if ($form['type'] == 'stripe' && empty($form['stripe_card_token'])) {
            $data['errors']['token'] = "Token is required";
        }

        if ($form['type'] == 'braintree' && empty($form['braintree_payment_nonce'])) {
            $data['errors']['token'] = "Nonce is required";
        }

        if(empty($data['errors'])) {
            $za           = new \ZypeMedia\Services\Auth;
            $consumer_id  = $za->get_consumer_id();
            $access_token = $za->get_access_token();
            $consumer     = \Zype::get_consumer($consumer_id, $access_token);
            $plan         = \Zype::get_plan(filter_var($form['plan_id'], FILTER_SANITIZE_STRING));

            if ($consumer && $form['email'] == $consumer->email) {
                $sub = [
                    'consumer_id' => $consumer_id,
                    'plan_id'     => $form['plan_id']
                ];

                switch ($form['type']) {
                    case 'braintree':
                        $sub['braintree_payment_nonce'] = $form['braintree_payment_nonce'];
                        $sub['braintree_id'] = $plan->braintree_id;
                        break;
                    case 'stripe':
                        $sub['stripe_card_token'] = $form['stripe_card_token'];
                        break;
                }

                $new_sub = \Zype::create_subscription($sub);

                if ($new_sub) {
                    $mailer = new \ZypeMedia\Services\Mailer;
                    $mailer->new_subscription($consumer->email);
                    $mail_res = $mailer->send();

                    $za->sync_cookie();

                    $data['success'] = true;
                } else {
                    $data['errors']['cannot'] = 'The purchase could not be completed. Please try again later.';
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

    public function checkout_success()
    {
        $form = filter_var_array($_POST, FILTER_SANITIZE_STRING);

        $braintree_nonce = $this->get_braintree_nonce($form);

        if (isset($form['email']) && isset($form['plan_id']) && isset($form['type']) && ($braintree_nonce || isset($form['stripe_card_token']))) {
            $za           = new \ZypeMedia\Services\Auth;
            $consumer_id  = $za->get_consumer_id();
            $access_token = $za->get_access_token();
            $consumer     = \Zype::get_consumer($consumer_id, $access_token);

            if ($consumer && $form['email'] == $consumer->email) {
                $sub = [
                    'consumer_id' => $consumer_id,
                    'plan_id'     => $form['plan_id']
                ];

                switch ($form['type']) {
                    case 'braintree':
                        $sub['braintree_payment_nonce'] = $braintree_nonce;
                        $sub['braintree_id'] = Config::get('zype.braintree_public_key');
                        break;
                    case 'stripe':
                        $sub['stripe_card_token'] = $form['stripe_card_token'];
                        $sub['stripe_id'] = Config::get('zype.stripe_pk');
                        break;
                }

                $new_sub = \Zype::create_subscription($sub);

                if ($new_sub) {
                    //send email
                    $mailer = new \ZypeMedia\Services\Mailer;
                    $mailer->new_subscription($consumer->email);
                    $mail_res = $mailer->send();

                    $za->sync_cookie();

                    wp_redirect(get_zype_url('profile'));
                } else {
                    zype_flash_message('error', 'An error has occured. You have not been charged.');
                    wp_redirect(get_zype_url('profile'));
                    exit();
                }
            } else {
                zype_flash_message('error', 'An error has occured. You have not been charged.');
                $za->logout();
                wp_redirect(get_zype_url('profile'));
                exit();
            }
        } else {
            zype_flash_message('error', 'An error has occured. You have not been charged.');
            wp_redirect(get_zype_url('profile'));
            exit();
        }
    }

    protected function get_braintree_nonce($request) {
        $braintree_nonce = null;
        if (isset($request['payment_method_nonce'])) {
            $braintree_nonce = $request['payment_method_nonce'];
        }
        elseif (isset($request['braintree_payment_nonce'])) {
            $braintree_nonce = $request['braintree_payment_nonce'];
        }

        return $braintree_nonce;
    }

}

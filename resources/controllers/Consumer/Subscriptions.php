<?php

namespace ZypeMedia\Controllers\Consumer;

use ZypeMedia\Services\Braintree;
use Themosis\Facades\Config;

class Subscriptions extends Base
{
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

    public function checkout()
    {
        global $plan;
        global $braintree_token;
        global $stripe_pk;
        global $videoId;

        if (isset($_GET['plan_id']) && $plan = \Zype::get_plan(filter_var($_GET['plan_id'], FILTER_SANITIZE_STRING))) {
            $stripe_pk = Config::get('zype.stripe_pk');

            $braintree_id = (new \ZypeMedia\Services\Auth)->get_consumer_braintree_id();
            $braintree_token = (new Braintree())->generateBraintreeToken($braintree_id);
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


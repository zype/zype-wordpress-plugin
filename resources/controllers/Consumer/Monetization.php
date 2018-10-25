<?php

namespace ZypeMedia\Controllers\Consumer;

use Themosis\Facades\Config;
use ZypeMedia\Services\Braintree;

class Monetization extends Base
{
    const SUBSCRIPTION = 'subscription';
    const PURCHASE = \ZypeMedia\Models\Transaction::PURCHASE;
    const PASS_PLAN = \ZypeMedia\Models\Transaction::PASS_PLAN;
    const RENTAL = \ZypeMedia\Models\Transaction::RENTAL;
    const TRANSACTION_TYPES = [self::SUBSCRIPTION, self::PURCHASE, self::PASS_PLAN, self::RENTAL];

    private $stripe_pk = '';
    private $root_parent = '';
    private $video_id = '';
    private $video = '';
    private $redirect_url = '';

    public function __construct($root_parent, $video_id, $redirect_url)
    {
        parent::__construct();
        $this->options = Config::get('zype');
        $this->stripe_pk = $this->options['stripe_pk'];
        $this->form_message = null;
        $this->root_parent = $root_parent;
        $this->video_id = $video_id;
        $this->redirect_url = $redirect_url;
        $this->video = $this->get_video();
    }

    public function paywall_view($attrs)
    {
        $monetizations = $this->get_monetizations();
        $plan = [];
        $subscription_plans = [];
        $pass_plans = [];
        if($monetizations['subscription']['required']) {
          if (isset($this->options['subscribe_select'])) {
              foreach ($this->options['subscribe_select'] as $option) {
                  $plan[] = \Zype::get_plan($option);
              }
          }
          $subscription_plans = $plan;
        }

        $plan = [];

        if($monetizations['pass']['required']) {
          if (isset($this->options['pass_plans_select'])) {
              foreach ($this->options['pass_plans_select'] as $option) {
                  $plan[] = \Zype::get_pass_plan($option);
              }
          }
          $pass_plans = $plan;
        }

        $content = view('auth.paywall', [
            'subscription_plans' => $subscription_plans,
            'pass_plans' => $pass_plans,
            'options' => $this->options,
            'stripe_pk' => $this->stripe_pk,
            'root_parent' => $this->root_parent,
            'monetizations' => $monetizations,
            'redirect_url' => $this->redirect_url,
            'video_id' => $this->video_id
        ]);

        return $content;
    }

    public function cc_form($attrs)
    {
        $error = false;
        $transaction_type = isset($attrs['transaction_type']) ? $this->request->sanitize($attrs['transaction_type'], ['textfield']) : '';
        $plan_id = isset($attrs['plan_id']) ? $this->request->sanitize($attrs['plan_id'], ['textfield']) : '';
        $stripe_ok = true;
        if(!in_array($transaction_type, self::TRANSACTION_TYPES)) {
            $error = 'Please select a valid transaction.';
            status_header(400, $error);
            return new WP_Error('invalid_transaction_type', $error);
        }

        if(empty($this->stripe_pk)) {
            $error = 'Currently it is not possible to pay through Stripe';
            status_header(400, $error);
            return new WP_Error('stripe_unavailable', $error);
        }
        else {
            $za = new \ZypeMedia\Services\Auth;
            $consumer_id = $za->get_consumer_id();
            $access_token = $za->get_access_token();
            $consumer = \Zype::get_consumer($consumer_id, $access_token);
            if ($consumer->braintree_id) {
                $braintree_token = (new Braintree())->generateBraintreeToken($consumer->braintree_id);
            }

            if($transaction_type == self::SUBSCRIPTION) {
                $plan = $this->get_subscription($plan_id, $braintree_token);
                if(!$plan) {
                    $error = 'Please select a valid plan.';
                    status_header(400, $error);
                    return new WP_Error('invalid_plan', $error);
                }
                elseif (empty($plan->stripe_id) && empty($braintree_token)) {
                    $error = 'Sorry, but this plan is temporarily unavailable';
                    status_header(400, $error);
                    return new WP_Error('unavailable_plan', $error);
                }
                if(empty($plan->stripe_id)) {
                    $stripe_ok = false;
                }
            } elseif ($transaction_type == self::PASS_PLAN) {
                $pass_plan = $this->get_pass_plan($plan_id);
                if(!$pass_plan) {
                    $error = 'Please select a valid pass plan.';
                    status_header(400, $error);
                    return new WP_Error('invalid_pass_plan', $error);
                }
            }

            if(isset($redirect_url) && !empty($redirect_url) && (strpos($redirect_url, 'http') !== 0)){
                $redirect_url = home_url($redirect_url);
            }
        }
        $content = view('auth.cc_form', [
            'plan' => $plan,
            'pass_plan' => $pass_plan,
            'transaction_type' => $transaction_type,
            'braintree_token' => $braintree_token,
            'root_parent' => $this->root_parent,
            'stripe_pk' => $this->stripe_pk,
            'redirect_url' => $this->redirect_url,
            'video_id' => $this->video_id,
            'stripe_ok' => $stripe_ok,
        ]);

        return $content;
    }

    private function get_subscription($plan_id, $braintree_token)
    {
        $plan = \Zype::get_plan($plan_id);
        if(empty($plan)) {
            return false;
        }
        return $plan;
    }


    private function get_pass_plan($plan_id)
    {
        $plan = \Zype::get_pass_plan($plan_id);
        if(empty($plan)) {
            return false;
        }
        return $plan;
    }

    private function get_monetizations()
    {
        return [
            'subscription' => [
                'required' => $this->video->subscription_required
            ],
            'pass' => [
                'required' => $this->video->pass_required
            ],
            'rental' => [
                'required' => $this->video->rental_required,
                'days' => $this->video->rental_duration,
                'price' => $this->video->rental_price
            ],
            'purchase' => [
                'required' => $this->video->purchase_required,
                'price' => $this->video->purchase_price
            ]
        ];
    }

    private function get_video()
    {
        $vm = (new \ZypeMedia\Models\Video);
        $vm->find($this->video_id);
        return $vm->single;
    }
}

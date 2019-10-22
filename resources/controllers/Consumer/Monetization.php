<?php

namespace ZypeMedia\Controllers\Consumer;

use Themosis\Facades\Config;
use ZypeMedia\Services\Braintree;
use ZypeMedia\Models\V2\Playlist;
use ZypeMedia\Models\V2\Plan;
use ZypeMedia\Models\V2\Consumer;

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
    private $playlist_id = '';
    private $object_id = '';
    private $object_type = '';
    private $video = '';
    private $redirect_url = '';

    // $object_type must be 'playlist' or 'video'.
    public function __construct($root_parent, $video_id, $playlist_id, $object_type, $redirect_url)
    {
        parent::__construct();
        $this->options = Config::get('zype');
        $this->stripe_pk = $this->options['stripe_pk'];
        $this->form_message = null;
        $this->root_parent = $root_parent;
        $this->video_id = $video_id;
        $this->playlist_id = $playlist_id;
        $this->object_id = $object_type === 'video' ? $video_id : $playlist_id;
        $this->object_type = $object_type;
        $this->redirect_url = $redirect_url;
    }

    public function paywall_view()
    {
        $this->object = $this->get_object_to_purchase();
        $monetizations = $this->get_monetizations();
        $plan = [];
        $subscription_plans = [];
        $pass_plans = [];
        if($monetizations['subscription']['required']) {
            if (isset($this->options['subscribe_select']) && !empty($this->options['subscribe_select'])) {
                $subscription_plans = Plan::all(['id[]' => $this->options['subscribe_select']], false);
                $subscription_plans = array_filter($subscription_plans, function($plan) {
                    return in_array($plan->_id, $this->object->plan_ids) || $plan->global_entitlement_type();
                });
            }
        }

        $plan = [];

        if($monetizations['pass']['required']) {
            if (isset($this->options['pass_plans_select'])) {
                foreach ($this->options['pass_plans_select'] as $option) {
                    $plan[] = \Zype::get_pass_plan($option);
                    $plan[count($plan) - 1]->amount = number_format($plan[count($plan) - 1]->amount, 2);
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
            'video_id' => $this->video_id,
            'playlist_id' => $this->playlist_id
        ]);

        return $content;
    }

    public function cc_form($attrs)
    {
        $error = false;
        $transaction_type = isset($attrs['transaction_type']) ? $this->request->sanitize($attrs['transaction_type'], ['textfield']) : '';
        $plan_id = isset($attrs['plan_id']) ? $this->request->sanitize($attrs['plan_id'], ['textfield']) : '';

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
            if($transaction_type == self::SUBSCRIPTION) {
                $za = new \ZypeMedia\Services\Auth;
                $consumer_id = $za->get_consumer_id();
                $plan = $this->get_plan($plan_id);

                if ($plan->braintree_id) {
                    $braintree_consumer = Consumer::get_braintree_customer($consumer_id);
                    $braintree_token = (new Braintree())->generateBraintreeToken($braintree_consumer->data->response->id);
                }
                elseif (empty($plan->stripe_id) && empty($braintree_token)) {
                    $error = 'Sorry, but this plan is temporarily unavailable';
                    status_header(400, $error);
                    return new WP_Error('unavailable_plan', $error);
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
            'playlist_id' => $this->playlist_id,
            'object_id' => $this->object_id,
            'object_type' => $this->object_type,
            'coupons_enabled' => $this->options['stripe']['coupon_enabled']
        ]);

        return $content;
    }

    private function get_plan($plan_id)
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
        $playlists = Playlist::all(['video_id' => $this->video_id], false);
        $rental_playlists = array_filter($playlists, function ($playlist) {
            return $playlist->rental_required;
        });
        $purchase_playlists = array_filter($playlists, function ($playlist) {
            return $playlist->purchase_required;
        });
        return [
            'subscription' => $this->subscription_params(),
            'pass' => $this->pass_params(),
            'rental' => [
                'video' => $this->rental_params($this->object),
                'playlists' => array_map(array($this, 'rental_params'), array_values($rental_playlists))
            ],
            'purchase' => [
                'video' => $this->purchase_params($this->object),
                'playlists' => array_map(array($this, 'purchase_params'), array_values($purchase_playlists))
            ]
        ];
    }

    private function subscription_params()
    {
        return [
            'required' => $this->object->subscription_required
        ];
    }

    private function pass_params()
    {
        return [
            'required' => $this->object->pass_required
        ];
    }

    private function rental_params($object)
    {
        return [
            'required' => $object->rental_required,
            'days' => $object->rental_duration,
            'price' => number_format($object->rental_price, 2),
            'playlist_item_count' => isset($object->playlist_item_count) ? $object->playlist_item_count : null,
            'name' => $object->title,
            'id' => $object->_id
        ];
    }

    private function purchase_params($object)
    {
        return [
            'required' => $object->purchase_required,
            'price' => number_format($object->purchase_price, 2),
            'playlist_item_count' => isset($object->playlist_item_count) ? $object->playlist_item_count : null,
            'name' => $object->title,
            'id' => $object->_id
        ];
    }

    private function get_object_to_purchase()
    {
        if($this->object_type === 'video') {
            $vm = new \ZypeMedia\Models\Video();
            $vm->find($this->video_id);
            $object = $vm->single;
        }
        else {
            $object = \ZypeMedia\Models\V2\Playlist::find($this->playlist_id);
        }
        return $object;
    }
}

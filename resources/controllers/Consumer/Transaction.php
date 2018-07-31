<?php

namespace ZypeMedia\Controllers\Consumer;

use ZypeMedia\Services\Braintree;

class Transaction extends Base
{
    public function __construct()
    {
        parent::__construct();
        $this->form_message = null;
    }

    public function plans()
    {
        global $pass_plans;

        $pass_plans = \Zype::get_all_pass_plans();

        $this->title = 'Select a Plan';
        $this->template = __FUNCTION__;
    }

    public function checkout()
    {
        global $pass_plan;
        global $video;
        global $braintree_token;
        global $consumer_id;
        global $videoId;

        $this->processTransactionSubmit();

        if ($videoId = $this->request->validate('video_id', ['textfield'])) {
            $vm = new \ZypeMedia\Models\Video;
            $vm->find($videoId);

            $video = $vm->single;

            if ($video->rental_required) {
                $this->template = 'rental_checkout';
            }

            $plan_id = $this->request->validate('plan_id', ['textfield']);
            if ($video->pass_required && $plan_id && $pass_plan = \Zype::get_pass_plan($plan_id)) {
                $this->template = 'pass_plan_checkout';
            }

            if (!$this->template) {
                wp_redirect($video->permalink);
                die();
            }
        }

        $braintree_id = (new \ZypeMedia\Services\Auth)->get_consumer_braintree_id();
        if ($braintree_id) {
            $braintree_token = (new Braintree)->generateBraintreeToken($braintree_id);
        }

        $consumer_id = (new \ZypeMedia\Services\Auth)->get_consumer_id();

        $this->title = 'Select a Payment Method';

        return $this;
    }

    protected function processTransactionSubmit()
    {
        $params = $this->request->validateAll(['textfield']);

        if (!empty($params['plan_id']) && !empty($params['video_id'])) {
            $this->processPassPlanSubmit($params['video_id']);
        } else if (!empty($params['video_id'])) {
            $this->processRentalSubmit($params['video_id']);
        }
    }

    protected function processPassPlanSubmit($videoId)
    {
        $form = $this->request->validateAll(['textfield']);

        $braintree_nonce = $this->get_braintree_nonce($form);

        // Pass plans support only Braintree method.
        if ($braintree_nonce && !empty($form['plan_id'])) {

            $newTransaction = (new \ZypeMedia\Models\Transaction())->createTransaction(\ZypeMedia\Models\Transaction::TYPE_PASS_PLAN, $videoId, 'braintree', $braintree_nonce, $form['plan_id']);

            if ($newTransaction === false) {
                zype_flash_message('error', 'Please select a valid plan.');
            } else {
                $vm = (new \ZypeMedia\Models\Video);
                $vm->find($videoId);
                $video = $vm->single;
                wp_redirect($video->permalink);
            }
        }
    }

    protected function processRentalSubmit($videoId)
    {
        $form = $this->request->validateAll(['textfield']);

        $braintree_nonce = $this->get_braintree_nonce($form);

        if ($braintree_nonce) {

            $newTransaction = (new \ZypeMedia\Models\Transaction())->createTransaction(\ZypeMedia\Models\Transaction::TYPE_RENTAL, $videoId, 'braintree', $braintree_nonce);

            if ($newTransaction === false) {
                zype_flash_message('error', 'Failed to create transaction. Please try later.');
            } else {

                $vm = (new \ZypeMedia\Models\Video);
                $vm->find($videoId);
                $video = $vm->single;

                $mailer = new \ZypeMedia\Services\Mailer;
                $mailer->new_rental((new \ZypeMedia\Services\Auth)->get_email(), ['videoUrl' => $video->permalink, 'videoTitle' => $video->title]);
                $mail_res = $mailer->send();

                wp_redirect($video->permalink);
            }
        }
    }

    public function template($template)
    {
        $find = [
            'zype/payment/' . $this->template . '.php',
            'payment/' . $this->template . '.php',
            // @deprecated 'auth' will be removed soon.
            'zype/auth/' . $this->template . '.php',
            'auth/' . $this->template . '.php',
        ];

        if ($locatedFile = $this->locate_file($find)) {
            $template = $locatedFile;
        }

        return $template;
    }

}

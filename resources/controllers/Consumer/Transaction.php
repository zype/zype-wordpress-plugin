<?php

namespace ZypeMedia\Controllers\Consumer;

use ZypeMedia\Services\Braintree;

class Transaction extends Base
{

    public function plans()
    {
        global $pass_plans;

        $pass_plans = \Zype::get_all_pass_plans();

        $this->title    = 'Select a Plan';
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

        if (isset($_GET['video_id']) && $videoId = $_GET['video_id']) {
            $vm = new \ZypeMedia\Models\Video;
            $vm->find($videoId);

            $video = $vm->single;

            if ($video->rental_required) {
                $this->template = 'rental_checkout';
            }

            if ($video->pass_required && isset($_GET['plan_id']) && $pass_plan = \Zype::get_pass_plan(filter_var($_GET['plan_id']))) {
                $this->template = 'pass_plan_checkout';
            }

            if (!$this->template) {
                wp_redirect($video->permalink);
                die();
            }
        }

        $braintree_id    = (new \ZypeMedia\Services\Auth)->get_consumer_braintree_id();
        $braintree_token = (new Braintree)->generateBraintreeToken($braintree_id);

        $consumer_id = (new \ZypeMedia\Services\Auth)->get_consumer_id();

        $this->title = 'Select a Payment Method';
    }

    protected function processTransactionSubmit()
    {
        if (!$_POST) {
            return;
        }
        $params = filter_var_array($_GET, FILTER_SANITIZE_STRING);

        if (isset($params['plan_id']) && isset($params['video_id'])) {
            $this->processPassPlanSubmit($params['video_id']);
        }
        else if (isset($params['video_id'])) {
            $this->processRentalSubmit($params['video_id']);
        }
    }

    protected function processPassPlanSubmit($videoId)
    {
        $form = filter_var_array($_POST, FILTER_SANITIZE_STRING);

        $braintree_nonce = $this->get_braintree_nonce($form);

        // Pass plans support only Braintree method.
        if ($braintree_nonce && isset($form['plan_id'])) {

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
        $form = filter_var_array($_POST, FILTER_SANITIZE_STRING);

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


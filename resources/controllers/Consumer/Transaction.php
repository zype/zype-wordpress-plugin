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

    protected function checkout_success()
    {
        $form = $this->request->validateAll(['textfield']);

        $data = array();

        if(empty($form['transaction_type']) ||
            !in_array($form['transaction_type'], \ZypeMedia\Controllers\Consumer\Monetization::TRANSACTION_TYPES)) {
            $data['errors']['transaction_type'] = "Transaction Type unknown";
        }

        if (empty($form['email'])) {
            $data['errors']['email'] = "Email is required";
        }

        if($form['transaction_type'] == \ZypeMedia\Controllers\Consumer\Monetization::PASS_PLAN &&
            empty($form['pass_plan_id'])) {
            $data['errors']['pass_plan'] = "Pass Plan id is required";
        }

        if (empty($form['video_id'])) {
            $data['errors']['video_id'] = "Unknown video ID";
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
            if ($consumer && $form['email'] == $consumer->email) {
                $sub = [
                    'consumer_id' => $consumer_id,
                ];

                switch ($form['type']) {
                    case 'braintree':
                        $sub['payment_nonce'] = $form['braintree_payment_nonce'];
                        $sub['braintree_id'] = $plan->braintree_id;
                        break;
                    case 'stripe':
                        $sub['payment_nonce'] = $form['stripe_card_token'];
                        break;
                }

                $transaction = new \ZypeMedia\Models\Transaction();

                $transaction = $transaction->create_transaction($form['transaction_type'], $form['video_id'], $form['type'], $sub['payment_nonce'], $form['pass_plan_id']);

                if ($transaction) {
                    $mailer = new \ZypeMedia\Services\Mailer;
                    $vm = (new \ZypeMedia\Models\Video);
                    $vm->find($form['video_id']);
                    $video = $vm->single;
                    $mailer->new_transaction($consumer->email, $form['transaction_type'], ['video_url' => $video->permalink, 'video_title' => $video->title]);
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
        }
        else {
            $data['success'] = false;
        }

        echo json_encode($data);

        exit();
    }
}

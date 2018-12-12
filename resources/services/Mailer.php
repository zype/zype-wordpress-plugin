<?php

namespace ZypeMedia\Services;

use Themosis\Facades\Config;

class Mailer
{

    public function __construct()
    {
        $this->options = Config::get('zype');
        $this->admin_email = get_option('admin_email');
        $this->headers = [
            'Content-Type: text/html; charset=UTF-8',
            'Bcc: ' . $this->admin_email,
        ];

        add_filter('wp_mail_from_name', function ($name) {
            return get_bloginfo('name');
        });
    }

    public function new_account($to, $dictionary = null, $subject = null)
    {
        $this->subject = $subject ? $subject : 'Thank you for signing up for ' . get_bloginfo('name') . '!';
        $this->to = $to;
        $email_text = $this->get_email_text('new_account');
        $email_text = str_replace('{login_link}', $this->get_login_placeholder(), $email_text);
        $this->body = view('email.template', [
            'text' => $email_text,
            'title' => 'Thank you for signing up!'
        ]);
    }

    public function new_subscription($to, $dictionary = null, $subject = null)
    {
        $this->subject = $subject ? $subject : 'Thank you for subscribing to ' . get_bloginfo('name') . '!';
        $this->to = $to;
        $email_text = $this->get_email_text('new_subscription');
        $email_text = str_replace('{login_link}', $this->get_login_placeholder(), $email_text);
        $this->body = view('email.template', [
            'text' => $email_text,
            'title' => 'New Subscription Confirmation'
        ]);
    }

    public function new_transaction($to, $transaction_type, $dictionary = null)
    {
        $transaction_humanized = ucfirst($transaction_type);
        $this->subject = "Thank you for your {$transaction_humanized} on " . get_bloginfo('name') . '!';
        $this->to = $to;
        $email_text = $this->get_email_text("new_{$transaction_type}");
        if($transaction_type != \ZypeMedia\Models\Transaction::PASS_PLAN) {
            $email_text = str_replace('{object_name}', $dictionary['object_title'], $email_text);
        }
        $email_text = str_replace('{login_link}', $this->get_login_placeholder(), $email_text);
        $this->body = view('email.template', [
            'text' => $email_text,
            'title' => "New {$transaction_humanized} Confirmation"
        ]);
    }

    public function cancel_subscription($to, $dictionary = null, $subject = null)
    {
        $this->subject = $subject ? $subject : 'Subscription cancellation confirmation for ' . get_bloginfo('name') . '!';
        $email_text = $this->get_email_text('cancel_subscription');
        $this->to = $to;
        $this->body = view('email.template', [
            'text' =>  $email_text,
            'title' => 'Subscription Cancellation'
        ]);
    }

    public function forgot_password($to, $dictionary, $subject = null)
    {
        $this->subject = $subject ? $subject : 'Password Reset | ' . get_bloginfo('name');
        $email_text = $this->get_email_text('forgot_password');
        $profileURL = apply_filters('zype_url', 'profile');
        $placeholder = <<<HTML
            <a href="{$profileURL}/reset-password/{$dictionary['password_token']}/">{$profileURL}/reset-password/{$dictionary['password_token']}/</a>
HTML;
        $email_text = str_replace('{forgot_password_link}', $placeholder, $email_text);
        $this->to = $to;
        $this->body = view('email.template', [
            'text' =>  $email_text,
            'title' => 'Forgot Your Password?'
        ]);
    }

    public function send()
    {
        return wp_mail($this->to, $this->subject, $this->body, $this->headers);
    }

    private function get_email_text($type)
    {
        $email_text = $this->options['emails'][$type]['text'];
        $email_text = str_replace("\n", "<br />", $email_text);
        return $email_text;
    }

    private function get_login_placeholder()
    {
        $profileURL = apply_filters('zype_url', 'profile');
        $loginURL = apply_filters('zype_url', '') . '/sign-in/';
        $loginPlaceholder = <<<HTML
            <a href="{$profileURL}/">{$loginURL}</a>
HTML;
        return $loginPlaceholder;
    }
}

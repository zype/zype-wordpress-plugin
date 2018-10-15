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
        $emailText = $this->get_email_text('new_account');
        $profileURL = apply_filters('zype_url', 'profile');
        $loginURL = apply_filters('zype_url', '') . '/sign-in/';
        $placeholder = <<<HTML
            <a href="{$profileURL}/">{$loginURL}</a>
HTML;
        $emailText = str_replace('{login_link}', $placeholder, $emailText);
        $this->body = view('email.template', [
            'text' => $emailText,
            'title' => 'Thank you for signing up!'
        ]);
    }

    public function new_subscription($to, $dictionary = null, $subject = null)
    {
        $this->subject = $subject ? $subject : 'Thank you for subscribing to ' . get_bloginfo('name') . '!';
        $this->to = $to;
        $emailText = $this->get_email_text('new_subscription');
        $profileURL = apply_filters('zype_url', 'profile');
        $loginURL = apply_filters('zype_url', '') . '/sign-in/';
        $placeholder = <<<HTML
            <a href="{$profileURL}/">{$loginURL}</a>
HTML;
        $emailText = str_replace('{login_link}', $placeholder, $emailText);
        $this->body = view('email.template', [
            'text' => $emailText,
            'title' => 'New Subscription Confirmation'
        ]);
    }

    public function new_rental($to, $dictionary = null, $subject = null)
    {
        $this->subject = $subject ? $subject : 'Thank you for your rental on ' . get_bloginfo('name') . '!';
        $this->to = $to;
        $emailText = $this->get_email_text('new_rental');
        $videoPlaceholder = <<<HTML
            <a href="{$dictionary['videoUrl']}/">{$dictionary['videoTitle']}</a>
HTML;
        $profileURL = apply_filters('zype_url', 'profile');
        $loginURL = apply_filters('zype_url', '') . '/sign-in/';
        $loginPlaceholder = <<<HTML
            <a href="{$profileURL}/">{$loginURL}</a>
HTML;
        $emailText = str_replace('{video_name_link}', $videoPlaceholder, $emailText);
        $emailText = str_replace('{login_link}', $loginPlaceholder, $emailText);
        $this->body = view('email.template', [
            'text' => $emailText,
            'title' => 'New Rental Confirmation'
        ]);
    }

    public function cancel_subscription($to, $dictionary = null, $subject = null)
    {
        $this->subject = $subject ? $subject : 'Subscription cancellation confirmation for ' . get_bloginfo('name') . '!';
        $emailText = $this->get_email_text('cancel_subscription');
        $this->to = $to;
        $this->body = view('email.template', [
            'text' =>  $emailText,
            'title' => 'Subscription Cancellation'
        ]);
    }

    public function forgot_password($to, $dictionary, $subject = null)
    {
        $this->subject = $subject ? $subject : 'Password Reset | ' . get_bloginfo('name');
        $emailText = $this->get_email_text('forgot_password');
        $profileURL = apply_filters('zype_url', 'profile');
        $placeholder = <<<HTML
            <a href="{$profileURL}/reset-password/{$dictionary['password_token']}/">{$profileURL}/reset-password/{$dictionary['password_token']}/</a>
HTML;
        $emailText = str_replace('{forgot_password_link}', $placeholder, $emailText);
        $this->to = $to;
        $this->body = view('email.template', [
            'text' =>  $emailText,
            'title' => 'Forgot Your Password?'
        ]);
    }

    public function send()
    {
        return wp_mail($this->to, $this->subject, $this->body, $this->headers);
    }

    private function get_email_text($type)
    {
        $emailText = $this->options['emails'][$type]['text'];
        $emailText = str_replace("\n", "<br />", $emailText);
        return $emailText;
    }
}

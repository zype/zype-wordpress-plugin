<?php

namespace ZypeMedia\Services;

class Mailer {
    public function __construct()
    {
        $this->admin_email = get_option('admin_email');
        $this->headers     = [
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
        $this->to      = $to;
        $this->body = view('email.new_account', [
            'dictionary' => $dictionary
        ]);
    }

    public function new_subscription($to, $dictionary = null, $subject = null)
    {
        $this->subject = $subject ? $subject : 'Thank you for subscribing to ' . get_bloginfo('name') . '!';
        $this->to      = $to;
        $this->body = view('email.new_subscription', [
            'dictionary' => $dictionary
        ]);
    }

    public function new_rental($to, $dictionary = null, $subject = null)
    {
        $this->subject = $subject ? $subject : 'Thank you for your rental on ' . get_bloginfo('name') . '!';
        $this->to      = $to;
        $this->body = view('email.new_rental', [
            'dictionary' => $dictionary
        ]);
    }

    public function cancel_subscription($to, $dictionary = null, $subject = null)
    {
        $this->subject = $subject ? $subject : 'Subscription cancellation confirmation for ' . get_bloginfo('name') . '!';
        $this->to      = $to;
        $this->body = view('email.cancel_subscription', [
            'dictionary' => $dictionary
        ]);
    }

    public function forgot_password($to, $dictionary, $subject = null)
    {
        $this->subject = $subject ? $subject : 'Password Reset | ' . get_bloginfo('name');
        $this->to      = $to;
        $this->body = view('email.forgot_password', [
            'dictionary' => $dictionary
        ]);
    }

    public function send()
    {
        return wp_mail($this->to, $this->subject, $this->body, $this->headers);
    }
}

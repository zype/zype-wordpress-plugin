<?php

namespace ZypeMedia\Controllers\Consumer;

use ZypeMedia\Services\Braintree;
use ZypeMedia\Models\V2\Plan;

class Profile extends Base
{

    public function __construct()
    {
        parent::__construct();
    }

    public function profile()
    {
        if (!\Auth::logged_in()) {
            wp_redirect(home_url($this->options['auth_url']));
            exit;
        }

        $za = new \ZypeMedia\Services\Auth;
        $consumer_id = $za->get_consumer_id();
        $access_token = $za->get_access_token();
        $consumer = \Zype::get_consumer($consumer_id, $access_token);
        $braintreeId = $za->get_consumer_braintree_id();

        if ($braintreeId) {
            $braintree_token = (new Braintree)->generateBraintreeToken($braintreeId);
        }

        $title = ucfirst($this->options['profile_url']);

        print view('auth.profile', [
            'title' => $title,
            'options' => $this->options,
            'consumer' => $consumer
        ]);
        exit;
    }

    public function rss_feeds()
    {
        $za = new \ZypeMedia\Services\Auth;
        $rss_token = $za->get_rss_token();
        $rss_urls = [];
        $zype_rss_links = [];
        $rss_urls['default'] = get_zype_url($this->options['rss_url']) . '/' . $rss_token . '/';

        foreach ($this->options['categories'] as $category => $values) {
            if (is_array($values)) {
                foreach ($values as $value => $ops) {
                    if (isset($ops['url']) && !empty($ops['url'])) {
                        $url = $ops['url'];
                    } else {
                        $url = zype_to_permalink($category) . '/' . zype_to_permalink($value);
                    }

                    if (isset($ops['rss'])) {
                        $rss_urls[$category . '%%' . $value] = get_zype_category_url($category, $value) . '/' . $this->options['rss_url'] . '/' . $rss_token . '/';
                    }
                }
            }
        }

        foreach ($rss_urls as $index => $rss_link) {
            if (preg_match('/^https/', $rss_link)) {
                $zype_rss_links[$index] = [
                    'itunes' => str_replace('https', 'itpc', $rss_link),
                    'feed' => str_replace('https', 'feed', $rss_link),
                    'http' => $rss_link,
                ];

            } elseif (preg_match('/^http/', $rss_link)) {
                $zype_rss_links[$index] = [
                    'itunes' => str_replace('http', 'itpc', $rss_link),
                    'feed' => str_replace('http', 'feed', $rss_link),
                    'http' => $rss_link,
                ];
            }
        }

        $title = 'RSS Feeds';

        ob_start();
        $content = view('auth.rss_feeds', [
            'title' => $title,
            'zype_rss_links' => $zype_rss_links,
            'rss_urls' => $rss_urls
        ]);
        ob_end_clean();
        return $content;
    }

    public function change_password()
    {
        if (!\Auth::logged_in()) {
            wp_redirect(home_url($this->options['auth_url']));
            exit;
        }

        $title = 'Change Password';
        print view('auth.change_password', [
            'title' => $title
        ]);
        exit;
    }

    public function forgot_password($root_parent = null)
    {
        $title = 'Forgot Password';
        return view('auth.forgot_password', [
            'title' => $title,
            'root_parent' => $root_parent
        ]);
    }

    public function forgot_password_submit_ajax()
    {
        $this->forgot_password_submit(true);
    }

    public function forgot_password_submit($ajax = false)
    {
        if ($ajax) {
            $errors = array();
        }

        $email = $this->request->validate('email', ['email']);

        if ($email) {
            $consumer = \Zype::find_consumer_by_email($email);

            if ($consumer) {
                //set token on user
                $pw_token = bin2hex(openssl_random_pseudo_bytes(16));
                $update_consumer = \Zype::admin_update_consumer($consumer->_id, ['password_token' => $pw_token]);
                if ($update_consumer) {
                    //send email
                    $mailer = new \ZypeMedia\Services\Mailer;
                    $mail_res = $mailer->forgot_password($email, ['password_token' => $pw_token]);
                    if ($mail_res) {
                        zype_form_message('check', 'You should receive a password reset email shortly.');
                        $errors[] = 'You should receive a password reset email shortly.';
                    } else {
                        zype_form_message('times', 'An error occured when sending your password reset email. Please try again.');
                        $errors[] = 'An error occured when sending your password reset email. Please try again.';
                    }
                } else {
                    zype_form_message('times', 'An error occured when trying to update your password. Please try again.');
                    $errors[] = 'An error occured when trying to update your password. Please try again.';
                }
            } else {
                zype_form_message('times', 'We could not find your account. Please try again.');
                $errors[] = 'We could not find your account. Please try again.';
            }

        } else {
            zype_form_message('times', 'You must provide a valid email address.');
            $errors[] = 'You must provide a valid email address.';
        }

        $redirect = get_zype_url('profile') . '/forgot-password/';

        if ($ajax) {
            echo json_encode(array(
                'status' => !sizeof($errors) ? true : false,
                'errors' => $errors,
                'redirect' => $redirect
            ));
            exit();
        } else {
            wp_redirect($redirect);
            exit();
        }
    }

    public function reset_password($hash = '')
    {
        $zype_password_token = $hash;

        $zype_message = get_zype_form_message();

        $title = 'Reset Password';
        print view('auth.reset_password', [
            'title' => $title,
            'zype_password_token' => $zype_password_token,
            'zype_message' => $zype_message
        ]);
        exit;
    }

    public function reset_password_submit($hash = '')
    {
        $zype_password_token = $hash;

        if ($zype_password_token) {
            $email = $this->request->validate('email', ['email']);
            $password_token = $this->request->validate('password_token', ['textfield']);
            $new_password = $this->validate_password($this->request->validate('password', ['textfield']), $this->request->validate('password_confirmation', ['textfield']));

            if ($email && $password_token) {
                if ($new_password) {
                    //find user by password token
                    $consumer = \Zype::find_consumer_by_email_and_password_token($email, $password_token);

                    if ($consumer) {
                        $update_consumer = \Zype::admin_update_consumer($consumer->_id, [
                            'password_token' => null,
                            'password' => $new_password,
                        ]);

                        if ($update_consumer) {
                            zype_form_message('check', 'Your password has been successfully changed.');
                            $auther = new \ZypeMedia\Services\Auth();

                            $username = $email;

                            $auther->login($username, $new_password);
                            wp_redirect(home_url($this->options['profile_url']));
                            exit();
                        } else {
                            zype_form_message('times', 'There was a problem updating your account. Please request a new reset token and try again.');
                            wp_redirect(get_zype_url('profile') . '/forgot-password/');
                            exit();
                        }
                    } else {
                        zype_form_message('times', 'Account could not be found or reset token invalid. Please try again. If this error persists, please <a href="' . get_zype_url('profile') . '/forgot-password/">request a new reset token</a>.');
                        wp_redirect(get_zype_url('profile') . '/reset-password/' . $zype_password_token . '/');
                        exit();
                    }
                } else {
                    zype_form_message('times', 'Passwords do not match, or new password does not meet the crieria. Passwords must contain at least 8 characters with at least one each of an uppercase letter, a lowercase letter, and a number. Please try again.');
                    wp_redirect(get_zype_url('profile') . '/reset-password/' . $zype_password_token . '/');
                    exit();
                }
            } else {
                zype_form_message('times', 'Email address, new password, and new password confirmation are required.');
                wp_redirect(get_zype_url('profile') . '/reset-password/' . $zype_password_token . '/');
                exit();
            }
        } else {
            zype_form_message('times', 'Password reset token missing. Please request a new one.');
            wp_redirect(get_zype_url('profile') . '/forgot-password/');
            exit();
        }
        exit();
    }

    private function validate_password($password, $password_confirmation)
    {
        if ($password != $password_confirmation) {
            return false;
        }
        if (strlen($password) < 8) {
            return false;
        }
        if (!preg_match('/[A-Z]/', $password)) {
            return false;
        }
        if (!preg_match('/[a-z]/', $password)) {
            return false;
        }
        if (!preg_match('/[0-9]/', $password)) {
            return false;
        }

        return $password;
    }

    public function subscription()
    {
        if (!\Auth::logged_in()) {
            wp_redirect(home_url($this->options['auth_url']));
            exit;
        }

        $zd = [];
        $za = new \ZypeMedia\Services\Auth;
        $zd['consumer_id'] = $za->get_consumer_id();
        $zd['email'] = $za->get_email();
        $zd['stripe_pk'] = $this->options['stripe_pk'];

        $zd['subscription'] = \Zype::get_consumer_subscription($zd['consumer_id']);
        if (!empty($zd['subscription'])) {
            $zd['plans'] = Plan::all([
                'id[]' => $this->options['subscribe_select']
            ], false);
            $zd['current_plan'] = \Zype::get_plan($zd['subscription']->plan_id);

            if (!empty($zd['subscription']->stripe_id)) {
                $zd['cancel_at_period_end'] = isset($zd['subscription']->cancel_at_period_end) ? $zd['subscription']->cancel_at_period_end : false;

                $full_sub = $sub = $zd['subscription'];

                $zd['plan_start'] = $sub->start_at;
                $zd['plan_end'] = $sub->current_period_end_at;
                $zd['sub_status'] = ucwords($sub->status);
            }
        } else {
            $zd['subscription'] = false;
        }

        $title = 'Subscription';

        print view('auth.subscription', [
            'title' => $title,
            'options' => $this->options,
            'zd' => $zd,
            'za' => $za
        ]);

        exit;
    }

    public function change_subscription()
    {
        $za = new \ZypeMedia\Services\Auth;
        $consumer_id = $za->get_consumer_id();
        $email = $za->get_email();

        $subscription = \Zype::get_consumer_subscription($consumer_id);

        $subscription_id = $this->request->validate('subscription_id', ['textfield']);
        $new_plan_id = $this->request->validate('new_plan_id', ['textfield']);
        $new_plan = Plan::find($new_plan_id);
        $res = false;

        $new_plan_type = empty($new_plan->stripe_id) && !empty($new_plan->braintree_id) ? 'Braintree' : 'Stripe';
        $current_plan_type = empty($subscription->stripe_id) && !empty($subscription->braintree_id) ? 'Braintree' : 'Stripe';

        if ($subscription && $subscription_id && ($subscription->_id == $subscription_id)) {
            if($new_plan_type == $current_plan_type) {
                $res = \Zype::change_subscription($subscription->_id, [
                    'plan_id' => $new_plan_id
                ]);
            }
            else {
                $error = "In order to update your subscription to the plan you currently have selected, you must cancel your current subscription first.";
            }
        }

        if ($res) {
            $za->sync_cookie();
            zype_flash_message('success', 'Your subscription has been successfully updated.');
        } else {
            $error = $error?: 'An error has occured. Please try again.';
            zype_flash_message('error', $error);
        }
        wp_redirect(get_zype_url('profile') . '/subscription/');
        die();
    }

    public function cancel_subscription()
    {
        $za = new \ZypeMedia\Services\Auth;
        $consumer_id = $za->get_consumer_id();
        $email = $za->get_email();

        $subscription = \Zype::get_consumer_subscription($consumer_id);

        $zd = [];
        $zd['subscription'] = $subscription;

        if ($this->request->validate('subscription_id', ['textfield'])) {
            $this->do_cancel_subscription($subscription);
        }

        $title = 'Cancel Subscription';

        print view('auth.cancel_subscription', [
            'title' => $title,
            'zd' => $zd,
            'subscription' => $subscription,
            'consumer_id' => $consumer_id,
            'email' => $email,
        ]);

        exit;
    }

    private function do_cancel_subscription($subscription)
    {
        $za = new \ZypeMedia\Services\Auth;
        $consumer_id = $za->get_consumer_id();
        $email = $za->get_email();

        $subscription_id = $this->request->validate('subscription_id', ['textfield']);

        $res = false;
        if ($subscription && $subscription_id && ($subscription->_id == $subscription_id)) {
            try {
                $res = \Zype::cancel_subscription($subscription->_id);
            } catch (\Exception $e) {

            }
        }

        $mailer = new \ZypeMedia\Services\Mailer;
        $mailer->cancel_subscription($email);

        $za->sync_cookie();
        zype_flash_message('success', 'Your subscription has been successfully canceled.');

        wp_redirect(get_zype_url('profile'));
        die();
    }

    public function change_credit_card()
    {
        $title = 'Change Credit Card';
        $stripe_pk = $this->options['stripe_pk'];
        print view('auth.change_credit_card', [
            'title' => $title,
            'stripe_pk' => $stripe_pk
        ]);
        exit;
    }

    public function change_credit_card_submit()
    {
        $za = new \ZypeMedia\Services\Auth;
        $consumer_id = $za->get_consumer_id();

        if ($consumer_id) {
            if ($this->request->validate('payment_method_nonce', ['textfield'])) {
                wp_redirect(get_zype_url('profile'));
                die();
            }

            $res = \Zype::change_card($consumer_id, $this->request->validate('stripe_card_token', ['textfield']));

            if ($res) {
                $za->sync_cookie();
                zype_flash_message('success', 'Your card has been successfully updated.');
            } else {
                zype_flash_message('error', 'There was an error updating your account information. Please try again.');
            }
        } else {
            zype_flash_message('error', 'An error has occured. Please try again.');
        }

        wp_redirect(get_zype_url('profile') . '/change-credit-card/');
        die();
    }

    public function device_link()
    {
        if (!\Auth::logged_in()) {
            wp_redirect(home_url($this->options['auth_url']));
            exit;
        }

        $title = 'Link Device';

        print view('auth.device_link', [
            'title' => $title,
            'options' => $this->options,
        ]);
        exit;
    }

    public function device_link_submit()
    {
        $za = new \ZypeMedia\Services\Auth;
        $consumer_id = $za->get_consumer_id();
        $pin = $this->request->validate('pin', ['textfield']);

        if ($consumer_id && $pin) {
            $res = \Zype::link_device($consumer_id, $pin);

            if ($res) {
                zype_flash_message('success', 'Your device has been successfully linked. Enjoy the show.');
            } else {
                zype_flash_message('error', 'There was an error linking your device. Please try again. If you continue to see this error you may need to request a new pin.');
            }
        } else {
            zype_flash_message('error', 'There was an error linking your device. Please try again. If you continue to see this error you may need to request a new pin.');
        }

        wp_redirect(get_zype_url('device_link') . '/');
        die();
    }
}

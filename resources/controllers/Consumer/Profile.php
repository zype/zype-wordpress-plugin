<?php

namespace ZypeMedia\Controllers\Consumer;

use \ZypeMedia\Services\Braintree;
use Themosis\Route\BaseController;
use Themosis\Facades\View;
use Themosis\Facades\Config;
use Themosis\Facades\Input;

class Profile extends BaseController
{
    public function profile()
    {
        if (!\Auth::logged_in()) {
            wp_redirect(home_url());
            exit;
        }

        global $consumer;
        global $braintree_token;

        $za           = new \ZypeMedia\Services\Auth;
        $consumer_id  = $za->get_consumer_id();
        $access_token = $za->get_access_token();
        $consumer     = \Zype::get_consumer($consumer_id, $access_token);
        $braintreeId     = $za->get_consumer_braintree_id();
        $braintree_token = (new Braintree)->generateBraintreeToken($braintreeId);

        $title    = ucfirst(Config::get('zype.profile_url'));

        print view('auth.profile', [
            'title' => $title,
            'consumer' => $consumer
        ]);
        exit;
    }

    public function rss_feeds()
    {
        global $zype_rss_links;
        $za                  = new \ZypeMedia\Services\Auth;
        $rss_token           = $za->get_rss_token();
        $rss_urls            = [];
        $zype_rss_links      = [];
        $rss_urls['default'] = get_zype_url(Config::get('zype.rss_url')) . '/' . $rss_token . '/';

        foreach (Config::get('zype.categories') as $category => $values) {
            if (is_array($values)) {
                foreach ($values as $value => $ops) {
                    if (isset($ops['url']) && !empty($ops['url'])) {
                        $url = $ops['url'];
                    } else {
                        $url = zype_to_permalink($category) . '/' . zype_to_permalink($value);
                    }

                    if (isset($ops['rss'])) {
                        $rss_urls[$category . '%%' . $value] = get_zype_category_url($category, $value) . '/' . Config::get('zype.rss_url') . '/' . $rss_token . '/';
                    }
                }
            }
        }

        foreach ($rss_urls as $index => $rss_link) {
            if (preg_match('/^https/', $rss_link)) {
                $zype_rss_links[$index] = [
                    'itunes' => str_replace('https', 'itpc', $rss_link),
                    'feed'   => str_replace('https', 'feed', $rss_link),
                    'http'   => $rss_link,
                ];

            } elseif (preg_match('/^http/', $rss_link)) {
                $zype_rss_links[$index] = [
                    'itunes' => str_replace('http', 'itpc', $rss_link),
                    'feed'   => str_replace('http', 'feed', $rss_link),
                    'http'   => $rss_link,
                ];
            }
        }

        $title    = 'RSS Feeds';

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
        $title    = 'Change Password';
        print view('auth.change_password', [
            'title' => $title
        ]);
        exit;
    }

    public function forgot_password()
    {
        $title = 'Forgot Password';
        return view('auth.forgot_password', [
            'title' => $title
        ]);
    }

	public function forgot_password_submit_ajax() {
		$this->forgot_password_submit(true);
    }

    public function forgot_password_submit($ajax = false) {
        if ($ajax) {
            $errors = array();
        }

        if (isset($_POST['email']) && $_POST['email'] != '') {
            $email = strtolower(filter_var(filter_var($_POST['email'], FILTER_SANITIZE_EMAIL), FILTER_VALIDATE_EMAIL));

            $consumer = \Zype::find_consumer_by_email($email);
            if ($consumer) {
                //set token on user
                $pw_token        = bin2hex(openssl_random_pseudo_bytes(16));
                $update_consumer = \Zype::admin_update_consumer($consumer->_id, ['password_token' => $pw_token]);
                if ($update_consumer) {
                    //send email
                    $mailer = new \ZypeMedia\Services\Mailer;
                    $mailer->forgot_password($email, ['password_token' => $pw_token]);
                    $mail_res = $mailer->send();
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
        global $zype_password_token;
        $zype_password_token = $hash;

        global $zype_message;
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
        if ($zype_password_token != '') {

            if (!empty($_POST['email']) && !empty($_POST['password_token']) && !empty($_POST['password']) && !empty($_POST['password_confirmation'])) {
                $new_password = $this->validate_password($_POST['password'], $_POST['password_confirmation']);
                $email        = trim(strtolower(filter_var($_POST['email'], FILTER_SANITIZE_STRING)));
                if ($new_password) {
                    //find user by password token
                    $password_token = filter_var($_POST['password_token'], FILTER_SANITIZE_STRING);
                    $consumer       = \Zype::find_consumer_by_email_and_password_token($email, $password_token);
                    if ($consumer) {
                        $update_consumer = \Zype::admin_update_consumer($consumer->_id, [
                            'password_token' => null,
                            'password'       => $new_password,
                        ]);
                        if ($update_consumer) {
                            zype_form_message('check', 'Your password has been successfully changed.');
                            wp_redirect(get_zype_url('login'));
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

    public function subscription()
    {
        global $zd;

        $zd                = [];
        $za                = new \ZypeMedia\Services\Auth;
        $zd['consumer_id'] = $za->get_consumer_id();
        $zd['email']       = $za->get_email();
        $zd['stripe_pk']   = Config::get('zype.stripe_pk');

        $zd['subscription'] = \Zype::get_consumer_subscription($zd['consumer_id']);
        if (!empty($zd['subscription'])) {
            $zd['plans']        = \Zype::get_all_plans();
            $zd['current_plan'] = \Zype::get_plan($zd['subscription']->plan_id);

            if (!empty($zd['subscription']->stripe_id)) {
                $zd['cancel_at_period_end'] = isset($zd['subscription']->cancel_at_period_end) ? $zd['subscription']->cancel_at_period_end : false;

                $full_sub = $sub = $zd['subscription'];

                $zd['plan_start'] = $sub->start_at;
                $zd['plan_end']   = $sub->current_period_end_at;
                $zd['sub_status'] = ucwords($sub->status);
            }
        } else {
            $zd['subscription'] = false;
        }
// var_dump($zd);
// exit;
        $title = 'Subscription';

        print view('auth.subscription', [
            'title' => $title,
            'zd' => $zd,
            'za' => $za
        ]);

        exit;
    }

    public function change_subscription()
    {
        $za          = new \ZypeMedia\Services\Auth;
        $consumer_id = $za->get_consumer_id();
        $email       = $za->get_email();

        $subscription = \Zype::get_consumer_subscription($consumer_id);

        $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);
        $res  = false;

        if ($subscription && isset($post['subscription_id']) && ($subscription->_id == $post['subscription_id'])) {
            $res = \Zype::change_subscription($subscription->_id, [
                'plan_id' => $post['new_plan_id']
            ]);
        }

        if ($res) {
            $za->sync_cookie();
            zype_flash_message('success', 'Your subscription has been successfully updated.');
        } else {
            zype_flash_message('error', 'An error has occured. Please try again.');
        }
        wp_redirect(get_zype_url('profile') . '/subscription/');
        die();
    }

    public function cancel_subscription()
    {
        $za          = new \ZypeMedia\Services\Auth;
        $consumer_id = $za->get_consumer_id();
        $email       = $za->get_email();

        $subscription = \Zype::get_consumer_subscription($consumer_id);

        global $zd;
        $zd                 = [];
        $zd['subscription'] = $subscription;

        if (isset($_POST['subscription_id'])) {
            $this->do_cancel_subscription($subscription);
        }

        $title    = 'Cancel Subscription';

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
        $za          = new \ZypeMedia\Services\Auth;
        $consumer_id = $za->get_consumer_id();
        $email       = $za->get_email();

        $post = filter_var_array($_POST, FILTER_SANITIZE_STRING);

        $res = false;
        if ($subscription && isset($post['subscription_id']) && ($subscription->_id == $post['subscription_id'])) {
            try {
                $res = \Zype::cancel_subscription($subscription->_id);
            } catch (\Exception $e) {

            }
        }

        $mailer = new \ZypeMedia\Services\Mailer;
        $mailer->cancel_subscription($email);
        $mail_res = $mailer->send();

        $za->sync_cookie();
        zype_flash_message('success', 'Your subscription has been successfully canceled.');

        wp_redirect(get_zype_url('profile'));
        die();
    }

    public function change_card()
    {
        $za          = new \ZypeMedia\Services\Auth;
        $consumer_id = $za->get_consumer_id();
        $post        = filter_var_array($_POST, FILTER_SANITIZE_STRING);

        if (isset($post['consumer_id']) && $consumer_id == $post['consumer_id']) {
            $this->processBraintreeChangeCardRequest($post);

            $res = \Zype::change_card($consumer_id, $post['stripe_card_token']);

            if ($res) {
                $za->sync_cookie();
                zype_flash_message('success', 'Your card has been successfully updated.');
            } else {
                zype_flash_message('error', 'There was an error updating your account information. Please try again.');
            }
        } else {
            zype_flash_message('error', 'An error has occured. Please try again.');
        }

        wp_redirect(get_zype_url('profile') . '/subscription/');
        die();
    }

    public function processBraintreeChangeCardRequest($post)
    {
        if (isset($post['payment_method_nonce'])) {
            wp_redirect(get_zype_url('profile'));
            die();
        }
    }

    public function device_link()
    {
        $title    = 'Link Device';

        print view('auth.device_link', [
            'title' => $title
        ]);
        exit;
    }

    public function device_link_submit()
    {
        $za          = new \ZypeMedia\Services\Auth;
        $consumer_id = $za->get_consumer_id();
        $post        = filter_var_array($_POST, FILTER_SANITIZE_STRING);

        if (isset($consumer_id) && isset($post['pin'])) {
            $res = \Zype::link_device($consumer_id, $post['pin']);
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

    private function validate_password($password, $password_confirmation)
    {
        $password              = filter_var($password, FILTER_SANITIZE_STRING);
        $password_confirmation = filter_var($password_confirmation, FILTER_SANITIZE_STRING);

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
}

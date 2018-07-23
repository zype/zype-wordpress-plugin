<?php

namespace ZypeMedia\Controllers\Consumer;

use Themosis\Facades\Config;

class Auth extends Base
{
    public function __construct()
    {
        parent::__construct();
        $this->form_message = null;
    }

    public function login($ajax = false)
    {
        if ($this->request->method() == 'POST') {
            $username = strtolower($this->request->validate('username', ['textfield']));

            if ($username) {
                zype_form_fields([['f_name' => 'username',
                    'f_value' => $username,
                ],
                ]);
            }
            $this->login_submit($ajax);
        }

        $this->title = "Login";

        if($ajax) {
            return view('auth.login_ajax');
        }
        else {
            return view('auth.login');
        }
    }

    public function auth_page()
    {
        if (\Auth::logged_in()) {
            wp_redirect(home_url(Config::get('zype.profile_url')));
            exit;
        }

        if ($this->request->method() == 'POST') {
            $username = strtolower($this->request->validate('username', ['textfield']));
            if ($username) {
                zype_form_fields([
                    [
                        'f_name' => 'username',
                        'f_value' => $username,
                    ],
                ]);
            }
            $this->login_submit();
        }

        echo view('auth.pre_auth', ['title' => 'Auth']);
        echo view('auth.login');
        echo view('auth.post_auth');

        exit;
    }

    public function login_submit_ajax($redirect = true) {
        $this->login_submit(true, $redirect);
    }

    public function login_submit($ajax = false, $redirect = true)
    {
        if ($ajax) {
            $errors = array();
        }
        if($redirect) {
            $redirect = home_url(Config::get('zype.profile_url'));
        }

        $auther = new \ZypeMedia\Services\Auth();

        $username = strtolower($this->request->validate('username', ['textfield']));
        $password = $this->request->validate('password', ['textfield']);

        if ($username && $password) {
            $remember_me = $this->request->validate('remember_me', ['bool']);
            $response = $auther->login($username, $password, $remember_me);
            $is_subscribed = $auther->subscriber();
            if (!$response) {
                if ($ajax) {
                    $errors[] = 'Username or password invalid.';
                } else {
                    $this->form_message = zype_flash_message('times', 'Username or password invalid.');
                }
            }
        } else {
            if ($ajax)
                $errors[] = 'Please provide an email address and password.';
            else
                $this->form_message = zype_flash_message('times', 'Please provide an email address and password.');
        }

        if($ajax)
        {
            echo wp_json_encode(array(
                'status' => !sizeof($errors) ? true : false,
                'is_subscribed' => $is_subscribed,
                'errors' => $errors,
                'redirect' => $redirect
            ));
            exit();
        }
    }

    public function signup($ajax = false)
    {
        global $zype_message;

        if ($this->request->method() == 'POST') {
            global $zype_signup_name;
            global $zype_signup_email;

            $zype_signup_email = $this->request->validate('email', ['email']);
            $zype_signup_name = $this->request->validate('name', ['textfield']);

            $this->signup_submit($ajax);
        }

        $zype_message = $this->form_message;

        $terms_link = false;
        $terms_link_opt = trim(Config::get('zype.terms_url'));
        if ($terms_link_opt) {
            if (parse_url($terms_link_opt, PHP_URL_SCHEME))
                $terms_link = $terms_link_opt;
            else
                $terms_link = get_home_url() . '/' . ltrim($terms_link_opt, '/');
        }

        ob_start();
        $view = $ajax ? 'auth.signup_ajax' : 'auth.signup';

        $content = view($view, [
            'zype_message' => $zype_message,
            'terms_link' => $terms_link
        ]);
        ob_end_clean();
        return $content;
    }

    public function signup_submit($ajax = false)
    {
        if ($ajax) {
            $errors = array();
        }

        if ($this->request->get('email') && $this->request->get('password') && $this->request->get('name')) {
            $confirmPassword = $this->request->validate('confirm_password', ['textfield']) ?: $this->request->validate('password', ['textfield']);

            if ($this->validate_password($this->request->validate('password', ['textfield']), $confirmPassword)) {
                $name = $this->request->validate('name', ['textfield']);
                $email = $this->request->validate('email', ['email']);
                $password = $this->request->validate('password', ['textfield']);

                $new_user = \Zype::create_consumer([
                    'email' => $email,
                    'password' => $password,
                    'name' => $name,
                    'sex' => 'male',
                    'updates' => true,
                    'terms' => true,
                ]);

                $auther = new \ZypeMedia\Services\Auth();

                if ($new_user) {
                    if ($auther->login($email, $password)) {
                        //send email
                        $mailer = new \ZypeMedia\Services\Mailer;
                        $mailer->new_account($email);
                        $mail_res = $mailer->send();
                    } else {
                        $auther->logout();
                        $this->form_message = zype_flash_message('times', 'An error occured during account authorization. Please try again.');
                    }
                } else {
                    $auther->logout();

                    $message = 'There was an error creating your account. This email address may already be in use. Please try again. If you have forgotten your password, <a href="' . get_permalink() . '?zype_auth_type=forgot" class="zype_auth_markup" data-type="forgot">click here.</a>';

                    if ($ajax)
                        $errors[] = $message;
                    else
                        $this->form_message = zype_flash_message('times', $message);
                }
            } else {
                $message = 'Your password must be at least 8 characters.';
                if ($ajax)
                    $errors[] = $message;
                else
                    $this->form_message = zype_flash_message('times', $message);
            }
        } else {
            $message = 'Please fill out all required fields.';

            if ($ajax)
                $errors[] = $message;
            else
                $this->form_message = zype_flash_message('times', $message);
        }

        if ($ajax) {
            echo json_encode(array(
                'status' => !sizeof($errors) ? true : false,
                'errors' => $errors,
            ));
            exit();
        }
    }

    private function validate_password($password, $password_confirmation)
    {
        $password = filter_var($password, FILTER_SANITIZE_STRING);
        $password_confirmation = filter_var($password_confirmation, FILTER_SANITIZE_STRING);

        if ($password != $password_confirmation) {
            return false;
        }

        if (mb_strlen($password) < 8) {
            return false;
        }

        return $password;
    }

    public function signup_submit_ajax()
    {
        $this->signup_submit(true);
    }

    public function template($template)
    {
        $find = [
            'zype/auth/' . $this->template . '.php',
            'auth/' . $this->template . '.php',
        ];

        if ($locatedFile = $this->locate_file($find)) {
            $template = $locatedFile;
        }

        return $template;
    }

}

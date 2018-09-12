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

    public function login($ajax = false, $root_parent = null)
    {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['username'])) {
                $zype_user_email = trim(strtolower(filter_var($_POST['username'], FILTER_SANITIZE_STRING)));
                zype_form_fields([['f_name'  => 'username',
                                'f_value' => $zype_user_email,
                                ],
                ]);
            }
            $this->login_submit($ajax);
        }

        $this->title = "Login";

        $view = $ajax ? 'auth.login_ajax' : 'auth.login';

        if($ajax) {
            $redirect_url = Config::get('zype.sub_short_code_redirect_url');
            if(isset($redirect_url) && !empty($redirect_url) && (strpos($redirect_url, 'http') !== 0)){
                $redirect_url = home_url($redirect_url);
            }
            return view($view, ['redirect_url' => $redirect_url, 'root_parent' => $root_parent]);
        }
        else {
            return view($view, ['root_parent' => $root_parent]);
        }
    }

    public function auth_page()
    {
        if (\Auth::logged_in()) {
            wp_redirect(home_url(Config::get('zype.profile_url')));
            exit;
        }

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if (isset($_POST['username'])) {
                $zype_user_email = trim(strtolower(filter_var($_POST['username'], FILTER_SANITIZE_STRING)));
                zype_form_fields([['f_name'  => 'username',
                                'f_value' => $zype_user_email,
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
        if($ajax) {
            $errors = array();
        }
        if($redirect) {
            $redirect = home_url(Config::get('zype.profile_url'));
        }

        $auther = new \ZypeMedia\Services\Auth();

        if (isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] != '' && $_POST['password'] != '') {
            $username    = trim(strtolower(filter_var($_POST['username'], FILTER_SANITIZE_STRING)));
            $password    = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
            $remember_me = isset($_POST['remember_me']) ? $_POST['remember_me'] : true;
            $response = $auther->login($username, $password, $remember_me);
            $is_subscribed = $auther->subscriber();
            if (!$response) {
                if($ajax) {
                    $errors[] = 'Username or password invalid.';
                }
                else {
                    $this->form_message = zype_flash_message('times', 'Username or password invalid.');
                }
            }
        } else {
            if($ajax)
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

    public function signup($ajax = false, $root_parent = null)
    {
        global $zype_message;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            global $zype_signup_name;
            global $zype_signup_email;

            if (isset($_POST['email'])) {
                $zype_signup_email = trim(strtolower(filter_var($_POST['email'], FILTER_SANITIZE_STRING)));
            }
            if (isset($_POST['name'])) {
                $zype_signup_name = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
            }

            $this->signup_submit($ajax);
        }

        $zype_message   = $this->form_message;

        $terms_link = false;
        $terms_link_opt = trim(Config::get('zype.terms_url'));
        if ($terms_link_opt) {
            if (parse_url($terms_link_opt, PHP_URL_SCHEME))
              $terms_link = $terms_link_opt;
            else
              $terms_link = get_home_url().'/'.ltrim($terms_link_opt, '/');
        }

        ob_start();
        $view = $ajax ? 'auth.signup_ajax' : 'auth.signup';

        $content = view($view, [
            'zype_message' => $zype_message,
            'terms_link' => $terms_link,
            'root_parent' => $root_parent
        ]);
        ob_end_clean();
        return $content;
    }

    public function signup_submit_ajax() {
        $this->signup_submit(true);
    }

    public function signup_submit($ajax = false)
    {
        if($ajax) {
            $errors = array();
        }

        if (!empty($_POST['email']) && !empty($_POST['password']) && !empty($_POST['name'])) {
            $confirmPassword = isset($_POST['confirm_password']) ? $_POST['confirm_password'] : $_POST['password'];

            if ($this->validate_password($_POST['password'], $confirmPassword)) {
                $name     = trim(filter_var($_POST['name'], FILTER_SANITIZE_STRING));
                $email    = trim(strtolower(filter_var($_POST['email'], FILTER_SANITIZE_STRING)));
                $password = filter_var($_POST['password'], FILTER_SANITIZE_STRING);

                $new_user = \Zype::create_consumer([
                    'email'    => $email,
                    'password' => $password,
                    'name'     => $name,
                    'sex'      => 'male',
                    'updates'  => true,
                    'terms'    => true,
                ]);

                $auther   = new \ZypeMedia\Services\Auth();

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

                    if($ajax)
                        $errors[] = $message;
                    else
                        $this->form_message = zype_flash_message('times', $message);
                }
            } else {
                $message = 'Your password must be at least 8 characters.';
                if($ajax)
                    $errors[] = $message;
                else
                    $this->form_message = zype_flash_message('times', $message);
            }
        } else {
            $message = 'Please fill out all required fields.';

            if($ajax)
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
        $password              = filter_var($password, FILTER_SANITIZE_STRING);
        $password_confirmation = filter_var($password_confirmation, FILTER_SANITIZE_STRING);

        if ($password != $password_confirmation) {
            return false;
        }

        if (mb_strlen($password) < 8) {
            return false;
        }

        return $password;
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

<?php

namespace ZypeMedia\Controllers\Consumer;

class Auth extends Base
{
    public function __construct()
    {
        parent::__construct();
        $this->form_message = null;
    }

    public function login()
    {
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

        $this->title = "Login";

        return view('auth.login');
    }

    public function login_submit_ajax() {
        $this->login_submit(true);
    }
    
    public function login_submit($ajax = false)
    {
        $redirect_location = get_zype_url('profile');
        
        if($ajax) {
            $errors = array();
        }
        
        $auther = new \ZypeMedia\Services\Auth();

        if (isset($_POST['username']) && isset($_POST['password']) && $_POST['username'] != '' && $_POST['password'] != '') {
            $username    = trim(strtolower(filter_var($_POST['username'], FILTER_SANITIZE_STRING)));
            $password    = filter_var($_POST['password'], FILTER_SANITIZE_STRING);
            $remember_me = isset($_POST['remember_me']) ? $_POST['remember_me'] : true;

            if ($auther->login($username, $password, $remember_me)) {
                if (isset($_REQUEST['redirect_to'])) {
                    $redirect_location = filter_var($_REQUEST['redirect_to'], FILTER_SANITIZE_URL);
                }

                if (!$ajax) {
                    wp_redirect($redirect_location);
                    exit();
                }
            } else {
                if($ajax)
                    $errors[] = 'Username or password invalid.';
                else
                    $this->form_message = zype_flash_message('times', 'Username or password invalid.');
            }
        } else {
            if($ajax)
                $errors[] = 'Please provide an email address and password.';
            else
                $this->form_message = zype_flash_message('times', 'Please provide an email address and password.');
        }
        
        if($ajax)
        {
            echo json_encode(array(
                'status' => !sizeof($errors) ? true : false,
                'errors' => $errors,
                'redirect' => $redirect_location
            ));
            exit();
        }
    }

    public function signup()
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

            $this->signup_submit();
        }

        $zype_message   = $this->form_message;

        ob_start();
        $content = view('auth.signup', [
            'zype_message' => $zype_message
        ]);
        ob_end_clean();
        return $content;
    }

    public function signup_submit_ajax() {
        $this->signup_submit(true);
    }
    
    public function signup_submit($ajax = false)
    {
        $redirect_location = get_zype_url('profile');
        
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
                        
                        if (isset($_REQUEST['redirect_to'])) {
                            $redirect_location = filter_var($_REQUEST['redirect_to'], FILTER_SANITIZE_URL);
                        }

                        if(!$ajax)
                        {
                            wp_redirect($redirect_location);
                            exit();
                        }
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
                $message = 'Your password must be at least 8 characters and contain one each of an uppercase letter, a lowercase letter, and a number.';
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
                'redirect' => $redirect_location
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

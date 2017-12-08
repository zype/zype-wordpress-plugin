<?php

namespace ZypeMedia\Controllers\Consumer;

class ProfileAJAX extends BaseAJAX
{
    public function init()
    {
        add_action('wp_ajax_nopriv_zype_update_profile', [
            $this,
            'update_profile',
        ]);
        add_action('wp_ajax_zype_update_profile', [
            $this,
            'update_profile',
        ]);

        add_action('wp_ajax_nopriv_zype_update_password', [
            $this,
            'update_password',
        ]);
        add_action('wp_ajax_zype_update_password', [
            $this,
            'update_password',
        ]);
    }

    public function update_profile()
    {
        $za           = new \ZypeMedia\Services\Auth;
        $consumer_id  = $za->get_consumer_id();
        $access_token = $za->get_access_token();

        $fields = $this->form_vars([
            'name',
            'email',
            'email_confirmation',
        ]);

        if (isset($fields['email_confirmation']) && $fields['email_confirmation'] != $fields['email']) {
            http_response_code(400);
            $res = [
                'result'  => 'failure',
                'message' => 'Email confirmation is not correct.',
            ];
            echo json_encode($res);
            wp_die();
        }

        $updated = \Zype::update_consumer($consumer_id, $access_token, $fields);

        if ($updated) {
            $res = [
                'result'  => 'success',
                'message' => 'Profile successfully updated.',
            ];
        } else {
            http_response_code(400);
            $res = [
                'result'  => 'failure',
                'message' => 'An error has occured. Please try again.',
            ];
        }
        (new \ZypeMedia\Services\Auth)->sync_cookie();
        echo json_encode($res);
        wp_die();
    }

    public function update_password()
    {
        $za           = new \ZypeMedia\Services\Auth;
        $consumer_id  = $za->get_consumer_id();
        $access_token = $za->get_access_token();
        $email        = $za->get_email();

        $updated      = false;
        $auth         = false;
        $new_password = false;

        if (isset($_POST['current_password'])) {
            $current_password = filter_var($_POST['current_password'], FILTER_SANITIZE_STRING);
            $auth             = (new \ZypeMedia\Services\Auth)->login($email, $current_password);
        }

        if ($auth && isset($_POST['new_password']) && isset($_POST['new_password_confirmation'])) {
            $new_password = $this->validate_password($_POST['new_password'], $_POST['new_password_confirmation']);
            $access_token = $za->get_access_token();
        }

        if ($auth && $new_password) {
            $fields['password'] = $new_password;
            $updated            = \Zype::update_consumer($consumer_id, $access_token, $fields);
        }

        if ($updated) {
            $res = [
                'result'  => 'success',
                'message' => 'Password successfully changed.',
            ];
        } else {
            http_response_code(400);
            $res = [
                'result'  => 'failure',
                'message' => 'Invalid password, new password does not match, or new password does not meet crieria. Please try again.',
            ];
        }
        echo json_encode($res);
        wp_die();
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

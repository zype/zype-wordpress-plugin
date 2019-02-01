<?php

namespace ZypeMedia\Controllers\Admin;

class EmailSettings extends \ZypeMedia\Controllers\Controller
{
    public function index()
    {
        echo view('admin.email_settings', [
            'options' => $this->options
        ]);

        wp_die();
    }

    public function submit()
    {
        $errors = $this->update_email_options();
        if(count($errors) > 0) {
            foreach($errors as $error) {
                zype_wp_admin_message('error', $error);
            }
        }
        else {
            $this->update_options();
            zype_wp_admin_message('updated', 'Changes successfully saved!');
        }
        wp_redirect($this->request->validateServer('HTTP_REFERER', ['textfield']));
        exit();
    }

    private function update_email_options()
    {
        $email_types = array_keys($this->options['emails']);
        $errors = [];
        foreach($email_types as $email_type) {
            $email_text = $this->request->validate($email_type, ['textarea'], $this->options['emails'][$email_type]['text']);
            $required_placeholders = $this->options['emails'][$email_type]['required'];
            if(count($required_placeholders) > 0) {
                foreach($required_placeholders as $required) {
                    $pos = strpos($email_text, $required);
                    if($pos == false) {
                        $errors[] = join(' ', ['The email', $email_type, 'is missing', $required]);
                    }
                    else {
                        $this->options['emails'][$email_type]['text'] = $email_text;
                    }
                }
            }
            else {
                $this->options['emails'][$email_type]['text'] = $email_text;
            }
            $email_enabled = $this->request->validate($email_type . '_enabled', ['bool']);
            $this->options['emails'][$email_type]['enabled'] = $email_enabled;
        }
        return $errors;
    }
}

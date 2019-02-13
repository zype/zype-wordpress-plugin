<?php

namespace ZypeMedia\Controllers\Admin;

class CustomizeUi extends \ZypeMedia\Controllers\Controller
{

    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        echo view('admin.customize_ui', [
            'options' => $this->options
        ]);

        wp_die();
    }

    public function submit()
    {
        if (wp_verify_nonce($this->request->validate('_wpnonce'), 'zype_customize_ui')) {
            $form = $this->request->validateAll(['textfield']);
            if($form['theme'] === 'false') {
                $this->options['colors']['user']['modal'] = array_replace($this->options['colors']['user']['modal'], $form['modal']);
                $this->options['colors']['user']['playlist'] = array_replace($this->options['colors']['user']['playlist'], $form['playlist']);
            }
            else {
                $this->options['colors']['user'] = $this->options['colors'][$form['theme']];
            }
            $this->update_options();
            zype_wp_admin_message('updated', 'Changes successfully saved!');
        } else {
            zype_wp_admin_message('error', 'Something has gone wrong.');
        }
        wp_redirect($this->request->validateServer('HTTP_REFERER', ['textfield']));
        exit;
    }
}

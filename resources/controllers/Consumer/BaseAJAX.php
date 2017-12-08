<?php

namespace ZypeMedia\Controllers\Consumer;

class BaseAJAX
{
    public function __construct()
    {
        global $zype_wp_options;
        $this->options = $zype_wp_options;
    }

    protected function form_vars($names)
    {
        $fields = [];
        foreach ($names as $name) {
            if (isset($_REQUEST[$name])) {
                $fields[$name] = trim(filter_var($_REQUEST[$name], FILTER_SANITIZE_STRING));
            }
        }

        return $fields;
    }

    protected function renderAjax($data = null)
    {
        echo json_encode($data);
        wp_die();
    }
}
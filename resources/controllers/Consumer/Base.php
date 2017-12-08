<?php

namespace ZypeMedia\Controllers\Consumer;

use Themosis\Route\BaseController;

class Base extends BaseController {
    public $template = null;

    public function __construct()
    {
        global $zype_wp_options;
        $this->options = $zype_wp_options;

        $this->search();
        $this->sort();

        add_action('template_include', [
            $this,
            'template',
        ]);
        add_action('wp_title', [
            $this,
            'wp_title',
        ], 0, 2);
        add_action('wp_head', [
            $this,
            'wp_head',
        ], 0, 2);
        add_filter('aioseop_canonical_url', [
            $this,
            'canonical_url',
        ]);
        add_filter('body_class', [
            $this,
            'add_body_class'
        ]);

    }

    public function init()
    {

    }

    public function template($template)
    {
        $templatePath = locate_template(['zype/' . $template]);
        if (!$templatePath) {
            $templatePath = $this->plugin_template_path() . '/' . $template;
        }

        return $templatePath;
    }

    public function locate_file($find)
    {
        $template = locate_template($find);

        if (!$template) {
            foreach ((array)$find as $file) {
                if (file_exists($this->plugin_template_path() . '/' . $file)) {
                    $template = $this->plugin_template_path() . '/' . $file;
                    break;
                }
            }
        }

        return $template;
    }

    public function plugin_path()
    {
        return plugin_dir_path(__FILE__) . '../..';
    }

    public function plugin_template_path()
    {
        return $this->plugin_path() . '/views';
    }

    public function wp_title($title, $sep)
    {
        if (isset($this->title) && isset($this->page) && $this->page != '') {
            $title = $this->title . ' - Page ' . $this->page . ' ' . $sep . ' ' . get_bloginfo('name');
        } elseif (isset($this->title)) {
            $title = $this->title . ' ' . $sep . ' ' . get_bloginfo('name');
        } else {
            $title = get_bloginfo('name');
        }

        return stripslashes($title);
    }

    public function add_body_class($classes)
    {
        global $zype_search;

        $classes[] = $this->template;
        if ($zype_search['is_search'] === true) {
            $classes[] = 'page-search';
        }
        if (isset($this->title) && $this->template != 'plans' && $this->template != 'single') {
            $string = preg_replace("/[^a-z0-9_\s-]/", "", strtolower($this->title));
            $classes[] = str_replace(' ', '-', $string);
        }

        if (isset($this->page)) {
            $classes[] = strtolower($this->page);
        }
        if (isset($this->category_key)) {
            $classes[] = 'zype-category-' . strtolower($this->category_key);
            $classes[] = 'zype-category';
        }

        $data = explode('\\', get_called_class());
        $classes[] = strtolower(end($data));

        return $classes;
    }

    public function wp_head()
    {}

    protected function form_vars($names)
    {
        $fields = [];
        foreach ($names as $name) {
            if (isset($_REQUEST[$name])) {
                $fields[$name] = filter_var($_REQUEST[$name], FILTER_SANITIZE_STRING);
            }
        }

        return $fields;
    }

    public function search()
    {
        global $zype_search;
        $zype_search              = [];
        $zype_search['is_search'] = false;
        if (isset($_GET['search'])) {
            $zype_search['term']      = filter_var($_GET['search'], FILTER_SANITIZE_STRING);
            $zype_search['is_search'] = true;
        }
    }

    public function sort()
    {
        global $zype_sort;
        $zype_sort              = [];
        $zype_sort['is_sorted'] = false;
        if (isset($_GET['sort'])) {
            $zype_sort['order']     = filter_var($_GET['sort'], FILTER_SANITIZE_STRING);
            $zype_sort['is_sorted'] = true;
        }
    }

    public function canonical_url($url)
    {
        $url = site_url() . filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_STRING);

        return $url;
    }

    protected function get_braintree_nonce($request)
    {
        $braintree_nonce = null;
        if (isset($request['payment_method_nonce'])) {
            $braintree_nonce = $request['payment_method_nonce'];
        }
        elseif (isset($request['braintree_payment_nonce'])) {
            $braintree_nonce = $request['braintree_payment_nonce'];
        }

        return $braintree_nonce;
    }

}

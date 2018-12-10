<?php

namespace ZypeMedia\Controllers\Consumer;

use Themosis\Facades\Action;
use ZypeMedia\Controllers\Controller;

class Base extends Controller
{
    public $template = null;
    public $zype_search = [];

    public function __construct()
    {
        parent::__construct();

        $this->search();
        $this->sort();

        Action::add('template_include', [$this, 'template']);
        Action::add('wp_title', [$this, 'wp_title'], 0, 2);
        Action::add('aioseop_canonical_url', [$this, 'canonical_url']);
        Action::add('body_class', [$this, 'add_body_class']);
    }

    public function search()
    {
        $this->zype_search['is_search'] = false;

        if ($search = $this->request->validate('search', ['textfield'])) {
            $this->zype_search['term'] = $search;
            $this->zype_search['is_search'] = true;
        }
    }

    public function sort()
    {
        $zype_sort = [];
        $zype_sort['is_sorted'] = false;

        if ($sort = $this->request->validate('sort', ['textfield'])) {
            $zype_sort['order'] = $sort;
            $zype_sort['is_sorted'] = true;
        }
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

    public function plugin_template_path()
    {
        return $this->plugin_path() . '/views';
    }

    public function plugin_path()
    {
        return plugin_dir_path(__FILE__) . '../..';
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
        $classes[] = $this->template;
        if ($this->zype_search['is_search'] === true) {
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

    public function canonical_url()
    {
        $url = site_url() . $this->request->validateServer('REQUEST_URI', ['textfield']);

        return $url;
    }

    protected function form_vars($names)
    {
        $fields = [];
        foreach ($names as $name) {
            if ($this->request->get($name)) {
                $fields[$name] = $this->request->validate($name, ['textfield']);
            }
        }

        return $fields;
    }

    protected function get_braintree_nonce($request)
    {
        $braintree_nonce = null;
        if (isset($request['payment_method_nonce'])) {
            $braintree_nonce = $request['payment_method_nonce'];
        } elseif (isset($request['braintree_payment_nonce'])) {
            $braintree_nonce = $request['braintree_payment_nonce'];
        }

        return $braintree_nonce;
    }

}

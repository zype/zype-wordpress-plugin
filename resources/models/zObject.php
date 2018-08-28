<?php

namespace ZypeMedia\Models;

use Themosis\Facades\Config;

class zObject extends Base
{
    public $suppress_search = false;

    public function __construct($type)
    {
        parent::__construct();
        $this->options = Config::get('zype');
        $this->type = $type;
    }

    public function find($id)
    {
        $this->single = \Zype::get_zobject($this->type, $id);
        if ($this->single) {
            $this->modify_one();
        }
    }

    private function modify_one()
    {
        $this->single->permalink = $this->generate_permalink($this->single);
        $this->single->thumbnail_url = $this->add_thumbnail_url($this->single);
        $this->single->excerpt = $this->add_excerpt($this->single);
    }

    private function generate_permalink($zobject)
    {
        return \site_url() . '/' . $this->type . '/' . $zobject->friendly_title;
    }

    private function add_thumbnail_url($video)
    {
        if (isset($video->pictures) && isset($video->pictures[0]) && isset($video->pictures[0]->url)) {
            return $video->pictures[0]->url;
        }
        return \get_template_directory_uri() . '/images/placeholder.png';
    }

    private function add_excerpt($zobject)
    {
        $excerpt = $zobject->description;
        $original = $excerpt;
        $excerpt = implode(' ', array_slice(explode(' ', $excerpt), 0, 30));

        if ($excerpt != $original) {
            $excerpt = $excerpt . '... <a href="' . $zobject->permalink . '/">More</a>';
        }
        return $excerpt;
    }

    public function all($params = [])
    {
        $per_page = isset($params['per_page']) ? $params['per_page'] : null;
        $page = isset($params['page']) ? $params['page'] : null;
        $sort = isset($params['sort']) ? $params['sort'] : null;

        $res = \Zype::get_zobjects($this->type, $page, $per_page, $sort, $this->suppress_search);
        if ($res) {
            $this->collection = $res->response;
            $this->pagination = $res->pagination;
            $this->modify_all();
        } else {
            $this->collection = false;
            $this->pagination = false;
        }
    }

    private function modify_all()
    {
        foreach ($this->collection as &$zobject) {
            $zobject->permalink = $this->generate_permalink($zobject);
            $zobject->thumbnail_url = $this->add_thumbnail_url($zobject);
            $zobject->excerpt = $this->add_excerpt($zobject);
        }
    }

    public function all_by($by, $params = [])
    {
        $per_page = isset($params['per_page']) ? $params['per_page'] : null;
        $page = isset($params['page']) ? $params['page'] : null;
        $sort = isset($params['sort']) ? $params['sort'] : null;

        $res = \Zype::get_zobjects_by($this->type, $by, $page, $per_page, $sort);
        if ($res) {
            $this->collection = $res->response;
            $this->pagination = $res->pagination;
            $this->modify_all();
        } else {
            $this->collection = false;
            $this->pagination = false;
        }
    }
}

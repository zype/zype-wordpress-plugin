<?php

namespace ZypeMedia\Controllers\Consumer;

class zObjects extends Base
{
    public function __construct()
    {
        $this->type     = \Input::get('zype_zobject_type');
        $this->page     = \Input::get('zype_paged');
        $this->per_page = get_option('posts_per_page');
        parent::__construct();
    }

    public function index()
    {
        $type = $this->type . 's';
        global $$type;
        global $zype_pagination;

        $zm = new \ZypeMedia\Models\zObject($this->type);
        $zm->all([
            'per_page' => $this->per_page,
            'page'     => $this->page,
        ]);
        $type = $zm->collection;

        $zype_pagination = (new \ZypeMedia\Models\Pagination($zm->pagination));

        $title    = ucfirst($this->type);

        return view("{$this->type}_index", [
            'type' => $type,
            'title' => $title,
            'zype_pagination' => $zype_pagination
        ]);
    }

    public function single()
    {
        $type = $this->type;
        global $$type;
        global $videos;

        $id = \Input::get('zype_zobject_id');
        $zm = new \ZypeMedia\Models\zObject($this->type);
        $zm->find($id);
        $type = $zm->single;

        $vm = new \ZypeMedia\Models\Video;
        $vm->all_by(['zobject_id' => $type->_id]);
        $videos = $vm->collection;

        $title = $type->title;

        return view("{$this->type}_single", [
            'type' => $type,
            'title' => $title,
            'videos' => $videos
        ]);
    }
}

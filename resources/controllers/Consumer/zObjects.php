<?php

namespace ZypeMedia\Controllers\Consumer;

class zObjects extends Base
{
    public function __construct()
    {
        parent::__construct();
        $this->type = $this->request->validate('zype_zobject_type', ['textfield']);
        $this->page = $this->request->validate('zype_paged', ['textfield']);
        $this->per_page = get_option('posts_per_page');
    }

    public function index()
    {
        $type = $this->type . 's';
        $zm = new \ZypeMedia\Models\zObject($this->type);
        $zm->all([
            'per_page' => $this->per_page,
            'page' => $this->page,
        ]);
        $type = $zm->collection;

        $zype_pagination = (new \ZypeMedia\Models\Pagination($zm->pagination));

        $title = ucfirst($this->type);

        return view("{$this->type}_index", [
            'type' => $type,
            'title' => $title,
            'zype_pagination' => $zype_pagination
        ]);
    }

    public function single()
    {
        $type = $this->type;

        $id = $this->request->validate('zype_zobject_id', ['textfield']);
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

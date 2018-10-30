<?php

namespace ZypeMedia\Controllers\Consumer;

class Library extends Base
{
    public $videos;
    public $page;
    public $per_page;

    public function __construct()
    {
        parent::__construct();
        $za = new \ZypeMedia\Services\Auth;
        $consumer_id = $za->get_consumer_id();        
        $videos = \Zype::get_consumer_entitled_videos($consumer_id);
        $this->videos = array();
        foreach ($videos as $v) {
            if (!isset($this->videos[$v->video_id])) {
                $this->videos[$v->video_id] = $v;
            }
        }
        $this->page = $this->request->validate('zype_paged', ['textfield'], 1);
        $this->per_page = 200;
    }

    public function index()
    {
        $category = self::$category_val;
        $zype_detail_links = self::$detail_links;

        $vm = new \ZypeMedia\Models\Video;
        $vm->all_by([
            'category' => [
                self::$category_key => stripslashes(self::$category_val)
            ]
        ]);

        $videos = $vm->collection;

        $zype_pagination = new \ZypeMedia\Models\Pagination($vm->pagination);

        $title = self::$category_val;

        return view('category.index', [
            'videos' => $videos,
            'zype_pagination' => $zype_pagination,
            'title' => $title,
            'category' => $category,
            'zype_detail_links' => $zype_detail_links
        ]);
    }

    public function categories_list()
    {
        $categoriesConfig = Config::get('zype.categories') ?: [];
        $categories = \Zype::get_all_categories();

        $categoryValues = [];
        foreach ($categories as $category) {
            $categoryValues[] = $category->title;
        }

        return view('category.categories_list', [
            'categories' => $categories,
            'categoryValues' => $categoryValues,
            'categoriesConfig' => $categoriesConfig
        ]);
    }
}

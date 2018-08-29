<?php

namespace ZypeMedia\Controllers\Consumer;

use Themosis\Facades\Config;

class Category extends Base
{
    public static $category_key;
    public static $category_val;
    public static $detail_links;
    public static $page;
    public static $per_page;

    public function __construct()
    {
        parent::__construct();

        $request_uri = $this->request->validateServer('REQUEST_URI', ['textfield']);
        $categories = array_values(array_filter(explode('/', $request_uri)));
        self::$category_key = $categories[0];
        self::$category_val = $categories[1];
        self::$detail_links = $this->request->validate('zype_category_detail', ['textfield'], 1);
        self::$page = $this->request->validate('zype_paged', ['textfield'], 1);
        self::$per_page = 200;
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

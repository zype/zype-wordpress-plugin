<?php

namespace ZypeMedia\Controllers\Consumer;

use Themosis\Facades\Config;

class Gridscreen extends Base
{
    public static $content = array();
    public static $subcontent = array();
    public static $help = array();

    public function __construct()
    {
        parent::__construct();
    }

    public function index($adm = null, $parent_id = false)
    {
        $per_page = 5;

        if ($parent_id && $this->request->get('zype_parent')) {
            $parent_id = $this->request->validate('zype_parent', ['textfield'], 0);
        }

        if (!$parent_id) {
            $parent_id = Config::get('zype.grid_screen_parent');
            $items = -1;
        }

        $app_key = Config::get('zype.app_key');
        $get_all = $this->request->validate('zype_get_all', ['num'], 0);

        $parent_playlist = \Zype::get_playlist($parent_id);
        $items = !empty($parent_playlist->playlist_item_count)? $parent_playlist->playlist_item_count: -1;

        if (!$items && $this->request->validate('zype_items', ['num'], 0)) {
            $items = $this->request->validate('zype_items', ['num'], 0);
        }

        if ($get_all == 2) {
            if ($items == 0) {
                self::$content = \Zype::get_playlists_by([
                    'active' => true,
                    'parent_id' => $parent_id
                ], 1, 500, 'priority', 'asc');
            } elseif ($items > 0) {
                self::$content = \Zype::get_playlist_videos($parent_id);
            }

        }

        if ($get_all == 0) {
            if ($items == -1) { //home
                self::$content = \Zype::get_playlists_by([
                    'active' => true,
                    'parent_id' => $parent_id
                ], 1, 500, 'priority', 'asc');
            } elseif ($items == 0) { //playlist with playlists
                self::$content = \Zype::get_playlists_by([
                    'active' => true,
                    'parent_id' => $parent_id
                ], 1, 500, 'priority', 'asc');
            } elseif ($items > 0) { //playlist with videos
                self::$content = \Zype::get_playlist_videos($parent_id);
            }
        }

        $content = self::$content;

        $page = $this->request->validate('zype_str', ['textfield'], 1);
        if ($get_all == 1 || $get_all == 2) {
            if ($page == 'last') {
                if (count($content) % $per_page == 0) {
                    $page = count($content) / $per_page;
                } else {
                    $page = intval(count($content) / $per_page) + 1;
                }
                $pages = $page;
            }

            for ($n = ($page - 1) * $per_page; $n < $page * $per_page and !empty($content[$n]); $n++) {
                self::$help[] = $content[$n];
            }

            $content = self::$help;
        }

        if (is_array($content)) {
            foreach ($content as $cont) {
                self::get_subcontent($cont->_id, !empty($cont->playlist_item_count) ? $cont->playlist_item_count : 0);
            }
        }

        $subcontent = self::$subcontent;

        if (is_array($content)) {
            foreach ($content as $cont) {
                if (empty($cont->playlist_type) || !$cont->playlist_type) {
                    $n = 0;
                    $cont->title_url = $cont->title;
                    while (isset($cont->title_url[$n])) {
                        if (!preg_match('/[a-zA-Z0-9\-]/', $cont->title_url[$n])) {
                            $cont->title_url[$n] = '-';
                        }
                        $n++;
                    }
                }
            }
        }

        if (is_array($subcontent)) {
            foreach ($subcontent as $sub) {
                if (empty($sub->playlist_type) || !$sub->playlist_type) {
                    $n = 0;
                    $sub->title_url = $sub->title;
                    while (isset($sub->title_url[$n])) {
                        if (!preg_match('/[a-zA-Z0-9\-]/', $sub->title_url[$n])) {
                            $sub->title_url[$n] = '-';
                        }
                        $n++;
                    }
                }
            }
        }

        $title = ucfirst(Config::get('zype.grid_screen_url'));

        if ($adm == true) {
            return (['subcontent' => $subcontent]);
        } else {
            $get_all = $this->request->validate('zype_get_all', ['num'], 0);
            $page = $this->request->validate('zype_str', ['num'], 1);
            $zype_items = $this->request->validate('zype_items', ['num'], 0);

            if ($get_all != 0 && $get_all != 2) {
                return "can't load page";
            }

            $pagination = Config::get('zype.playlist_pagination', true);

            return view('grid_screen_index', [
                'request' => $this->request,
                'pagination' => $pagination,
                'parent_id' => $parent_id,
                'get_all' => $get_all,
                'zype_items' => $zype_items,
                'page' => $page,
                'per_page' => $per_page,
                'title' => $title,
                'content' => is_array($content) ? $content : array(),
                'subcontent' => is_array($subcontent) ? $subcontent : array(),
                'parent_playlist' => $parent_playlist
            ]);
        }
    }

    public function get_subcontent($parent_id, $item)
    {
        $app_key = Config::get('zype.app_key');
        $content = self::$content;

        if ($item == 0) {
            $data = \Zype::get_playlists_by([
                'active' => true,
                'parent_id' => $parent_id
            ], 1, 500, 'priority', 'asc');
        } elseif ($item > 0) {
            $data = \Zype::get_playlist_videos($parent_id);
        }

        if (is_array($data)) {
            foreach ($data as $dat) {
                $dat->parent_id = $parent_id;
                self::$subcontent[] = $dat;
            }
        }
    }
}

<?php

namespace ZypeMedia\Controllers\Consumer;

use Themosis\Facades\Config;

class Gridscreen extends Base
{
    public static $content = array();
    public static $subcontent = array();
    public static $help = array();
    const PER_PAGE = 5;

    public function __construct()
    {
        parent::__construct();
    }

    public function index($parent_id = false)
    {
        $pagination = $this->request->validate('pagination', ['bool'], false);
        $title = ucfirst($this->options['grid_screen_url']);

        if ($parent_id && $this->request->get('zype_parent')) {
            $parent_id = $this->request->validate('zype_parent', ['textfield'], 0);
        }
        $parent_playlist = \ZypeMedia\Models\V2\Playlist::find($parent_id);

        $videos_count = $parent_playlist->playlist_item_count;
        $content = $this->get_content($parent_id, $pagination, $videos_count);
        $subcontent = [];
        if ($videos_count === 0 && is_array($content) && !$pagination) {
            foreach ($content as $playlist) {
                $subcontent[$playlist->_id] = $this->get_content($playlist->_id, false, $playlist->playlist_item_count);
            }
        }

        $page = $this->request->validate('zype_str', ['num'], 1);
        $playlist_pagination_enabled = $this->options['playlist_pagination'];

        return view('grid_screen_index', [
            'request' => $this->request,
            'playlist_pagination_enabled' => $playlist_pagination_enabled,
            'parent_id' => $parent_id,
            'pagination' => $pagination,
            'items_count' => $videos_count,
            'page' => $page,
            'per_page' => self::PER_PAGE,
            'title' => $title,
            'content' => is_array($content) ? $content : array(),
            'subcontent' => is_array($subcontent) ? $subcontent : array(),
            'parent_playlist' => $parent_playlist
        ]);
    }

    private function get_content($parent_id, $pagination, $videos_count)
    {
        $page = $this->request->validate('zype_str', ['textfield'], 1);
        $content = [];
        // If $videos_count === 0, then we should search for children playlists
        if($videos_count === 0) {
            $content = \ZypeMedia\Models\V2\Playlist::all([
                'active' => true,
                'parent_id' => $parent_id,
                'order' => 'asc',
                'sort' => 'priority'
            ], false);
        }
        // If $videos_count !== 0, then we should search for videos inside the playlist
        else {
            if($pagination) {
                $content = \ZypeMedia\Models\V2\Playlist::videos([$parent_id, ['per_page' => self::PER_PAGE, 'page' => $page]])['collection'];
            }
            else {
                $content = \ZypeMedia\Models\V2\Playlist::videos($parent_id, false);
            }
        }
        return $content;
    }
}

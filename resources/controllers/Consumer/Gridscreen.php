<?php

namespace ZypeMedia\Controllers\Consumer;

use Themosis\Facades\View;
use Themosis\Facades\Input;
use Themosis\Facades\Config;

class Gridscreen extends Base {
	public static $content = array();
	public static $subcontent = array();
	public static $help = array();
	public static $parent_id = 0;
	
	public function __construct() {
		parent::__construct();
	}
	
	public static function index($adm = null) {
		global $parent_id;
		global $content;
		global $subcontent;
		global $pages;
		global $video_url;
		global $items;

		$per_page = 5;

		self::$parent_id = Input::get('zype_parent', 0 );

		if ($parent_id && !self::$parent_id) {
			self::$parent_id = $parent_id;
		}

		if (Input::get('zype_items', 0) && !$items) {
			$items = Input::get('zype_items', 0);
		}

		if (!self::$parent_id) {
			self::$parent_id = Config::get('zype.grid_screen_parent');
			$items=-1;
		}

		$parent_id = self::$parent_id;
		$app_key = Config::get('zype.app_key'); 
		$get_all = Input::get('zype_get_all', 0);

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

		$page = Input::get('zype_str', 1);

		if ($get_all == 1 || $get_all == 2) {
			if ($page == 'last') {
				if (count($content)%$per_page==0) {
					$page = count($content)/$per_page;
				} else {
					$page = intval(count($content)/$per_page)+1;
				}
				$pages = $page;
			}

			for ($n=($page-1)*$per_page; $n<$page*$per_page and !empty($content[$n]); $n++) {
				self::$help[]=$content[$n];
			}

			$content = self::$help;
		}
		
		if ( is_array ($content) ) {
			foreach ($content as $cont) {
				self::get_subcontent($cont->_id, !empty($cont->playlist_item_count)? $cont->playlist_item_count: 0);
			}
		}
		
		$subcontent = self::$subcontent;
		
		if ( is_array ($content) ) {
			foreach ($content as $cont) {
				if (empty($cont->playlist_type) || !$cont->playlist_type) {
					$n=0;
					$cont->title_url = $cont->title;
					while (isset($cont->title_url[$n])) {
						if (!preg_match('/[a-zA-Z0-9\-]/', $cont->title_url[$n])){
							$cont->title_url[$n] = '-';
						}
						$n++;
					}
				}
			}
		}
		
		if ( is_array ($subcontent) ) {
			foreach ($subcontent as $sub) {
				if (empty($sub->playlist_type) || !$sub->playlist_type) {
					$n=0;
					$sub->title_url = $sub->title;
					while(isset($sub->title_url[$n])) {
						if (!preg_match('/[a-zA-Z0-9\-]/', $sub->title_url[$n])){
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
		}
		else{
		return view('grid_screen_index', [
			'per_page' => $per_page,
			'title' => $title,
			'content' => is_array ($content) ? $content : array(),
			'subcontent' => is_array ($subcontent) ? $subcontent : array()
		]);
		}
	}

	public static function get_subcontent($parent_id, $item) {
		$app_key = Config::get('zype.app_key');
		$content = self::$content;

		if ($item==0){
			$data = \Zype::get_playlists_by([
				'active' => true,
				'parent_id' => $parent_id
			], 1, 500, 'priority', 'asc');
		} elseif ($item > 0) {
			$data = \Zype::get_playlist_videos($parent_id);
		}
		
		if ( is_array ($data) ) {
			foreach ($data as $dat) {
				$dat->parent_id = $parent_id;
				self::$subcontent[] = $dat;
			}
		}
	}
}
?>
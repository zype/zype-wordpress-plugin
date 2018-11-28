<?php

namespace ZypeMedia\Controllers\Consumer;

use ZypeMedia\Models\Pagination;
use ZypeMedia\Models\Video;
use ZypeMedia\Models\V2\Consumer\VideoEntitlement;
use ZypeMedia\Services\Access;

class Videos extends Base
{
    const RENTAL_URL = 'rental';
    const PASS_URL = 'pass';

    public static $page;
    public static $per_page;
    public $title;

    public function __construct()
    {
        parent::__construct();
        self::$page = $this->request->validate('zype_paged', ['textfield']);
        self::$per_page = get_option('posts_per_page');
    }

    public function index()
    {
        $vm = new Video;
        $vm->all([
            'per_page' => self::$per_page,
            'page' => self::$page,
        ]);
        $videos = $vm->collection;

        $zype_pagination = new Pagination($vm->pagination);

        $title = ucfirst(self::$options['video_url']);

        ob_start();
        require plugin_dir_path(__FILE__) . '../../views/video_index.php';
        $data = ob_get_contents();
        ob_end_clean();

        return $data;
    }

    public function single($id = false, $view = 'full')
    {
        return $this->show_video($id, $view);
    }

    public function single_in_playlist($video_id, $playlist_id, $view = 'full')
    {
        return $this->show_video($video_id, $view, $playlist_id);
    }

    public function entitled($page_number = 1)
    {
        $videos = [];
        $pagination = [];
        if(\Auth::logged_in()) {
            $entitled_videos = $this->get_entitled_videos($page_number);
            $videos = $entitled_videos['videos'];
            $pagination = $entitled_videos['pagination'];
        }
        $my_library_container_id = 'my-library-' . (time() * rand(1, 1000000));
        $login_shortcode = ajax_shortcode('zype_auth', [
            'zype_auth_type' => 'login',
            'root_parent' => $my_library_container_id,
            'ajax' => true,
            'redirect_url' => get_permalink(),
            'show_plans' => false
        ]);
        $sign_up_shortcode = ajax_shortcode('zype_signup', [
            'root_parent' => $my_library_container_id,
            'ajax' => true,
            'redirect_url' => get_permalink(),
            'show_plans' => false
        ]);
        $forgot_password_shortcode = ajax_shortcode('zype_forgot', [
            'root_parent' => $my_library_container_id
        ]);
        return view('auth/videos_pagination', [
            'videos'                    => $videos,
            'pagination'                => $this->options['my_library']['pagination'] ? $pagination : false,
            'sign_in_text'              => $this->options['my_library']['sign_in_text'],
            'my_library_container_id'   => $my_library_container_id,
            'shortcodes'    => [
                'login'         => $login_shortcode,
                'sign_up'       => $sign_up_shortcode,
                'forgot_pass'   => $forgot_password_shortcode
            ]
        ]);
    }

    private function get_entitled_videos($page_number = 1)
    {
        $za = new \ZypeMedia\Services\Auth;
        $consumer_id = $za->get_consumer_id();
        $access_token = $za->get_access_token();
        $purchase_video_entitlement = VideoEntitlement::all($access_token, ['per_page' => 500, 'transaction_type' => 'purchase'], false);
        $rental_video_entitlement = VideoEntitlement::all($access_token, ['per_page' => 500, 'transaction_type' => 'rental'], false);
        $video_entitlements = array_merge($purchase_video_entitlement, $rental_video_entitlement);
        $video_ids = array_map(function($video_entitlement) { return $video_entitlement->video_id; }, $video_entitlements);
        $video_ids = array_unique($video_ids);
        $videos = [];
        $pagination = [];
        if(count($video_ids) >= 1) {
            $sort_key = $this->options['my_library']['sort'];
            $sort_options = $this->options['my_library_sort_options'][$sort_key];
            $vm = new Video;
            $per_page = $this->options['my_library']['pagination'] ? 20 : 500;
            $vm->all([
                'page' => $page_number,
                'per_page' => $per_page,
                'id[]' => $video_ids,
                'sort' => $sort_key,
                'order' => $sort_options["order"]
            ], $this->options['my_library']['pagination']);
            $pagination = new \ZypeMedia\Models\Pagination($vm->pagination);
            $videos = $vm->collection;
        }
        return [
            'videos'     => $videos,
            'pagination' => $pagination
        ];
    }

    private function show_video($video_id, $view, $playlist_id = '')
    {
        $vm = new Video;
        $vm->find($video_id);
        $video = $vm->single;
        $redirect_url = $this->canonical_url();

        if (!$video) {
            return 'Nothing found';
        }

        $title = $video->title;

        return view('video_single', [
            'video' => $video,
            'playlist_id' => $playlist_id,
            'view' => $view,
            'title' => $title,
            'redirect_url' => $redirect_url
        ]);
    }
}

<?php

namespace ZypeMedia\Controllers\Consumer;

use ZypeMedia\Services\Access;
use ZypeMedia\Models\Video;
use ZypeMedia\Models\Pagination;
use ZypeMedia\Models\Transaction;
use \Input;

class Videos extends Base
{
    const RENTAL_URL = 'rental';
    const PASS_URL   = 'pass';

    public $title;
    public static $page;
    public static $per_page;

    public function __construct()
    {
        self::$page     = \Input::get('zype_paged');
        self::$per_page = get_option('posts_per_page');
        parent::__construct();
    }

    public static function index()
    {
        global $videos;
        global $zype_pagination;

        $vm = new Video;
        $vm->all([
            'per_page' => self::$per_page,
            'page'     => self::$page,
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

    public static function single($id = false, $view = 'full')
    {
        global $video;
        global $is_subscriber;
        global $hasUserAccessToVideo;

        $is_subscriber = (new \ZypeMedia\Services\Auth)->subscriber();

        if(!$id) {
            $id = Input::get('zype_video_id');
        }

        $vm = new Video;
        $vm->find($id);
        $video = $vm->single;

        if (!$video) {
            return 'Nothing found';
        }

        if ($video->pass_required){
            $video->payment_url_segment = Transaction::TYPE_PASS_PLAN;
        } elseif ($video->rental_required) {
            $video->payment_url_segment = Transaction::TYPE_RENTAL;
        }

        $hasUserAccessToVideo = (new Access())->checkUserVideoAccess($id);

        $title = $video->title;

        return view('video_single', [
            'video' => $video,
            'view' => $view,
            'title' => $title,
            'hasUserAccessToVideo' => $hasUserAccessToVideo,
            'is_subscriber' => $is_subscriber
        ]);
    }
}

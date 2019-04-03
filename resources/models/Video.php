<?php

namespace ZypeMedia\Models;

use ZypeMedia\Models\V2\Account;

class Video extends Base
{
    public $suppress_search = false;

    public function __construct($use_admin = false)
    {
        parent::__construct();
        $this->options = get_option(ZYPE_WP_OPTIONS);
        $this->use_admin = $use_admin;
    }

    public function find($id)
    {
        $this->single = \Zype::get_video($id);
        if ($this->single) {
            $this->modify_one();
        }
    }

    public function transaction_required()
    {
        return $this->single->subscription_required || $this->single->pass_required ||
        $this->single->rental_required || $this->single->purchase_required;
    }

    private function modify_one()
    {
        $this->single->permalink = $this->generate_permalink($this->single);
        $this->single->thumbnail_url = $this->add_thumbnail_url($this->single);
        $this->single->excerpt = $this->add_excerpt($this->single);
        $this->single->transaction_required = $this->transaction_required();
    }

    private function generate_permalink($video)
    {
        return \site_url() . '/' . $this->options['video_url'] . '/' . $this->title_to_permalink($video->title) . '/' . $video->_id;
    }

    private function title_to_permalink($str, $replace = array(), $delimiter = '-')
    {
        if (!empty($replace)) {
            $str = str_replace((array)$replace, ' ', $str);
        }

        $clean = zype_url_slug($str);
        $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
        $clean = strtolower(trim($clean, '-'));
        $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

        return $clean;
    }

    private function add_thumbnail_url($video)
    {
        $res = \get_template_directory_uri() . '/images/placeholder.jpg';

        if (isset($video->thumbnails) && is_array($video->thumbnails) && isset($video->thumbnails[0])) {
            $res = $video->thumbnails[0]->url;

            foreach ($video->thumbnails as $thumb) {
                if ($thumb->aspect_ratio == 1.78) {
                    $res = $thumb->url;
                    if ($thumb->width > 640) {
                        break;
                    }
                }
            }
        }

        return $res;
    }

    private function add_excerpt($video)
    {
        if (isset($video->short_description) && $video->short_description != '') {
            $excerpt = $video->short_description;
        } else {
            $excerpt = $video->description;
        }

        $original = $excerpt;

        $excerpt = implode(' ', array_slice(explode(' ', $excerpt), 0, 50));

        if (strlen($excerpt) > 400) {
            $excerpt = substr($excerpt, 0, 400);
        }

        if ($excerpt != $original) {
            $excerpt = $excerpt . '... <a href="' . $video->permalink . '/">More</a>';
        }

        return $excerpt;
    }

    public function all($params = [], $with_pagination = false)
    {
        $res = \Zype::get_videos($params, $this->suppress_search);

        if ($res) {
            $this->collection = $res->response;
            $this->pagination = $res->pagination;
            if (!$with_pagination && $this->pagination && $this->pagination->pages > 1) {
                for ($page = $this->pagination->current + 1; $page <= $this->pagination->pages; $page++) {
                  $params['page'] = $page;
                  $res = \Zype::get_videos($params, $this->suppress_search);
                  if ($res) {
                    $this->collection = array_merge($this->collection, $res->response);
                  }
                }
              }
            $this->modify_all();
        } else {
            $this->collection = false;
            $this->pagination = false;
        }

    }

    private function modify_all()
    {
        if ($this->collection) {
            foreach ($this->collection as &$video) {
                $video->permalink = $this->generate_permalink($video);
                $video->thumbnail_url = $this->add_thumbnail_url($video);
                $video->excerpt = $this->add_excerpt($video);
            }
        }
    }

    public function all_by($by, $params = [])
    {
        $per_page = isset($params['per_page']) ? $params['per_page'] : null;
        $page = isset($params['page']) ? $params['page'] : null;
        $no_cache = isset($params['no_cache']) ? $params['no_cache'] : null;
        $exclude = isset($params['exclude']) ? $params['exclude'] : null;

        $res = \Zype::get_videos_by($by, $page, $per_page, $this->use_admin, $no_cache, $exclude);
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

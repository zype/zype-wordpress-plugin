<?php

namespace ZypeMedia\Models\V2;

class Video extends Base
{
    public function __construct($object)
    {
        parent::__construct($object);
    }

    public static function find($id)
    {
        $single = \Zype::get_video($id);
        return $single ? self::load_model($single) : false;
    }

    public static function all($params, $with_pagination = true)
    {
        return parent::get_all($params, $with_pagination, 'get_videos');
    }

    public function permalink()
    {
        return \site_url() . '/' . $this->options['video_url'] . '/' . $this->title_to_permalink($this->title) . '/' . $this->_id;
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

    public function thumbnail_url()
    {
        $res = \get_template_directory_uri() . '/images/placeholder.jpg';

        if (isset($this->thumbnails) && is_array($this->thumbnails) && isset($this->thumbnails[0])) {
            $res = $this->thumbnails[0]->url;

            foreach ($this->thumbnails as $thumb) {
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

    public function excerpt()
    {
        if (isset($this->short_description) && $this->short_description != '') {
            $excerpt = $this->short_description;
        } else {
            $excerpt = $this->description;
        }

        $original = $excerpt;

        $excerpt = implode(' ', array_slice(explode(' ', $excerpt), 0, 50));

        if (strlen($excerpt) > 400) {
            $excerpt = substr($excerpt, 0, 400);
        }

        if ($excerpt != $original) {
            $excerpt = $excerpt . '... <a href="' . $this->permalink . '/">More</a>';
        }

        return $excerpt;
    }

    public function require_authentication()
    {
        return $this->subscription_required || $this->pass_required ||
        $this->rental_required || $this->purchase_required;
    }
}

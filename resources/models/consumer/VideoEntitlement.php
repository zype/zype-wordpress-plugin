<?php

namespace ZypeMedia\Models\Consumer;

class VideoEntitlement extends \ZypeMedia\Models\Base
{
    const PURCHASE = 'purchase';
    const PASS_PLAN = 'pass';
    const RENTAL = 'rental';

    private $access_token;

    public function __construct($access_token, $use_admin = false)
    {
        parent::__construct();
        $this->access_token = $access_token;
        $this->options = get_option(ZYPE_WP_OPTIONS);
    }

    public function all($params = [], $with_pagination = true)
    {
        $res = \Zype::get_consumer_entitled_videos($this->access_token, $params);

        if ($res) {
            $collection = $res->response;
            $pagination = $res->pagination;
            if (!$with_pagination && $pagination && $pagination->pages > 1) {
              for ($page = $pagination->current + 1; $page <= $pagination->pages; $page++) {
                $params['page'] = $page;
                $res = \Zype::get_consumer_entitled_videos($this->access_token, $params);
                if ($res) {
                  $collection = array_merge($collection, $res->response); 
                }
              }
            }
        } else {
            $collection = false;
            $pagination = false;
        }

        return $with_pagination ? $ret : $collection;
    }
}

<?php

namespace ZypeMedia\Controllers;

class RSS extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function show($user_rss_id)
    {
        $feed_settings = false;
        $consumer = \Zype::find_consumer_by_rss_token($user_rss_id);

        if ($consumer && isset($consumer->subscription_count) && $consumer->subscription_count > 0) {
            header('Content-Type: application/xml;');
            $zm = new \ZypeMedia\Models\zObject('rss feed settings');
            $zm->all_by(
                ['title' => 'default'],
                ['per_page' => 1]
            );

            if ($zm->collection && array_key_exists(0, $zm->collection)) {
                $feed_settings = $zm->collection[0];
                $vm = new \ZypeMedia\Models\Video(true);
                $vm->all_by(['on_air' => 'false', 'outputs' => 'true'], ['per_page' => 500, 'exclude' => true]);
                $items = $vm->collection;

                echo view('admin.rss_template', [
                    'feed_settings' => $feed_settings,
                    'items' => $items
                ]);
            } else {
                echo '<?xml version="1.0" encoding="UTF-8"?>';
                echo '<Error>ConfigurationError<Message>Configuration Error</Message></Error>';
            }
        } else {
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Error>AccessDenied<Message>Access Denied</Message></Error>';
        }

        die();
    }

    public function show_category($user_rss_id)
    {
        $category_key = 'city';
        $category_val = 'city01';
        $consumer = \Zype::find_consumer_by_rss_token($user_rss_id);

        if ($consumer && isset($consumer->subscription_count) && $consumer->subscription_count > 0) {
            header('Content-Type: application/xml;');
            $zm = new \ZypeMedia\Models\zObject('rss feed settings');
            $zm->all_by(
                ['category_name' => $category_key, 'category_value' => $category_val],
                ['per_page' => 1]
            );

            if ($zm->collection && array_key_exists(0, $zm->collection)) {
                $feed_settings = $zm->collection[0];
            }

            $vm = new \ZypeMedia\Models\Video(true);

            $vm->all_by(
                ['category' => [$category_key => $category_val], 'on_air' => 'false', 'outputs' => 'true'],
                ['per_page' => 500]
            );

            $items = $vm->collection;

            echo view('admin.rss_template', [
                'feed_settings' => $feed_settings,
                'items' => $items
            ]);
        } else {
            echo '<?xml version="1.0" encoding="UTF-8"?>';
            echo '<Error>AccessDenied<Message>Access Denied</Message></Error>';
        }

        die();
    }

    private function the_m4a_url($video)
    {
        if ($this->has_m4a($video)) {
            foreach ($video->outputs as $key => $output) {
                if ($output->output_type == 'm4a') {
                    return $output->download_url;
                }
            }
        }
    }

    private function has_m4a($video)
    {
        if (isset($video->outputs)) {
            foreach ($video->outputs as $output) {
                if ($output->output_type == 'm4a') {
                    return true;
                }
            }
            return false;
        }
        return false;
    }

    private function the_file_size($video)
    {
        if ($this->has_m4a($video)) {
            foreach ($video->outputs as $key => $output) {
                if ($output->output_type == 'm4a') {
                    return $output->file_size;
                }
            }
        }
    }
}

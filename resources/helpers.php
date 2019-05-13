<?php

function zype_wp_admin_message($type, $msg)
{
    if (!is_array($_SESSION['zype_admin_messages'])) {
        $_SESSION['zype_admin_messages'] = [];
    }

    array_push($_SESSION['zype_admin_messages'], (object)['type' => $type, 'msg' => $msg]);
}

function zype_wp_admin_notices()
{
    if (isset($_SESSION['zype_admin_messages']) && is_array($_SESSION['zype_admin_messages'])) {
        foreach ($_SESSION['zype_admin_messages'] as $message) {
            if (property_exists($message, 'type') && property_exists($message, 'msg')) {
                echo '<div class="' . $message->type . '"> <p>' . $message->msg . '</p></div>';
            }
        }
    }

    $_SESSION['zype_admin_messages'] = [];
}


function zype_url($page)
{
    echo apply_filters('zype_url', $page);
}

function get_zype_url($page)
{
    return apply_filters('zype_url', $page);
}

function zype_category_url($category, $value)
{
    echo apply_filters('zype_category_url', $category, $value);
}

function get_zype_category_url($category, $value)
{
    return apply_filters('zype_category_url', $category, $value);
}

function zype_zobject_url($category)
{
    echo apply_filters('zype_zobject_url', $category);
}

function zype_current_consumer()
{
    echo (new \ZypeMedia\Services\Auth)->get_email();
}

function zype_already_logged_in()
{
    return (new \ZypeMedia\Services\Auth)->logged_in();
}

function zype_ms2hms($input)
{
    $input = floor($input / 1000);

    $s = zype_num_padder($input % 60);
    $input = floor($input / 60);

    $m = zype_num_padder($input % 60);
    $input = floor($input / 60);

    $h = zype_num_padder($input % 24);
    $input = floor($input / 24);

    echo "{$h}:{$m}:{$s}";
}

function zype_num_padder($num)
{
    if (strlen($num) < 2) {
        $num = "0$num";
    }

    return $num;
}

function zype_video_zobjects($type, $id = null)
{
    if (!$id) {
        $request = zypeRequest();
        $id = $request->validate('zype_video_id', ['textfield']);
    }
    $zm = new \ZypeMedia\Models\zObject($type);
    $zm->all_by(['video_id' => $id]);

    return $zm->collection;
}

function zype_get_videos_by_zobject_type($type)
{
    $videos = [];

    $zObject = \Zype::get_zobjects($type);
    if (!isset($zObject->response[0]->_id)) {
        return $videos;
    }

    $videosResponse = \Zype::get_zobject_videos($zObject->response[0]->_id, 1, 20);
    if (isset($videosResponse->response) && !empty($videosResponse->response)) {
        return $videosResponse->response;
    }

    return $videos;
}

function zype_get_zobject_by_title($type, $zObjectTitle)
{
    $zObject = \Zype::get_zobjects_by($type, array('title' => $zObjectTitle));
    if (!isset($zObject->response[0])) {
        return null;
    }

    return $zObject->response[0];
}

function zype_player_free_embed($video)
{
    (new \ZypeMedia\Models\Player($video))->free_embed();
}

function zype_player_free_embed_auto_play($video)
{
    (new \ZypeMedia\Models\Player($video))->free_embed_auto_play();
}

function zype_player_auth_embed($video)
{
    (new \ZypeMedia\Models\Player($video))->auth_embed();
}

function zype_player_auth_embed_auto_play($video)
{
    (new \ZypeMedia\Models\Player($video))->auth_embed_auto_play();
}

function zype_player_embed($video, $params)
{
    $params['options'] = \Config::get('zype');
    (new \ZypeMedia\Models\Player($video))->embed($params);
}

function zype_est_widget_embed($video)
{
    (new \ZypeMedia\Models\EstWidget($video))->embed();
}

function zype_audio_only()
{
    $request = zypeRequest();

    if ($request->validate('audio', ['textfield'], 'false') == 'true' || \Config::get('zype.audio_only_enabled')) {
        return true;
    }

    return false;
}

function zype_flash_message($type, $msg)
{
    setcookie('zype_flash_messages', json_encode([
        'type' => $type,
        'msg' => $msg,
    ]), 0, "/");
    return $msg;
}

function zype_form_message($type, $msg)
{
    setcookie('zype_form_messages', base64_encode(json_encode([
        'type' => $type,
        'msg' => $msg,
    ])), 0, "/");
}

function zype_form_fields($fields)
{
    setcookie('zype_form_fields', json_encode($fields), 0, "/");
}

function get_zype_form_message()
{
    $request = zypeRequest();
    $zype_form_messages = $request->validateCookie('zype_form_messages', ['textfield']);
    if (isset($zype_form_messages)) {
        try {
            $message = json_decode(base64_decode($zype_form_messages));
            setcookie('zype_form_messages', null, 0, '/');
            $_COOKIE['zype_form_messages'] = null;

            return '<div class="zype_form_messages"><i class="fa fa-fw fa-' . $message->type . '"></i> ' . $message->msg . '</div>';
        } catch (Exception $ex) {
        }
    }
}

function zype_form_now_message($type, $msg)
{
    return '<div class="zype_form_messages"><i class="fa fa-fw fa-' . $type . '"></i> ' . $msg . '</div>';
}


function zype_to_permalink($str, $replace = array(), $delimiter = '-')
{
    if (!empty($replace)) {
        $str = str_replace((array)$replace, ' ', $str);
    }

    $clean = iconv('UTF-8', 'ASCII//TRANSLIT', $str);
    $clean = preg_replace("/[^a-zA-Z0-9\/_|+ -]/", '', $clean);
    $clean = strtolower(trim($clean, '-'));
    $clean = preg_replace("/[\/_|+ -]+/", $delimiter, $clean);

    return $clean;

}

function zype_group_videos_by_category($videos, $groupByCategory = 'Season')
{
    $grouped_videos = array();

    foreach ($videos as $video) {
        foreach ($video->categories as $category) {

            if ($category->title != $groupByCategory)
                continue;

            foreach ($category->value as $value) {
                $grouped_videos[$groupByCategory . ' ' . $value][] = $video;
            }
        }
    }
    ksort($grouped_videos);

    foreach ($grouped_videos as &$seasonVideos) {
        usort($seasonVideos, function ($a, $b) {
            if ($a->episode == $b->episode) {
                return 0;
            }
            return ($a->episode < $b->episode) ? -1 : 1;
        });
    }
    return $grouped_videos;
}

function zype_get_videos_by_category($categoryKey, $categoryVal, $perPage = 200, $page = 0)
{
    $vm = new \ZypeMedia\Models\Video;
    $vm->all_by(
        ['category' => [$categoryKey => stripcslashes($categoryVal)]],
        [
            'per_page' => $perPage,
            'page' => $page,
        ]
    );
    return $vm->collection;
}

function zype_extract_category_images($videos, $categoryName = 'Show', $imageName = "")
{
    if (!$videos) return array();

    return array_reduce(
        $videos,
        function (&$result, $item) use ($categoryName, $imageName) {

            $shows = array();
            if ($item->categories) {
                $shows = array_reduce($item->categories, function (&$result_show, $item_show) use ($categoryName) {
                    if ($item_show->title == $categoryName)
                        $result_show = $item_show->value;
                    return $result_show;
                }, array());
            }

            $image = "";
            if ($item->images) {
                $image = array_reduce($item->images, function (&$result_image, $item_image) use ($imageName) {
                    if ($item_image->title == $imageName)
                        $result_image = $item_image->url;
                    return $result_image;
                }, "");
            }

            if ($shows) {
                foreach ($shows as $show) {
                    if (!isset($result[$show])) {
                        $result[$show] = $image;
                    }
                }
            }

            return $result;
        }
    );

}

function zype_extract_category_thumbnails($videos, $categoryName = 'Show', $thumbHeight = 180)
{
    if (!$videos) return array();

    return array_reduce(
        $videos,
        function (&$result, $item) use ($categoryName, $thumbHeight) {

            $shows = array();
            if ($item->categories) {
                $shows = array_reduce($item->categories, function (&$result_show, $item_show) use ($categoryName) {
                    if ($item_show->title == $categoryName)
                        $result_show = $item_show->value;
                    return $result_show;
                }, array());
            }

            $thumbnail = "";
            if ($item->thumbnails) {
                $thumbnail = array_reduce($item->thumbnails, function (&$result_thumb, $item_thumb) use ($thumbHeight) {
                    if (!isset($result_thumb) && isset($item_thumb->url))
                        $result_thumb = $item_thumb->url;
                    if ($item_thumb->height == $thumbHeight)
                        $result_thumb = $item_thumb->url;
                    return $result_thumb;
                }, "");
            }

            if ($shows) {
                foreach ($shows as $show) {
                    if (!isset($result[$show])) {
                        $result[$show] = $thumbnail;
                    }
                }
            }

            return $result;
        }
    );

}

add_filter('widget_text', 'do_shortcode');
add_theme_support('post-thumbnails');
add_theme_support('html5', ['search-form']);

add_image_size('hero', 1140, 500, true);
add_image_size('blog', 263, 200, true);

add_action('init', function () {
    register_nav_menu('header-menu', 'Header Menu');
});

add_action('widgets_init', function () {
    register_sidebar([
        'name' => 'Header Social Icons',
        'id' => 'header-social-icons'
    ]);
});

add_action('widgets_init', function () {
    register_sidebar([
        'name' => 'Footer Contacts',
        'id' => 'footer-contacts'
    ]);
});

add_action('widgets_init', function () {
    register_sidebar([
        'name' => 'Footer Legal',
        'id' => 'footer-legal'
    ]);
});

add_action('widgets_init', function () {
    register_sidebar([
        'name' => 'Footer Social',
        'id' => 'footer-social'
    ]);
});

add_action('widgets_init', function () {
    register_sidebar([
        'name' => 'Footer Newsletter',
        'id' => 'footer-newsletter'
    ]);
});

add_action('widgets_init', function () {
    register_sidebar([
        'name' => 'Homepage Subscribe',
        'id' => 'homepage-subscribe'
    ]);
});

add_action('widgets_init', function () {
    register_sidebar([
        'name' => 'News and Info Sidebar',
        'id' => 'news-and-info'
    ]);
});

add_action('init', function () {
    add_post_type_support('page', 'excerpt');
});

//exclude 'rundown' category
add_action('pre_get_posts', function ($query) {
    if ($query->is_home() && $query->is_main_query()) {
        $cat = get_term_by('slug', 'rundown', 'category');
        if (!empty($cat->term_id)) {
            $exclude = $cat->term_id;
            $query->set('cat', '-' . $exclude);
        }
    }
});

add_filter('mc4wp_form_css_classes', 'my_mc4wp_form_classes');

function my_mc4wp_form_classes($classes)
{
    $classes[] = 'newsletter-form';
    return $classes;
}


function get_thumbnail_url($id = null, $size = 'thumbnail')
{
    if ($id == null) {
        global $post;
        $id = $post->ID;
    }
    $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($id), $size);
    if ($thumb[0] == '' || $thumb[0] == null || $thumb[0] == false) {
        $thumb[0] = get_template_directory_uri() . '/images/placeholder.jpg';
    }
    return $thumb[0];
}

function echo_the_post_thumbnail_url($id = null, $size = 'thumbnail')
{
    echo get_thumbnail_url($id, $size);
}

function the_html5_time()
{
    the_time('Y-m-d');
}

function the_formatted_time()
{
    the_time(get_option('date_format'));
}

function get_the_html5_time($post_id)
{
    return get_the_time('Y-m-d', $post_id);
}

function get_the_formatted_time($post_id)
{
    return get_the_time(get_option('date_format'), $post_id);
}

function formatted_time($raw_time)
{
    $time = strtotime($raw_time);
    echo date('F j, Y', $time);
}

function html5_time($raw_time)
{
    $time = strtotime($raw_time);
    echo date('Y-m-d', $time);
}

function blog_url()
{
    if (get_option('show_on_front') == 'page') {
        echo get_permalink(get_option('page_for_posts'));
    } else {
        echo bloginfo('url');
    }
}

function get_recent_merch($posts_per_page)
{
    $merch = get_posts(['post_type' => 'merch', 'posts_per_page' => $posts_per_page]);
    return $merch;
}

function get_recent_events($posts_per_page)
{
    $events = get_posts([
        'post_type' => 'event',
        'posts_per_page' => $posts_per_page,
        'meta_key' => 'event_date',
        'orderby' => 'meta_value_num',
        'order' => 'DESC'
    ]);
    return $events;
}

function get_recent_heroes($posts_per_page, $position = 'homepage')
{
    $heroes = get_posts(['post_type' => 'hero', 'posts_per_page' => $posts_per_page,
        'meta_query' => [
            [
                'key' => 'position',
                'value' => '"' . $position . '"',
                'compare' => 'LIKE'
            ]
        ]
    ]);
    usort($heroes, function ($a, $b) {
        return get_field('sort', $a->ID) - get_field('sort', $b->ID);
    });
    return $heroes;
}

function get_recent_sidekicks($posts_per_page, $location = 'homepage_shows')
{
    $sidekicks = get_posts(['post_type' => 'sidekick', 'posts_per_page' => $posts_per_page,
        'meta_query' => [
            [
                'key' => 'location',
                'value' => '"' . $location . '"',
                'compare' => 'LIKE'
            ]
        ]
    ]);
    usort($sidekicks, function ($a, $b) {
        return get_field('sort', $a->ID) - get_field('sort', $b->ID);
    });
    return $sidekicks;
}

function get_show_info($posts_per_page, $position)
{
    $show_info = get_posts(['post_type' => 'show_info', 'posts_per_page' => $posts_per_page,
        'meta_query' => [
            [
                'key' => 'position',
                'value' => $position,
                'compare' => '='
            ]
        ]
    ]);
    return $show_info;
}

function get_shows($posts_per_page = -1)
{
    $shows = get_posts([
        'post_type' => 'show_info',
        'posts_per_page' => $posts_per_page,
        'post_status' => 'publish',
        'order' => 'ASC',
        'orderby' => 'name',
        'meta_query' => [
            [
                'key' => 'signup_form',
                'value' => '1',
                'compare' => '=='
            ]
        ]
    ]);
    return $shows;
}

function order_tacs_video($query)
{
    if (!is_admin() && is_post_type_archive('tacs_video') && $query->is_main_query()) {
        $query->set('meta_key', 'tacs_published_at');
        $query->set('orderby', 'meta_value_num');
        return $query;
    }
    return $query;
}

add_action('pre_get_posts', 'order_tacs_video');

function get_recent_guests($per_page)
{
    $gm = new \ZypeMedia\Models\zObject('guest');
    $gm->suppress_search = true;
    $gm->all(['per_page' => $per_page, 'sort' => 'recent']);
    $guests = $gm->collection;
    return $guests;
}

function get_recent_videos($per_page)
{
    $vm = new \ZypeMedia\Models\Video();
    $vm->suppress_search = true;
    $vm->all(['per_page' => $per_page]);
    $videos = $vm->collection;
    return $videos;
}

function is_zype_search()
{
    global $zype_search;
    if (isset($zype_search['is_search']) && $zype_search['is_search'] == true) {
        return true;
    }
    return false;
}

function zype_search()
{
    global $zype_search;
    if (isset($zype_search['term']) && $zype_search['term']) {
        return $zype_search['term'];
    }
    return false;
}

function zype_sort()
{
    global $zype_sort;
    if (isset($zype_sort['order']) && $zype_sort['order']) {
        return $zype_sort['order'];
    }
    return false;
}

function zype_sort_querystring()
{
    $sort = zype_sort();
    if ($sort) {
        return 'sort=' . $sort;
    }
}

function zype_search_querystring()
{
    $search = zype_search();
    if ($search) {
        return 'search=' . $search;
    }
}

function zype_search_and_sort_querystring()
{
    $search = zype_search_querystring();
    $sort = zype_sort_querystring();

    $querystring = '';

    if ($search || $sort) {
        $querystring = '?';
        if ($search && $sort) {
            $querystring .= $search . '&' . $sort;
        } elseif ($search) {
            $querystring .= $search;
        } elseif ($sort) {
            $querystring .= $sort;
        }
    }
    return $querystring;
}

function zype_wp_enqueue_scripts()
{
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-form');
}

add_action('wp_enqueue_scripts', 'zype_wp_enqueue_scripts', 99);

function the_share_buttons($url = null)
{
    if (!$url) {
        $request_uri = zypeRequest()->validate->validateServer('REQUEST_URI', ['textfield']);
        $url = urlencode(site_url() . $request_uri);
    } else {
        $url = urlencode($url);
    }
}

function zypeRequest() {
    $request = ZypeMedia\Validators\Request::capture();
    return $request;
}

add_action( 'phpmailer_init', 'fix_my_email_return_path' );

function fix_my_email_return_path($phpmailer) {
    $phpmailer->Sender = $phpmailer->From;
}

/*
 * Plugin get asset url.
 */
function asset_url($path = '')
{
    $path = ltrim($path, '/');
    return plugins_url("dist/{$path}", ZYPE_PATH);
}

function zype_url_slug($str, $options = array())
{
    // Make sure string is in UTF-8 and strip invalid UTF-8 characters
    $str = mb_convert_encoding((string)$str, 'UTF-8', mb_list_encodings());

    $defaults = array(
        'delimiter' => '-',
        'limit' => null,
        'lowercase' => true,
        'replacements' => array(),
        'transliterate' => false,
    );

    // Merge options
    $options = array_merge($defaults, $options);

    $char_map = array(
        // Latin
        'À' => 'A', 'Á' => 'A', 'Â' => 'A', 'Ã' => 'A', 'Ä' => 'A', 'Å' => 'A', 'Æ' => 'AE', 'Ç' => 'C',
        'È' => 'E', 'É' => 'E', 'Ê' => 'E', 'Ë' => 'E', 'Ì' => 'I', 'Í' => 'I', 'Î' => 'I', 'Ï' => 'I',
        'Ð' => 'D', 'Ñ' => 'N', 'Ò' => 'O', 'Ó' => 'O', 'Ô' => 'O', 'Õ' => 'O', 'Ö' => 'O', 'Ő' => 'O',
        'Ø' => 'O', 'Ù' => 'U', 'Ú' => 'U', 'Û' => 'U', 'Ü' => 'U', 'Ű' => 'U', 'Ý' => 'Y', 'Þ' => 'TH',
        'ß' => 'ss',
        'à' => 'a', 'á' => 'a', 'â' => 'a', 'ã' => 'a', 'ä' => 'a', 'å' => 'a', 'æ' => 'ae', 'ç' => 'c',
        'è' => 'e', 'é' => 'e', 'ê' => 'e', 'ë' => 'e', 'ì' => 'i', 'í' => 'i', 'î' => 'i', 'ï' => 'i',
        'ð' => 'd', 'ñ' => 'n', 'ò' => 'o', 'ó' => 'o', 'ô' => 'o', 'õ' => 'o', 'ö' => 'o', 'ő' => 'o',
        'ø' => 'o', 'ù' => 'u', 'ú' => 'u', 'û' => 'u', 'ü' => 'u', 'ű' => 'u', 'ý' => 'y', 'þ' => 'th',
        'ÿ' => 'y',
        // Latin symbols
        '©' => '(c)',
        // Greek
        'Α' => 'A', 'Β' => 'B', 'Γ' => 'G', 'Δ' => 'D', 'Ε' => 'E', 'Ζ' => 'Z', 'Η' => 'H', 'Θ' => '8',
        'Ι' => 'I', 'Κ' => 'K', 'Λ' => 'L', 'Μ' => 'M', 'Ν' => 'N', 'Ξ' => '3', 'Ο' => 'O', 'Π' => 'P',
        'Ρ' => 'R', 'Σ' => 'S', 'Τ' => 'T', 'Υ' => 'Y', 'Φ' => 'F', 'Χ' => 'X', 'Ψ' => 'PS', 'Ω' => 'W',
        'Ά' => 'A', 'Έ' => 'E', 'Ί' => 'I', 'Ό' => 'O', 'Ύ' => 'Y', 'Ή' => 'H', 'Ώ' => 'W', 'Ϊ' => 'I',
        'Ϋ' => 'Y',
        'α' => 'a', 'β' => 'b', 'γ' => 'g', 'δ' => 'd', 'ε' => 'e', 'ζ' => 'z', 'η' => 'h', 'θ' => '8',
        'ι' => 'i', 'κ' => 'k', 'λ' => 'l', 'μ' => 'm', 'ν' => 'n', 'ξ' => '3', 'ο' => 'o', 'π' => 'p',
        'ρ' => 'r', 'σ' => 's', 'τ' => 't', 'υ' => 'y', 'φ' => 'f', 'χ' => 'x', 'ψ' => 'ps', 'ω' => 'w',
        'ά' => 'a', 'έ' => 'e', 'ί' => 'i', 'ό' => 'o', 'ύ' => 'y', 'ή' => 'h', 'ώ' => 'w', 'ς' => 's',
        'ϊ' => 'i', 'ΰ' => 'y', 'ϋ' => 'y', 'ΐ' => 'i',
        // Turkish
        'Ş' => 'S', 'İ' => 'I', 'Ç' => 'C', 'Ü' => 'U', 'Ö' => 'O', 'Ğ' => 'G',
        'ş' => 's', 'ı' => 'i', 'ç' => 'c', 'ü' => 'u', 'ö' => 'o', 'ğ' => 'g',
        // Russian
        'А' => 'A', 'Б' => 'B', 'В' => 'V', 'Г' => 'G', 'Д' => 'D', 'Е' => 'E', 'Ё' => 'Yo', 'Ж' => 'Zh',
        'З' => 'Z', 'И' => 'I', 'Й' => 'J', 'К' => 'K', 'Л' => 'L', 'М' => 'M', 'Н' => 'N', 'О' => 'O',
        'П' => 'P', 'Р' => 'R', 'С' => 'S', 'Т' => 'T', 'У' => 'U', 'Ф' => 'F', 'Х' => 'H', 'Ц' => 'C',
        'Ч' => 'Ch', 'Ш' => 'Sh', 'Щ' => 'Sh', 'Ъ' => '', 'Ы' => 'Y', 'Ь' => '', 'Э' => 'E', 'Ю' => 'Yu',
        'Я' => 'Ya',
        'а' => 'a', 'б' => 'b', 'в' => 'v', 'г' => 'g', 'д' => 'd', 'е' => 'e', 'ё' => 'yo', 'ж' => 'zh',
        'з' => 'z', 'и' => 'i', 'й' => 'j', 'к' => 'k', 'л' => 'l', 'м' => 'm', 'н' => 'n', 'о' => 'o',
        'п' => 'p', 'р' => 'r', 'с' => 's', 'т' => 't', 'у' => 'u', 'ф' => 'f', 'х' => 'h', 'ц' => 'c',
        'ч' => 'ch', 'ш' => 'sh', 'щ' => 'sh', 'ъ' => '', 'ы' => 'y', 'ь' => '', 'э' => 'e', 'ю' => 'yu',
        'я' => 'ya',
        // Ukrainian
        'Є' => 'Ye', 'І' => 'I', 'Ї' => 'Yi', 'Ґ' => 'G',
        'є' => 'ye', 'і' => 'i', 'ї' => 'yi', 'ґ' => 'g',
        // Czech
        'Č' => 'C', 'Ď' => 'D', 'Ě' => 'E', 'Ň' => 'N', 'Ř' => 'R', 'Š' => 'S', 'Ť' => 'T', 'Ů' => 'U',
        'Ž' => 'Z',
        'č' => 'c', 'ď' => 'd', 'ě' => 'e', 'ň' => 'n', 'ř' => 'r', 'š' => 's', 'ť' => 't', 'ů' => 'u',
        'ž' => 'z',
        // Polish
        'Ą' => 'A', 'Ć' => 'C', 'Ę' => 'e', 'Ł' => 'L', 'Ń' => 'N', 'Ó' => 'o', 'Ś' => 'S', 'Ź' => 'Z',
        'Ż' => 'Z',
        'ą' => 'a', 'ć' => 'c', 'ę' => 'e', 'ł' => 'l', 'ń' => 'n', 'ó' => 'o', 'ś' => 's', 'ź' => 'z',
        'ż' => 'z',
        // Latvian
        'Ā' => 'A', 'Č' => 'C', 'Ē' => 'E', 'Ģ' => 'G', 'Ī' => 'i', 'Ķ' => 'k', 'Ļ' => 'L', 'Ņ' => 'N',
        'Š' => 'S', 'Ū' => 'u', 'Ž' => 'Z',
        'ā' => 'a', 'č' => 'c', 'ē' => 'e', 'ģ' => 'g', 'ī' => 'i', 'ķ' => 'k', 'ļ' => 'l', 'ņ' => 'n',
        'š' => 's', 'ū' => 'u', 'ž' => 'z'
    );

    // Make custom replacements
    $str = preg_replace(array_keys($options['replacements']), $options['replacements'], $str);

    // Transliterate characters to ASCII
    if ($options['transliterate']) {
        $str = str_replace(array_keys($char_map), $char_map, $str);
    }

    // Replace non-alphanumeric characters with our delimiter
    $str = preg_replace('/[^\p{L}\p{Nd}]+/u', $options['delimiter'], $str);

    // Remove duplicate delimiters
    $str = preg_replace('/(' . preg_quote($options['delimiter'], '/') . '){2,}/', '$1', $str);

    // Truncate slug to max. characters
    $str = mb_substr($str, 0, ($options['limit'] ? $options['limit'] : mb_strlen($str, 'UTF-8')), 'UTF-8');

    // Remove delimiter from ends
    $str = trim($str, $options['delimiter']);

    return $options['lowercase'] ? mb_strtolower($str, 'UTF-8') : $str;
}

/*
 * Generate an ajax shortcode
 */
function ajax_shortcode($type, $params)
{
    $shortcode_params = array_map(function ($key, $value) {
        if(is_bool($value)) {
            $value = ($value === true) ? 'true' : 'false';
        }
        return $value ? "{$key}={$value}" : '';
    }, array_keys($params), $params);
    $shortcode_params = join(' ', $shortcode_params);
    $shortcode = "[{$type} " . $shortcode_params . ']';
    return $shortcode;
}

<?php

function zype_wp_admin_message($type, $msg) {
    if (!is_array($_SESSION['zype_admin_messages'])) {
        $_SESSION['zype_admin_messages'] = [];
    }

    array_push($_SESSION['zype_admin_messages'], (object)['type' => $type, 'msg' => $msg]);
}

function zype_wp_admin_notices() {
    if (isset($_SESSION['zype_admin_messages']) && is_array($_SESSION['zype_admin_messages'])) {
        foreach ($_SESSION['zype_admin_messages'] as $message) {
            if (property_exists($message, 'type') && property_exists($message, 'msg')) {
                echo '<div class="'.$message->type.'"> <p>'.$message->msg.'</p></div>';
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
    $u     = zype_num_padder($input % 1000);
    $input = floor($input / 1000);

    $s     = zype_num_padder($input % 60);
    $input = floor($input / 60);

    $m     = zype_num_padder($input % 60);
    $input = floor($input / 60);

    $h     = zype_num_padder($input % 24);
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
        $id = \Input::get('zype_video_id');
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
    (new \ZypeMedia\Models\Player($video))->embed($params);
}

function zype_est_widget_embed($video)
{
    (new \ZypeMedia\Models\EstWidget($video))->embed();
}

function zype_audio_only()
{
    if ((isset($_GET['audio']) && $_GET['audio'] == 'true') || \Config::get('zype.audio_only_enabled')) {
        return true;
    }

    return false;
}

function zype_flash_message($type, $msg)
{
    setcookie('zype_flash_messages', json_encode([
        'type' => $type,
        'msg'  => $msg,
    ]), 0, "/");
    return $msg;
}

function zype_form_message($type, $msg)
{
    setcookie('zype_form_messages', base64_encode(json_encode([
        'type' => $type,
        'msg'  => $msg,
    ])), 0, "/");
}

function zype_form_fields($fields)
{
    setcookie('zype_form_fields', json_encode($fields), 0, "/");
}

function get_zype_form_message()
{
    if (isset($_COOKIE['zype_form_messages'])) {
        try {
            $message = json_decode(base64_decode(filter_var($_COOKIE['zype_form_messages'], FILTER_SANITIZE_STRING)));
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


function zype_current_url()
{
    $url = 'http';
    if (!empty($_SERVER['HTTPS'])) {
        $url .= "s";
    }
    $url .= "://";
    $url .= $_SERVER["SERVER_NAME"] . $_SERVER["REQUEST_URI"];

    return $url;
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

    foreach ($grouped_videos as $season => &$seasonVideos) {
        usort($seasonVideos, function($a, $b) {
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
            'page'     => $page,
        ]
    );
    return $vm->collection;
}

function zype_extract_category_images($videos, $categoryName = 'Show', $imageName = "")
{
    if (!$videos) return array();

    return array_reduce(
        $videos,
        function(&$result, $item) use ($categoryName, $imageName) {

            $shows = array();
            if ($item->categories) {
                $shows = array_reduce($item->categories, function(&$result_show, $item_show) use ($categoryName) {
                    if ($item_show->title == $categoryName)
                        $result_show = $item_show->value;
                    return $result_show;
                }, array());
            }

            $image = "";
            if ($item->images) {
                $image = array_reduce($item->images, function(&$result_image, $item_image) use ($imageName) {
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
        function(&$result, $item) use ($categoryName, $thumbHeight) {

            $shows = array();
            if ($item->categories) {
                $shows = array_reduce($item->categories, function(&$result_show, $item_show) use ($categoryName) {
                    if ($item_show->title == $categoryName)
                        $result_show = $item_show->value;
                    return $result_show;
                }, array());
            }

            $thumbnail = "";
            if ($item->thumbnails) {
                $thumbnail = array_reduce($item->thumbnails, function(&$result_thumb, $item_thumb) use ($thumbHeight) {
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

function zype_get_all_pass_plans() {
    return \Zype::get_all_pass_plans();
}

function zype_get_all_plans() {
    return \Zype::get_all_plans();
}
  
add_filter('widget_text', 'do_shortcode');
add_theme_support('post-thumbnails'); 
add_theme_support('html5', ['search-form']);

add_image_size('hero', 1140, 500, true);
add_image_size('blog', 263, 200, true);

add_action('init', function(){
  register_nav_menu('header-menu', 'Header Menu');
});

add_action('widgets_init', function(){
  register_sidebar([
    'name' => 'Header Social Icons',
    'id' => 'header-social-icons'
  ]);
});

add_action('widgets_init', function(){
  register_sidebar([
    'name' => 'Footer Contacts',
    'id' => 'footer-contacts'
  ]);
});

add_action('widgets_init', function(){
  register_sidebar([
    'name' => 'Footer Legal',
    'id' => 'footer-legal'
  ]);
});

add_action('widgets_init', function(){
  register_sidebar([
    'name' => 'Footer Social',
    'id' => 'footer-social'
  ]);
});

add_action('widgets_init', function(){
  register_sidebar([
    'name' => 'Footer Newsletter',
    'id' => 'footer-newsletter'
  ]);
});

add_action('widgets_init', function(){
  register_sidebar([
    'name' => 'Homepage Subscribe',
    'id' => 'homepage-subscribe'
  ]);
});

add_action('widgets_init', function(){
  register_sidebar([
    'name' => 'News and Info Sidebar',
    'id' => 'news-and-info'
  ]);
});

add_action('init', function(){
  add_post_type_support('page', 'excerpt');
});

//exclude 'rundown' category
add_action('pre_get_posts', function($query){
  if($query->is_home() && $query->is_main_query()){
    $cat = get_term_by('slug', 'rundown', 'category');
    if (!empty($cat->term_id)) {
        $exclude = $cat->term_id;
        $query->set('cat', '-'.$exclude);
    }
  }
});

add_filter( 'mc4wp_form_css_classes', 'my_mc4wp_form_classes' );

function my_mc4wp_form_classes($classes){
  $classes[]='newsletter-form';
  return $classes;
}




function get_thumbnail_url($id=null, $size='thumbnail'){
  if($id == null){
    global $post;
    $id = $post->ID;
  }
  $thumb = wp_get_attachment_image_src(get_post_thumbnail_id($id), $size);
  if($thumb[0] == '' || $thumb[0] == null || $thumb[0] == false){
    $thumb[0] = get_template_directory_uri().'/images/placeholder.jpg'; 
  }
  return $thumb[0];
}

function echo_the_post_thumbnail_url($id=null, $size='thumbnail'){
  echo get_thumbnail_url($id, $size);
}

function the_html5_time(){
  the_time('Y-m-d');
}

function the_formatted_time(){
  the_time(get_option('date_format'));
}

function get_the_html5_time($post_id){
  return get_the_time('Y-m-d', $post_id);
}

function get_the_formatted_time($post_id){
  return get_the_time(get_option('date_format'), $post_id);
}

function formatted_time($raw_time){
  $time = strtotime($raw_time);
  echo date('F j, Y', $time);
}

function html5_time($raw_time){
  $time = strtotime($raw_time);
  echo date('Y-m-d', $time);
}

function blog_url(){
  if(get_option('show_on_front') == 'page'){
    echo get_permalink(get_option('page_for_posts'));
  }
  else{
    echo bloginfo('url');
  }
}

function get_recent_merch($posts_per_page){
  $merch = get_posts(['post_type' => 'merch', 'posts_per_page' => $posts_per_page]);
  return $merch;
}

function get_recent_events($posts_per_page){
  $events = get_posts([
    'post_type' => 'event', 
    'posts_per_page' => $posts_per_page,
    'meta_key' => 'event_date',
    'orderby' => 'meta_value_num',
    'order' => 'DESC'
  ]);
  return $events;
}

function get_recent_heroes($posts_per_page, $position='homepage'){
  $heroes = get_posts(['post_type' => 'hero', 'posts_per_page' => $posts_per_page,
    'meta_query' => [
      [
        'key' => 'position',
        'value' => '"'.$position.'"',
        'compare' => 'LIKE'
      ]
    ]
  ]);
  usort($heroes, function($a, $b) {
    return get_field('sort', $a->ID) - get_field('sort' ,$b->ID);
  });
  return $heroes;
}

function get_recent_sidekicks($posts_per_page, $location='homepage_shows'){
  $sidekicks = get_posts(['post_type' => 'sidekick', 'posts_per_page' => $posts_per_page,
    'meta_query' => [
      [
        'key' => 'location',
        'value' => '"'.$location.'"',
        'compare' => 'LIKE'
      ]
    ]
  ]);
  usort($sidekicks, function($a, $b) {
    return get_field('sort', $a->ID) - get_field('sort', $b->ID);
  });
  return $sidekicks;
}

function get_show_info($posts_per_page, $position){
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

function get_shows($posts_per_page = -1) {
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

function order_tacs_video($query) {
  if ( !is_admin() && is_post_type_archive('tacs_video') && $query->is_main_query() ) {
    $query->set('meta_key', 'tacs_published_at');
    $query->set('orderby', 'meta_value_num');
    return $query;
  }
  return $query;
}
add_action('pre_get_posts', 'order_tacs_video');

function get_recent_guests($per_page){
  $gm = new \ZypeMedia\Models\zObject('guest');
  $gm->suppress_search = true;
  $gm->all(['per_page' => $per_page, 'sort' => 'recent']);
  $guests = $gm->collection;
  return $guests;
}

function get_recent_videos($per_page){
  $vm = new \ZypeMedia\Models\Video();
  $vm->suppress_search = true;
  $vm->all(['per_page' => $per_page]);
  $videos = $vm->collection;
  return $videos;
}

function is_zype_search(){
  global $zype_search;
  if(isset($zype_search['is_search']) && $zype_search['is_search'] == true){
    return true;
  }
  return false;
}

function zype_search(){
  global $zype_search;
  if(isset($zype_search['term']) && $zype_search['term']){
    return $zype_search['term'];
  }
  return false;
}

function zype_sort(){
  global $zype_sort;
  if(isset($zype_sort['order']) && $zype_sort['order']){
    return $zype_sort['order'];
  }
  return false;
}

function zype_sort_querystring(){
  $sort = zype_sort();
  if($sort){
    return 'sort='.$sort;
  }
}

function zype_search_querystring(){
  $search = zype_search();
  if($search){
    return 'search='.$search;
  }
}

function zype_search_and_sort_querystring(){
  $search = zype_search_querystring();
  $sort = zype_sort_querystring();

  $querystring = '';

  if($search || $sort){
    $querystring = '?';
    if($search && $sort){
      $querystring .= $search.'&'.$sort; 
    }
    elseif ($search){
      $querystring .= $search;
    }
    elseif ($sort){
      $querystring .= $sort;
    }
  }
  return $querystring;
}

function current_menu_item($classes=[], $item=false){
  $current_url = trailingslashit(get_site_url(null, filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_STRING)));
  if(strstr($current_url, $item->url)){
    $classes[] = 'current-menu-item';
  }
  return $classes;
}
add_filter('nav_menu_css_class', 'current_menu_item', 10, 2);

function zype_wp_enqueue_scripts() {
    wp_enqueue_script('jquery');
    wp_enqueue_script('jquery-form');
}
add_action('wp_enqueue_scripts', 'zype_wp_enqueue_scripts', 99);

function the_share_buttons($url=null){
    if(!$url){
        $url = urlencode(site_url().filter_var($_SERVER['REQUEST_URI'], FILTER_SANITIZE_STRING));
    }
    else{
        $url = urlencode($url);
    }
}

/*
 * Plugin get asset url.
 */
function asset_url($path = '') {
    return plugins_url('dist/', __FILE__).$path;
}
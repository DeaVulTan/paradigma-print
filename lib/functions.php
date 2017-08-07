<?php

defined('PF_VERSION') OR header('Location:404.html');

/**
 *
 * @package		Vitubo
 * @author		Vitubo Team 
 * @copyright	Vitubo Team
 * @link		http://www.vitubo.com
 * @since		Version 1.0
 * @filesource
 *
 */
function load_admin_plugins($from_folder, $method = 'admin_init')
{
    if (is_dir($from_folder)) {
        if ($handle = @opendir($from_folder)) {
            while ($file = readdir($handle)) {
                if ((is_dir($from_folder . '/' . $file)) && ($file != '.') && ($file != '..') && ($file != '.svn')) {
                    if (is_file($from_folder . '/' . $file . '/' . $file . '.php')) {
                        require_once $from_folder . '/' . $file . '/' . $file . '.php';
                        $plugin = trim(strtolower($file));
                        load_language($plugin,'default');
                        $plugin_names = explode('-', $plugin);
                        $plugin_names = array_map('strtolower', $plugin_names);
                        $plugin_names = array_map('ucfirst', $plugin_names);
                        $class = implode('_', $plugin_names) . '_Plugin';
                        if (class_exists($class)) {
                            $object = new $class ();
                            if (method_exists($object, $method)) {
                                $object->{$method}();
                            }

                        }
                    }
                }
            }
            closedir($handle);
        }

    }
}

function load_active_plugins($method = 'admin_init')
{
    $actived_plugins = get_option('active_plugins');
    if (!empty($actived_plugins)) {
        foreach ($actived_plugins as $plugin) {
            if (is_file(PLUGIN_PATH . '/' . $plugin)) {
                require PLUGIN_PATH . '/' . $plugin;

                $plugin = explode('/', trim($plugin));
                if (is_array($plugin) && count($plugin) == 2) {
                    $plugin_names = explode('-', $plugin [0]);
                    $plugin_names = array_map('strtolower', $plugin_names);
                    $plugin_names = array_map('ucfirst', $plugin_names);
                    $class = implode('_', $plugin_names) . '_Plugin';
                    load_language($plugin[0]);
                    if (class_exists($class)) {
                        $object = new $class ();
                        if (method_exists($object, $method)) {
                            $object->{$method}();
                        }

                    }
                }
            }
        }
    }
}

function get_all_actived_plugins(){
    $plugins = get_plugins ( PLUGIN_PATH );
    $plugins = array_keys($plugins);
    $actived_plugins = get_option ( 'active_plugins' );

    foreach ($plugins as $k => $plugin){
        if (! in_array ( $plugin, $actived_plugins )) {
            unset($plugins[$k]);
        }
    }

    $admin_plugins = get_plugins ( DEFAULT_PLUGIN_PATH );
    $admin_plugins = array_keys($admin_plugins);

    $return = array('default' => $plugins, 'admin' => $admin_plugins);

    return $return;
}

function get_plugins($from_folder)
{
    $plugins = array();

    if (is_dir($from_folder)) {
        if ($handle = @opendir($from_folder)) {
            while ($file = readdir($handle)) {
                if ((is_dir($from_folder . '/' . $file)) && ($file != '.') && ($file != '..') && ($file != '.svn')) {
                    if (is_file($from_folder . '/' . $file . '/' . $file . '.php')) {
                        $plugins [$file . '/' . $file . '.php'] = get_plugin_info($file, $from_folder . '/' . $file . '/' . $file . '.php');
                    }
                }
            }
            closedir($handle);
        }
    }

    return $plugins;
}

function get_plugin_info($plugin_name, $file)
{
    $plugin_info = array();
    $plugin_names = explode('-', $plugin_name);
    $plugin_names = array_map('strtolower', $plugin_names);
    $plugin_names = array_map('ucfirst', $plugin_names);
    $plugin_class = implode('_', $plugin_names) . '_Plugin';
    if (!class_exists($plugin_class)) {
        require $file;
    }
    $plugin_object = null;
    if (class_exists($plugin_class)) {
        $plugin_object = new $plugin_class(array(), array());
        if (!empty($plugin_object->name)) {
            $plugin_info ['name'] = $plugin_object->name;
            if (!empty($plugin_object->version)) {
                $plugin_info ['version'] = $plugin_object->version;
            }
            if (!empty($plugin_object->author)) {
                $plugin_info ['author'] = $plugin_object->author;
            }
            if (!empty($plugin_object->description)) {
                $plugin_info ['description'] = $plugin_object->description;
            }
        }
    }

    return $plugin_info;
}

function admin_css($link, $file = '')
{
    global $_admin_css;
    if ($file != '') {
        $file = preg_replace('/\\\/', '/', dirname($file));
    }
    $_admin_css [] = array(
            $link,
            $file
    );
}

function admin_js($js, $file = '')
{
    global $_admin_js;
    if ($file != '') {
        $file = preg_replace('/\\\/', '/', dirname($file));
    }
    $_admin_js [] = array(
            $js,
            $file
    );
}

/**
 *
 * @param string $name
 * @param unknown $callback
 * @param string $position_type
 * @param string $position_menu
 * @param string $parent_menu
 */
function admin_menu($plugin_class, $icon_class, $name, $mnu_key, $callback = '', $parent_menu = '', $position_type = '', $position_menu = '')
{
    global $_admin_menu;

    $mnu_key = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $mnu_key), '-'));

    if (!empty($parent_menu)) {
        $mnu_parent_key = strtolower(trim(preg_replace('/[^a-zA-Z0-9]+/', '-', $parent_menu), '-'));
        $_admin_menu [$mnu_parent_key] ['sub'] [$mnu_key] = array(
                'name' => $name,
                'plugin_class' => $plugin_class,
                'icon_class' => $icon_class,
                'callback' => $callback,
                'sub' => array()
        );
    } else {
        if (isset($_admin_menu [$mnu_key] ['sub'])) {
            $tmp = array(
                    'name' => $name,
                    'plugin_class' => $plugin_class,
                    'icon_class' => $icon_class,
                    'callback' => $callback,
                    'sub' => $_admin_menu [$mnu_key] ['sub']
            );
            unset($_admin_menu [$mnu_key]);
            $_admin_menu [$mnu_key] = $tmp;
        } else {
            $_admin_menu [$mnu_key] = array(
                    'name' => $name,
                    'plugin_class' => $plugin_class,
                    'icon_class' => $icon_class,
                    'callback' => $callback,
                    'sub' => array()
            );
        }
    }
}

function add_toolbar_button($form_button)
{
    global $_admin_toolbar_button;
    $_admin_toolbar_button [] = $form_button;
}

function array_move_element(&$array, $a, $b)
{
    $out = array_splice($array, $a, 1);
    array_splice($array, $b, 0, $out);
}

function is_login()
{
    $auth = Pf::auth();
    return $auth->is_logged_in();
}

function set_session($uid)
{
    $db = Pf::database();
    $db->select('user_name, user_firstname, user_lastname, user_role,'.DB_PREFIX.'users.id as uid, user_avatar', ''.DB_PREFIX.'users', "".DB_PREFIX."users.id='$uid'");
    $info = $db->fetch_assoc_all();
    $auth = Pf::auth();
    if (count($info) != 0) {
        $auth->set_session_info_login($info[0]);
    }
}
function current_user($field)
{
    $auth = Pf::auth();
    if (!is_null($auth->get_session('user-id'))) {
        $uid =  $auth->get_session('user-id');
        $db = Pf::database();
        if (!is_null($auth->get_session($field))){
            return $auth->get_session($field);
        }
        else{
            $db->select($field, ''.DB_PREFIX.'users', "`id`=? and user_delete_flag=0",array($uid));
            $info = $db->fetch_assoc_all();
            if (isset($info[0][$field])){
                return $info[0][$field];
            }
            else
                return '';
        }
    } else
        return '';
}
function get_captcha() {
    $publickey = get_configuration('recaptcha_public_key');
    $privatekey = get_configuration('recaptcha_private_key');
    $captcha_enable = get_configuration('recaptcha_enable');
    $captcha_require = @file_get_contents('http://www.google.com/recaptcha/api/challenge?k=' . $publickey);
    if($captcha_require === false){
        return false;
    }
    if (strpos($captcha_require, "Input error") > 0 || $captcha_enable!=1)
        $result	=	FALSE;
    else
    {
        $result= array(
                'publickey' =>  $publickey,
                'privatekey'=>  $privatekey
        );
    }
    return $result;
}
function notif($info) {
    if ($info) {
        $show = "<div class='alert alert-danger fade in'>
                 <button type='button' class='close' data-dismiss='alert' aria-hidden='true'>×</button>
                 <strong>" . $info . "</strong>.
              </div>";
    } else {
        $show = '';
    }
    return $show;
}
function is_admin()
{
    $auth = Pf::auth();
    return $auth->is_admin();
}

function is_editor()
{
    $auth = Pf::auth();
    return $auth->is_editor();
}

function is_author()
{
    $auth = Pf::auth();
    return $auth->is_author();
}

function is_contributor()
{
    $auth = Pf::auth();
    return $auth->is_contributor();
}

function is_User()
{

    $auth = Pf::auth();
    return $auth->is_user();
}

function user_avatar($uid, $size = '80px', $class = '', $alt = '')
{
    if ($uid == current_user('user-id')) {
        $avatar = current_user('avatar');
        if ($alt == '') {
            $alt = current_user('user-firstname').' '.  current_user('user-lastname');
        }
    } else {
        Pf::database()->select('user_avatar,user_firstname, user_lastname', ''.DB_PREFIX.'users', "`id`='$uid'");
        $avatar_data = Pf::database()->fetch_assoc_all();
        $avatar = isset($avatar_data[0]['user_avatar']) ? $avatar_data[0]['user_avatar'] : '';
        if ($alt == '') {
            $alt = isset($avatar_data[0]['user_firstname']) &&  isset($avatar_data[0]['user_lastname'])? $avatar_data[0]['user_firstname'].' '.$avatar_data[0]['user_lastname'] : '';
        }
    }
    if (!empty($avatar) && is_file(urldecode(ABSPATH.'/'.$avatar))) {
        return "<img src='" . site_url().RELATIVE_PATH.'/'.$avatar . "' style='width:" . $size . ";' class='" . $class . "' data-author='" . $alt . "' alt='" . $alt . "'/>";
    } else {
        return "<i class='fa fa-user fa-color $class' style='font-size:" . $size . "' data-author='" . $alt . "' ></i> ";
    }
}

function user_profile($username)
{
    $userpage = 'user';
    return RELATIVE_PATH . "/" . $userpage . "/page:profile/user:" . $username;
}

if(!function_exists('e')){
    function e($data) {
        $data = str_replace(array('&amp;', '&lt;', '&gt;'), array(
                '&amp;amp;', '&amp;lt;', '&amp;gt;'), $data);
        $data = preg_replace('/(&#*\w+)[\x00-\x20]+;/u', '$1;', $data);
        $data = preg_replace('/(&#x*[0-9A-F]+);*/iu', '$1;', $data);
        $data = html_entity_decode($data, ENT_COMPAT, 'UTF-8');
        $data = preg_replace('#(<[^>]+?[\x00-\x20"\'])(?:on|xmlns)[^>]*+>#iu', '$1>', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=[\x00-\x20]*([`\'"]*)[\x00-\x20]*j[\x00-\x20]*a[\x00-\x20]*v[\x00-\x20]*a[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2nojavascript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*v[\x00-\x20]*b[\x00-\x20]*s[\x00-\x20]*c[\x00-\x20] *r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00-\x20]*:#iu', '$1=$2novbscript...', $data);
        $data = preg_replace('#([a-z]*)[\x00-\x20]*=([\'"]*)[\x00-\x20]*-moz-binding[\x00-\x20]*:#u', '$1=$2nomozbinding...', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?expression[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?behaviour[\x00-\x20]*\([^>]*+>#i', '$1>', $data);
        $data = preg_replace('#(<[^>]+?)style[\x00-\x20]*=[\x00-\x20]*[`\'"]*.*?s[\x00-\x20]*c[\x00-\x20]*r[\x00-\x20]*i[\x00-\x20]*p[\x00-\x20]*t[\x00- \x20]*:* [^>]*+>#iu', '$1>', $data);
        $data = preg_replace('#</*\w+:\w[^>]*+>#i', '', $data);
        do {
            $old_data = $data;
            $data = preg_replace('#</*(?:applet|b(?:ase|gsound|link)|embed|frame(?:set)?|i(?:frame|layer)|l(?:ayer|ink)|meta|object|s(?:cript|tyle)|title|xml)[^>]*+>#i', '', $data);
        } while ($old_data !== $data);
        return $data;
    }

}

/**
 *
 * @package		Vitubo
 * @author		Vitubo Team 
 * @copyright	Vitubo Team
 * @link		http://www.vitubo.com
 * @since		Version 1.0
 * @filesource
 *
 */
function is_ajax() {
    return ((isset($_SERVER['HTTP_X_REQUESTED_WITH']) && $_SERVER['HTTP_X_REQUESTED_WITH'] == 'XMLHttpRequest') || !empty($_GET['ajax'])) ? true : false;
}


function get_widgtes($from_folder) {
    $from_folder .= '/controllers';
    $widgets = array ();
    if (is_dir ( $from_folder )) {
        if ($handle = @opendir ( $from_folder )) {
            while ( $file = readdir ( $handle ) ) {
                if ((is_file ( $from_folder . '/' . $file )) && ($file != '.') && ($file != '..') && ($file != '.svn') && ($file != 'index.php')) {
                    $widget_id = str_replace('-widget.php', '', $file);
                    $widgets [$widget_id] = get_widget_info ($widget_id, $from_folder . '/' . $file );
                }
            }
            closedir ( $handle );
        }
    }

    return $widgets;
}
function get_widget_info($plugin_name,$file) {
    $plugin_name = strtolower($plugin_name);
    $plugin_name_ary = explode('-', $plugin_name);
    $widget_class = '';
    foreach ($plugin_name_ary as $v){
        $widget_class .= ucfirst($v).'_';
    }
    $widget_class .= 'Widget';
    
    if (!class_exists($widget_class)){
        require $file;
    }
    $widget_info = array();

    $widget_object = null;
    if (class_exists($widget_class)){
        $widget_object = new $widget_class(array(),array());
        if (!empty($widget_object->name)){
            $widget_info['name'] = $widget_object->name;
            if (!empty($widget_object->version)){
                $widget_info['version'] = $widget_object->version;
            }
            if (!empty($widget_object->description)){
                $widget_info['description'] = $widget_object->description;
            }
        }
    }
    $widget_info['file'] = $file;

    return $widget_info;
}

/**
 * 
 * Execute widgets
 * @param unknown $widgets
 */
function load_widgets($widgets = array(), $setting_data, $active_widgets) {
    global $theme;
    $active_widgets = (is_array($active_widgets)) ? $active_widgets : array();
    foreach ($widgets as $widget) {
        if (!empty($widget['id'])) {
            $widget_id = str_replace('widget_', '', strtolower($widget['id']));
            if (!in_array($widget_id, $active_widgets))
                continue;
            
            $widget_ary = explode('-', strtolower($widget_id));
            $widget_class = '';
            foreach ($widget_ary as $v){
                $widget_class .= ucfirst($v).'_';
            }
            $widget_class .= 'Widget';
            
            
            $all_widgets = array();
            $plugins = get_all_actived_plugins();
            foreach ($plugins['default'] as $plugin){
                $plugin_ary = explode('/', $plugin);
                $all_widgets = array_merge($all_widgets, get_widgtes(PLUGIN_PATH.'/'.$plugin_ary[0].'/widgets'));
            }
            
            foreach ($plugins['admin'] as $plugin){
                $plugin_ary = explode('/', $plugin);
                $all_widgets = array_merge($all_widgets, get_widgtes(DEFAULT_PLUGIN_PATH.'/'.$plugin_ary[0].'/widgets'));
            }
            
            $path_theme_widget = isset($all_widgets[$widget_id])?$all_widgets[$widget_id]['file']:'';
            if (!class_exists($widget_class)) {
                if (is_file($path_theme_widget)) {
                    require $path_theme_widget;
                } else {
                    // Error: Widget class is not found.
                }
            }

            if (class_exists($widget_class) && get_parent_class($widget_class) == 'Pf_Widget') {
                $widget_object = new $widget_class($widget, $setting_data);
                if (method_exists($widget_object, 'index')) {
                    $widget_object->custom_template = 'index';
                    ob_start();
                    $widget_object->index();
                    $data = ob_get_contents();
                    ob_end_clean();
                    echo Pf::shortcode()->exec($data);
                } else {
                    // Error: Widget main method is not found.
                }
            } else {
                // Error: Widget class must extend Pf_Widget .
            }
        } else {
            // Error: Widget don't have the id.
        }
    }
}

function parse_atts($attributes, $default = array()) {
    if (is_array($attributes)) {
        foreach ($default as $key => $val) {
            if (isset($attributes [$key])) {
                $default [$key] = $attributes [$key];
                unset($attributes [$key]);
            }
        }

        if (count($attributes) > 0) {
            $default = array_merge($default, $attributes);
        }
    }

    $att = '';
    foreach ($default as $key => $val) {
        $val = form_prep($val);
        $att .= $key . '="' . $val . '" ';
    }

    return $att;
}

function has_active_widget($widgets = array(), $active_widgets) {
    $flag = false;
    $active_widgets = (is_array($active_widgets)) ? $active_widgets : array();
    foreach ($widgets as $widget) {
        if (!empty($widget['id'])) {
            $widget_id = str_replace('widget_', '', strtolower($widget['id']));
            if (in_array($widget_id, $active_widgets)) {
                $flag = true;
                break;
            }
        }
    }

    return $flag;
}

function public_css($link, $external = false) {
    global $_public_css;

    $_public_css [$link] = array($link, $external);
}

function public_js($js, $external = false) {
    global $_public_js;

    $_public_js [$js] = array($js, $external);
}

function load_css_js($html, $theme) {
    global $_public_css;
    global $_public_js;

    $str_css = '<!-- include css -->' . " \n\t";
    foreach ($_public_css as $css) {
        if ($css[1] == false) {
            $str_css .= '<link href="' . RELATIVE_PATH . '/app/themes/' . $theme . '/' . preg_replace('/\\\/', '/', $css[0]) . '" rel="stylesheet">' . " \n\t";
        } else {
            $str_css .= '<link href="' . preg_replace('/\\\/', '/', $css[0]) . '" rel="stylesheet">' . " \n\t";
        }
    }
    $html = str_replace('<!-- include css (do not remove this line) -->', $str_css, $html);

    $str_js = '<!-- include javascript -->' . " \n\t";
    foreach ($_public_js as $js) {
        if ($js[1] == false) {
            $str_js .= '<script src="' . RELATIVE_PATH . '/app/themes/' . $theme . '/' . preg_replace('/\\\/', '/', $js[0]) . '"></script>' . " \n\t";
        } else {
            $str_js .= '<script src="' . preg_replace('/\\\/', '/', $js[0]) . '"></script>' . " \n\t";
        }
    }
    $html = str_replace('<!-- include javascript (do not remove this line) -->', $str_js, $html);

    return $html;
}

//Head info
function set_head_info($info) {
    global $head_info;
    $head_info = $info;
}

function get_head_info($key) {
    global $head_info;
    return isset($head_info[$key]) ? $head_info[$key] : '';
}

//Get configuration
function get_configuration($key, $name = 'general') {
    return Pf::setting()->get_element_value($name, $key);
}

//Redirect to install page
function redirect_to_install($abs_path) {
    require $abs_path . '/lib/helper/url-helper.php';
    define('RELATIVE_PATH', (strpos($_SERVER["SCRIPT_NAME"], '/' . ADMIN_FOLDER . '/') !== false) ? dirname(substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/'))) : substr($_SERVER['SCRIPT_NAME'], 0, strrpos($_SERVER['SCRIPT_NAME'], '/')));
    header('Location: ' . site_url() . RELATIVE_PATH . '/installs/index.php?step=1');
    exit();
}

/**
 * Function update key in widget category
 */
if (!function_exists('get_site_url')) {

    function get_site_url($url = '') {
        $relative = RELATIVE_PATH;
        if (empty($relative)) {
            return site_url() . '/' . $url;
        }
        return site_url() . "{$relative}/{$url}";
    }

}

if (!function_exists('get_key_widget_shortcode_post')) {

    function get_key_widget_shortcode_post() {
        $option = get_configuration('page_lists', 'pf_post');
        $keys = array();
        if (empty($option)) {
            return;
        }
        Pf::database()->query('SELECT page_content FROM '.DB_PREFIX.'pages WHERE id="' . $option . '" LIMIT 1');
        $content = Pf::database()->fetch_assoc();
        if (empty($content)) {
            return;
        }
        $matches = array();
        preg_match("/category\=\[get:([a-zA-Z0-9_]*)\]/i", $content['page_content'], $matches);
        if (!empty($matches[1])) {
            $keys['category'] = $matches[1];
        }
        preg_match("/tag\=\[get:([a-zA-Z0-9_]*)\]/i", $content['page_content'], $matches);
        if (!empty($matches[1])) {
            $keys['tag'] = $matches[1];
        }
        return $keys;
    }

}
if (!function_exists('upate_widget_category')) {

    function upate_widget_category() {
        $footer = get_option('footer');
        $layouts = get_option('layouts');
        $keys = get_key_widget_shortcode_post();
        $category_key = isset($keys['category']) ? $keys['category'] : '';
        $tag_key = isset($keys['tag']) ? $keys['tag'] : '';

        if (!empty($footer['setting_data'])) {
            foreach ($footer['setting_data'] as $key => $setting) {
                if (isset($setting['widget-key-category'])) {
                    $footer['setting_data'][$key]['widget-key-category'] = $category_key;
                }
                if (isset($setting['widget-key-tag'])) {
                    $footer['setting_data'][$key]['widget-key-tag'] = $tag_key;
                }
            }
            update_option('footer', $footer);
        }
        if (!empty($layouts)) {
            foreach ($layouts as $key_layout => $layout) {
                if (empty($layout['setting_data'])) {
                    continue;
                }
                foreach ($layout['setting_data'] as $key_setting => $setting) {
                    if (isset($setting['widget-key-category'])) {
                        $layouts[$key_layout]['setting_data'][$key_setting]['widget-key-category'] = $category_key;
                    }
                    if (isset($setting['widget-key-tag'])) {
                        $layouts[$key_layout]['setting_data'][$key_setting]['widget-key-tag'] = $tag_key;
                    }
                }
            }
            update_option('layouts', $layouts);
        }
    }

}
if (!function_exists('noreply_email')) {

    function noreply_email() {
        return 'noreply@' . $_SERVER['HTTP_HOST'];
    }

}

/**
 * Functions thumbnail
 */
if (!function_exists('image_check_memory_usage')) {

    function image_check_memory_usage($img, $max_breedte, $max_hoogte) {
        if (file_exists($img)) {
            $K64 = 65536;    // number of bytes in 64K
            $memory_usage = memory_get_usage();
            $memory_limit = abs(intval(str_replace('M', '', ini_get('memory_limit')) * 1024 * 1024));
            $image_properties = getimagesize($img);
            $image_width = $image_properties[0];
            $image_height = $image_properties[1];
            $image_bits = $image_properties['bits'];
            $image_memory_usage = $K64 + ($image_width * $image_height * ($image_bits ) * 2);
            $thumb_memory_usage = $K64 + ($max_breedte * $max_hoogte * ($image_bits ) * 2);
            $memory_needed = intval($memory_usage + $image_memory_usage + $thumb_memory_usage);

            if ($memory_needed > $memory_limit) {
                ini_set('memory_limit', (intval($memory_needed / 1024 / 1024) + 5) . 'M');
                if (ini_get('memory_limit') == (intval($memory_needed / 1024 / 1024) + 5) . 'M') {
                    return true;
                } else {
                    return false;
                }
            } else {
                return true;
            }
        } else {
            return false;
        }
    }

}

if (!function_exists('no_image')) {

    function no_image() {
        return "data:image/png;base64,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";
    }

}

if (!function_exists('check_type_image')) {

    function check_type_image($path) {
        $a = getimagesize($path);
        $image_type = $a[2];
        if (in_array($image_type, array(IMAGETYPE_GIF, IMAGETYPE_JPEG, IMAGETYPE_PNG, IMAGETYPE_BMP))) {
            return true;
        }
        return false;
    }

}

if (!function_exists('get_thumbnails')) {

    function get_thumbnails($file, $width = 160, $full_url = true) {
        $file = urldecode($file);
        $absolute_path_file = ABSPATH . '/' . $file;
        // If the original file and need to resize the width is greater than 0, then further processed
        if (file_exists($absolute_path_file) && is_file($absolute_path_file) && check_type_image($absolute_path_file) && $width > 0) {
            $thumnail_relative_path = dirname($file) . "/thumbs/{$width}/";
            $thumnail_file_name = $thumnail_relative_path . basename($file);
            $thumbnail_url = ($full_url ? site_url() . RELATIVE_PATH . '/' : '') . $thumnail_file_name;

            // Style file thumbnails investigation already exists. If exists returns thumbnails
            if (file_exists($thumbnail_absolute_file = ABSPATH . '/' . $thumnail_file_name)) {
                return $thumbnail_url;
            }

            // If the folder does not exist, then create thumbnails folder
            if (!file_exists(ABSPATH . '/' . $thumnail_relative_path)) {
                if (!mkdir(ABSPATH . '/' . $thumnail_relative_path, 0777, true)) {
                    return no_image();
                }
            }

            // Get the width and height to calculate the height of the file changes
            list($width_old, $height_old) = getimagesize($absolute_path_file);
            $height = $height_old * ($width / $width_old);

            // Check there is enough RAM to handle and not proceed to create thumbnail image
            if (image_check_memory_usage($absolute_path_file, $width, $height)) {
                $img = new SimpleImage(ABSPATH . '/' . $file);
                $img->resize($width, $height)->save($thumbnail_absolute_file, 90);
                return $thumbnail_url;
            }
        }
        return no_image();
    }

}

if (!function_exists('show_widget_tags')) {

    function show_widget_tags($limit = 10) {
        Pf::database()->query("SELECT post_tag_post_id,post_tag_name,post_tag_rewrite, count(post_tag_post_id) as total FROM ".DB_PREFIX."post_tags GROUP BY post_tag_name ORDER BY total DESC LIMIT {$limit}");
        return Pf::database()->fetch_assoc_all();
    }

}

// Function string URL
if (!function_exists('removesign')) {

    function removesign($str) {
        $coDau = array("à", "á", "ạ", "ả", "ã", "â", "ầ", "ấ", "ậ", "ẩ", "ẫ", "ă", "ằ", "ắ"
            , "ặ", "ẳ", "ẵ", "è", "é", "ẹ", "ẻ", "ẽ", "ê", "ề", "ế", "ệ", "ể", "ễ", "ì", "í", "ị", "ỉ", "ĩ",
            "ò", "ó", "ọ", "ỏ", "õ", "ô", "ồ", "ố", "ộ", "ổ", "ỗ", "ơ"
            , "ờ", "ớ", "ợ", "ở", "ỡ",
            "ù", "ú", "ụ", "ủ", "ũ", "ư", "ừ", "ứ", "ự", "ử", "ữ",
            "ỳ", "ý", "ỵ", "ỷ", "ỹ",
            "đ",
            "À", "Á", "Ạ", "Ả", "Ã", "Â", "Ầ", "Ấ", "Ậ", "Ẩ", "Ẫ", "Ă"
            , "Ằ", "Ắ", "Ặ", "Ẳ", "Ẵ",
            "È", "É", "Ẹ", "Ẻ", "Ẽ", "Ê", "Ề", "Ế", "Ệ", "Ể", "Ễ",
            "Ì", "Í", "Ị", "Ỉ", "Ĩ",
            "Ò", "Ó", "Ọ", "Ỏ", "Õ", "Ô", "Ồ", "Ố", "Ộ", "Ổ", "Ỗ", "Ơ"
            , "Ờ", "Ớ", "Ợ", "Ở", "Ỡ",
            "Ù", "Ú", "Ụ", "Ủ", "Ũ", "Ư", "Ừ", "Ứ", "Ự", "Ử", "Ữ",
            "Ỳ", "Ý", "Ỵ", "Ỷ", "Ỹ",
            "Đ", "ê", "ù", "à");
        $khongDau = array("a", "a", "a", "a", "a", "a", "a", "a", "a", "a", "a"
            , "a", "a", "a", "a", "a", "a",
            "e", "e", "e", "e", "e", "e", "e", "e", "e", "e", "e",
            "i", "i", "i", "i", "i",
            "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o", "o"
            , "o", "o", "o", "o", "o",
            "u", "u", "u", "u", "u", "u", "u", "u", "u", "u", "u",
            "y", "y", "y", "y", "y",
            "d",
            "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A", "A"
            , "A", "A", "A", "A", "A",
            "E", "E", "E", "E", "E", "E", "E", "E", "E", "E", "E",
            "I", "I", "I", "I", "I",
            "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O", "O"
            , "O", "O", "O", "O", "O",
            "U", "U", "U", "U", "U", "U", "U", "U", "U", "U", "U",
            "Y", "Y", "Y", "Y", "Y",
            "D", "e", "u", "a");
        return str_replace($coDau, $khongDau, $str);
    }

}
if (!function_exists('url_title')) {

    function url_title($str, $separator = '-', $lowercase = true) {
        if ($separator === 'dash') {
            $separator = '-';
        } elseif ($separator === 'underscore') {
            $separator = '_';
        }

        $q_separator = preg_quote($separator, '#');

        $trans = array(
            '&.+?;' => '',
            '[^a-z0-9 _-]' => '',
            '\s+' => $separator,
            '(' . $q_separator . ')+' => $separator
        );

        $str = strip_tags($str);
        foreach ($trans as $key => $val) {
            $str = preg_replace('#' . $key . '#i', $val, $str);
        }

        if ($lowercase === TRUE) {
            $str = strtolower($str);
        }

        return trim(trim($str, $separator));
    }

}

if (!function_exists('public_base_url')) {

    /**
     * Get base url site
     * @return string
     */
    function public_base_url() {
        return RELATIVE_PATH == '' ? site_url() . '/' : site_url() . RELATIVE_PATH . '/';
    }

}

if (!function_exists('public_url')) {

    /**
     * Create a page of links attached to the base url
     * @param string $url URL you want to pair with base url
     * @param boolean $page_default There are not using the default page?
     * @return string 
     */
    function public_url($url = '', $page_default = false) {
        $page = $page_default === true && !empty($_GET['pf_page_url']) ? $_GET['pf_page_url'] . '/' : '';
        return public_base_url() . (MOD_REWRITE == FALSE ? 'index.php/' : '') . $page . $url;
    }

}

/**
 * Helper for post
 */
function get_full_url() {
    $url = $_SERVER['REQUEST_URI'];
    $base = str_replace('\\', '/', dirname($_SERVER['SCRIPT_NAME']));
    if (strpos($url, $base) === 0) {
        $url = substr($url, strlen($base));
    }
    return $url;
}

if (!function_exists('convent_to_number')) {

    function convent_to_number($item) {
        return (int) $item;
    }

}

if (!function_exists('get_post_detail_id')) {

    function get_post_detail_id() {
        $post_id = array();
        $pattern = '/[\w-]*(\d+)/';
        preg_match_all($pattern, get_full_url(), $post_id);
        array_shift($post_id);
        $clear = array_map('convent_to_number', $post_id[0]);
        return $clear[0];
    }

}


if (!function_exists('check_visible_page')) {

    function check_visible_page($visible) {
        $data = json_decode($visible, true);
        if (empty($data)) {
            return true;
        }
        $flag_users = false;
        $flag_groups = false;
        if (empty($data['users']) || in_array(current_user('user_name'), $data['users'])) {
            $flag_users = true;
        }
        // check permssion groups (linhdh)
        if (empty($data['groups'][0]) || (is_login() && in_array(current_user('user_role'), $data['groups'])) || (is_login() && in_array(current_user('user_role'), $data['groups'],false)) || (is_login() && in_array(5, $data['groups'])) ) {
            $flag_groups = true;
        }
        return $flag_users === true && $flag_groups === true ? true : false;
    }
}

function get_page_url_by_id($id) {
    Pf::database()->query('select page_url from '.DB_PREFIX.'pages where id = ' . (int) $id . ' limit 1');
    $page = Pf::database()->fetch_assoc();
    if (empty($page)) {
        return;
    }
    return $page['page_url'];
}

function check_plugin_active($plugin_name) {
    $active_plugins = get_option('active_plugins');
    $plugin_name = $plugin_name . '/' . $plugin_name . '.php';
    if (is_array($active_plugins) && array_search($plugin_name, $active_plugins)) {
        return true;
    }
    return false;
}

# Referer Shortcode

function fetch_info_refer($info) {
    $data = array(
        'id' => $info['id'],
        'username' => $info['user_name'],
        'firstname' => $info['user_firstname'],
        'lastname' => $info['user_lastname'],
        'avatar' => public_base_url() . $info['user_avatar'],
        'email' => $info['user_email']
    );
    # Megre meta user
    $meta = !empty($info['user_custom_fields']) ? unserialize($info['user_custom_fields']) : array();
    return array_merge($data, $meta);
}

function set_info_referer($id = NULL) {
    $key = 'ref';

    # If the admin information exists then stop. Do not do anything anymore
    if (!empty($_SESSION[$key]) && (($id == REFERER_DEFAULT_USER && $_SESSION[$key]['id'] == REFERER_DEFAULT_USER) || $_SESSION[$key]['id'] == $id || empty($id))) {
        return;
    }

    # Select info referer
    $select = 'select id,user_name,user_firstname,user_lastname,user_email,user_avatar, user_custom_fields from '.DB_PREFIX.'users';
    Pf::database()->query($select . ' where id = ' . (int) $id . ' limit 1');
    $referer = Pf::database()->fetch_assoc();

    if (!empty($referer)) {
        $_SESSION[$key] = fetch_info_refer($referer);
        return;
    }

    # Get info user default
    Pf::database()->query($select . ' where id = ' . REFERER_DEFAULT_USER . ' limit 1');
    $admin = Pf::database()->fetch_assoc();
    if (!empty($admin)) {
        $_SESSION[$key] = fetch_info_refer($admin);
    }
}

function get_info_referer($key) {
    if (isset($_SESSION['ref'][$key])) {
        return $_SESSION['ref'][$key];
    }
}


/** Debug data */
function debug($val){
    echo "<pre>";
    print_r($val);
    echo "</pre>";
    die();
}

function str_to_mysqldate($str,$format = 'y-m-d',$new_format = 'Y-m-d'){
    $str = trim($str);
    if (strpos($str, '0000-00-00') !== false){
        return '';
    }
    $format = strtolower(str_replace('/', '-', trim($format)));
    $format = preg_replace('!y+!', 'y', $format);
    $format = preg_replace('!m+!', 'm', $format);
    $format = preg_replace('!d+!', 'd', $format);

    $new_format = trim($new_format);
    
    $new_format = preg_replace('!y+!', 'y', $new_format);
    $new_format = preg_replace('!m+!', 'm', $new_format);
    $new_format = preg_replace('!d+!', 'd', $new_format);
    $new_format = preg_replace('!Y+!', 'Y', $new_format);
    $new_format = preg_replace('!M+!', 'm', $new_format);
    $new_format = preg_replace('!D+!', 'd', $new_format);
    
    $format_ary = explode(' ', $format);
    $format = $format_ary[0];

    $str = strtolower(str_replace('/', '-', $str));
    $str = preg_replace('!\s+!', ' ', $str);

    $str_ary = explode(' ',$str);
    $date = explode('-', $str_ary[0]);

    switch($format){
        case 'y-m-d':
            $str_ary[0] = $date[0].'-'.$date[1].'-'.$date[2];
            break;
        case 'y-d-m':
            $str_ary[0] = $date[0].'-'.$date[2].'-'.$date[1];
            break;
        case 'd-m-y':
            $str_ary[0] = $date[2].'-'.$date[1].'-'.$date[0];
            break;
        case 'm-d-y':
            $str_ary[0] = $date[2].'-'.$date[0].'-'.$date[1];
            break;
    }
    $str = implode(" ", $str_ary);
    $new_str = false;
    try{
        $date = new DateTime($str);
        $new_str = $date->format($new_format);
    }catch(Exception $e){}
    
    return ($new_str == false)?$str:$new_str;
}

// Function to get the client IP address
function get_client_ip() {
    $ipaddress = '';
    if (getenv('HTTP_CLIENT_IP'))
        $ipaddress = getenv('HTTP_CLIENT_IP');
    else if(getenv('HTTP_X_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_X_FORWARDED_FOR');
    else if(getenv('HTTP_X_FORWARDED'))
        $ipaddress = getenv('HTTP_X_FORWARDED');
    else if(getenv('HTTP_FORWARDED_FOR'))
        $ipaddress = getenv('HTTP_FORWARDED_FOR');
    else if(getenv('HTTP_FORWARDED'))
        $ipaddress = getenv('HTTP_FORWARDED');
    else if(getenv('REMOTE_ADDR'))
        $ipaddress = getenv('REMOTE_ADDR');
    else
        $ipaddress = 'UNKNOWN';
    return $ipaddress;
}

//this is function cut string description
function cut( $str, $limit, $more=" ..."){
    if ($str=="" || $str == NULL || is_array($str) || strlen($str)==0)
        return $str;
    $str = trim($str);
     
    if (strlen($str) <= $limit) return $str;
    $str = substr($str,0,$limit);

    if (!substr_count($str," ")){
        if ($more) $str .= " ...";
        return $str;
    }
    while(strlen($str) && ($str[strlen($str)-1] != " ")){
        $str = substr($str,0,-1);
    }
    $str = substr($str,0,-1);
    if ($more) $str .= " ...";
    return $str;
}

//Function get theme
function get_active_theme(){
    return get_option('active_theme');
}

/**
 * Generate Random ID
 */
if (!function_exists('generate_id')) {

    function generate_id($length) {
        $random = '';
        for ($i = 0; $i < $length; $i++) {
            $random .= chr(rand(ord('a'), ord('z')));
        }
        return $random;
    }

}
/**
 * The function used for session hijacking
 */
if (!function_exists('get_token_input')) {

    function get_token_input($time = 600, $key = '') {
        return '<input type="hidden" value="' . Pf_Plugin_CSRF::token($key, $time) . '" name="token"/>';
    }

}
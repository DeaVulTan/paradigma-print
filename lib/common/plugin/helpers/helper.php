<?php

defined('PF_VERSION') OR header('Location:404.html');
/**
 *
 * @package Vitubo
 * @author Vitubo Team 
 * @copyright   Vitubo Team
 * @link http://www.vitubo.com
 * @since Version 1.0
 * @filesource
 *
 */
/**
 * The set of functions when using validation
 */
if (!function_exists('has_error')) {

    function has_error($key, $data = array()) {
        if (isset($data[$key])) {
            return true;
        }

        return false;
    }

}

if (!function_exists('state_validator')) {

    function state_validator($key, $data = array()) {
        $status = '';
        if (has_error($key, $data)) {
            $status = 'has-error';
        } elseif (!empty($data)) {
            $status = 'has-success';
        }

        return $status;
    }

}

if (!function_exists('alert_error_validator')) {

    function alert_error_validator($key, $data = array(), $return = false) {

        if (has_error($key, $data)) {
            foreach ($data[$key] as $value) {
                $error = "<p class='help-block'>{$value}</p>";
                if ($return) {
                    return $error;
                }

                echo $error;
            }
        }
    }

}

/**
 * The use of the recursive function
 */
if (!function_exists('recursive')) {

    function recursive123($data, &$result, $parent = 0, $level = 0, $prefix = '') {
        if (count($data)) {
            $tmp = $prefix !== '' ? $prefix . '_parent' : 'parent';
            foreach ($data as $key => $value) {
                if ($value->$tmp == $parent) {
                    $id = $value->id;
                    $value->level = $level;
                    $result[] = $value;
                    unset($data[$key]);
                    recursive($data, $result, $id, $level + 1, $prefix);
                }
            }
        }
    }

}

if (!function_exists('replace_recursive')) {

    function replace_recursive($numReplace, $title) {
        $tmp = '';
        if ($numReplace > 0) {
            $tmp = str_repeat('--', $numReplace);
        }

        return $tmp . ' ' . $title;
    }

}

if (!function_exists('replace_recursive_title')) {

    function replace_recursive_title($data, $prefix = 'category') {
        $object = $prefix !== '' ? $prefix . '_name' : 'name';
        $data->$object = replace_recursive($data->level, $data->$object);
        return $data;
    }

}

if (!function_exists('get_all_parent_by_id')) {

    function get_all_parent_by_id($data, &$result, $id = 0, $prefix = '') {
        if (count($data)) {
            $parent = $prefix !== '' ? $prefix . '_parent' : 'parent';
            foreach ($data as $key => $value) {
                if ($value->$parent == $id) {
                    $result[] = $value->id;
                    unset($data->$key);
                    get_all_parent_by_id($data, $result, $value->id, $prefix);
                }
            }
        }
    }

}

if (!function_exists('remove_parent_by_id')) {

    function remove_parent_by_id($data, &$result, $id = 0, $prefix = '') {
        $parent = array();
        get_all_parent_by_id($data, $parent, $id, $prefix);
        array_unshift($parent, $id);
        foreach ($data as $value) {
            if (!in_array($value->id, $parent)) {
                $result[] = $value;
            }
        }
    }

}

if (!function_exists('get_all_children')) {

    function get_all_children($data, &$result, $id = 0, $object = '') {
        if (count($data)) {
            foreach ($data as $key => $value) {
                if ($value[$object] == $id) {
                    $result[] = $value['id'];
                    unset($data[$key]);
                    get_all_children($data, $result, $value['id'], $object);
                }
            }
        }
    }

}

if (!function_exists('get_parent_of_category')) {

    function get_parent_of_category($data) {
        return $data->category_parent;
    }

}

if (!function_exists('get_id_of_category')) {

    function get_id_of_category($data) {
        return $data->id;
    }

}

/**
 * The function used for Curd
 */
if (!function_exists('multiple_item_insert')) {

    function multiple_item_insert($data) {
        $result[] = $data;
        return $result;
    }

}

if (!function_exists('generate_param_where_in')) {

    function generate_param_where_in() {
        return "?";
    }

}

if (!function_exists('generate_where_in')) {

    function generate_where_in($id) {
        $conditions = array_map('generate_param_where_in', $id);
        return implode(',', $conditions);
    }

}

if (!function_exists('generate_where_or')) {

    function generate_where_or($id, $column) {
        $conditions = array_map('generate_param_where_in', $id);
        $where_or = array();
        foreach ($conditions as $item) {
            $where_or[] = "{$column} = {$item}";
        }

        return implode(' or ', $where_or);
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

if (!function_exists('get_token_link')) {

    function get_token_link($key, $time = 600) {
        return Pf_Plugin_CSRF::token($key, $time);
    }

}

if (!function_exists('check_token')) {

    function check_token($token, $key = '') {
        return Pf_Plugin_CSRF::is_valid($token, $key);
    }

}

/**
 * The function used for data array (Table option)
 */
if (!function_exists('get_data_by_key')) {

    function get_data_by_key($data, $key) {
        $title = isset($data[$key]) ? strtolower($data[$key]) : '';
        return $title;
    }

}

if (!function_exists('get_title')) {

    function get_title($data) {
        return get_data_by_key($data, 'title');
    }

}

if (!function_exists('get_status')) {

    function get_status($data) {
        return get_data_by_key($data, 'status');
    }

}

if (!function_exists('search_title')) {

    function search_title($data, $condition) {
        $input = preg_quote(strtolower($condition), '~');
        $result = preg_grep('~' . $input . '~', $data);
        return $result;
    }

}

if (!function_exists('search_status')) {

    function search_status($data, $condtion) {
        $status = array();
        foreach ($data as $key => $value) {
            if ($value == $condtion) {
                $status[$key] = $condtion;
            }
        }

        return $status;
    }

}

if (!function_exists('search_by_key')) {

    function search_by_key($data, $condition, $key_search) {
        $data_get = array_map('get_' . $key_search, $data);
        $result_search = is_string($condition) ? search_title($data_get, $condition) : search_status($data_get, $condition);
        $data_search = array();
        if (count($result_search)) {
            $data_search = array_intersect_key($data, $result_search);
        }

        return $data_search;
    }

}

if (!function_exists('pagination')) {

    function pagination($data, $curent_page) {
        if (is_array($data)) {
            $total = count($data);
            $total_page = ceil($total / NUM_PER_PAGE);
            if ($curent_page > $total_page) {
                $curent_page = $total_page;
            }

            $start = ($curent_page - 1) * NUM_PER_PAGE;
            return array_slice($data, $start, NUM_PER_PAGE);
        }
    }

}

/**
 * The function used for date
 */
if (!function_exists('ago')) {

    function ago($date) {
        $date_ago = $date;
        if (empty($date_ago)) {
            return __('No date provided', 'includes');
        }

        $periods = array(
            __('second', 'includes'),
            __('minute', 'includes'),
            __('hour', 'includes'),
            __('day', 'includes'),
            __('week', 'includes'),
            __('month', 'includes'),
            __('year', 'includes'),
            __('decade', 'includes')
        );
        $lengths = array(
            "60",
            "60",
            "24",
            "7",
            "4.35",
            "12",
            "10"
        );
        $now = time();
        $unix_date = strtotime($date_ago);
        if (empty($unix_date)) {
            return __('Bad date', 'includes');
        }

        if ($now > $unix_date) {
            $difference = $now - $unix_date;
            $tense = __('ago', 'includes');
        } else {
            $difference = $unix_date - $now;
            $tense = __('from now', 'includes');
        }

        for ($j = 0; $difference >= $lengths[$j] && $j < count($lengths) - 1; $j++) {
            $difference/= $lengths[$j];
        }

        $difference = round($difference);
        if ($difference != 1) {
            $periods[$j].= "";
        }

        return "$difference $periods[$j] {$tense}";
    }

}

if (!function_exists('convert_date')) {

    function convert_date($date, $format = 'd-m-Y') {
        return date($format, strtotime($date));
    }

}


/**
 * The function used for frontend
 */
if (!function_exists('escape_value')) {

    function escape_value($value) {
        $db = Pf::database();
        return $db->escape($value);
    }

}

if (!function_exists('the_excerpt')) {

    function the_excerpt($text, $strLen = 255) {
        $sanitized = strip_tags($text);
        if (strlen($sanitized) > $strLen) {
            $cutString = substr($sanitized, 0, $strLen);
            if (strrpos($cutString, ' ')) {
                return substr($sanitized, 0, strrpos($cutString, ' ')) . '...';
            }
            return $cutString . '...';
        }
        return $sanitized;
    }

}

if (!function_exists('get_all_id_object')) {

    function get_all_id_object1($data) {
        $result = array();
        if (is_array($data)) {
            foreach ($data as $item) {
                $result[] = $item->id;
            }
        }
        return $result;
    }

}


if (!function_exists('get_path_file')) {

    function get_path_file($file) {
        return public_base_url() . $file;
    }

}


/**
 * The other utility functions
 */
if (!function_exists('show_status')) {

    function show_status($url, $status = 1, $no_link = false) {
        if ($status == 1) {
            return $no_link == true ? '<span class="label label-success">' . __('Published', 'includes') . '</span>' : '<a href="' . $url . '" class="label label-success">' . __('Published', 'includes') . '</a>';
        }

        return $no_link == true ? '<span class="label label-danger">' . __('Unpublished', 'includes') . '</span>' : '<a href="' . $url . '" class="label label-danger">' . __('Unpublished', 'includes') . '</a>';
    }

}

if (!function_exists('show_status_filter')) {

    function show_status_filter() {
        $status = __('Unpublished', 'includes');
        if (!isset($_GET['status'])) {
            $status = __('All status', 'includes');
        } elseif ($_GET['status'] == 1) {
            $status = __('Published', 'includes');
        }

        return $status;
    }

}

if (!function_exists('get_value_search')) {

    function get_value_search($key = 'kw') {
        $kw = isset($_GET[$key]) ? e($_GET[$key]) : '';
        return $kw;
    }

}

if (!function_exists('e')) {

    function e($data) {
        $data = str_replace(array(
            '&amp;',
            '&lt;',
            '&gt;'
                ), array(
            '&amp;amp;',
            '&amp;lt;',
            '&amp;gt;'
                ), $data);
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

if (!function_exists('set_message_ajax')) {

    function set_message_ajax($code) {
        echo json_encode(array(
            'code' => $code
        ));
    }

}

if (!function_exists('convent_to_number')) {

    function convent_to_number($item) {
        return (int) $item;
    }

}

if (!function_exists('dd')) {

    function dd($data) {
        echo "<pre>";
        print_r($data);
        echo "</pre>";
        exit();
    }

}

if (!function_exists('get_ul_category')) {

    function get_ul_category(&$list, $base, $child = FALSE) {
        $str = '';
        if (count($list)) {
            $str .= $child == FALSE ? '<ul class="categories blg-social">' : '<ul class="list-unstyled">';
            foreach ($list as $item) {
                $slug_name = get_configuration('show_category_name_url', 'pf_post') == 1 ? '/' . url_title($item->category_name) : '';
                $str .= '<li>';
                $str .= "<a href='" . public_url() . $base . $item->id . $slug_name . "'>{$item->category_name}</a>";
                if (isset($item->children) && count($item->children)) {
                    $str .= get_ul_category($item->children, $base, TRUE);
                }
                $str .= '</li>' . PHP_EOL;
            }
            $str .= '</ul>' . PHP_EOL;
        }
        return $str;
    }

}


if (!function_exists('print_date')) {

    function print_date($data) {
        $str = '';
        if (isset($data) && strlen($data) > 0) {
            $tmp = explode('-', $data);
            $str = isset($tmp[0]) && $tmp[0] == '0000' ? '' : $data;
        }
        return $str;
    }

}


if (!function_exists('get_list_user')) {

    $user_id = DB_PREFIX.'users.id';
    function get_list_user($conditions, $param, $select = '$user_id as uid,user_name,user_email') {
        $db = PF::database();
        $db->select($select, ''.DB_PREFIX.'users', $conditions, $param);
        return$db->fetch_assoc_all();
    }

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

function get_value_input($key, $value = '') {
    if (isset($_POST[$key])) {
        $value = $_POST[$key];
    }
    return e($value);
}

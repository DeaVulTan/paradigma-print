<?php
defined('PF_VERSION') OR header('Location:404.html');

/**
 *
 * @package		Vitubo
 * @author		Vitubo Team
 * @copyright   Vitubo Team
 * @link		http://www.vitubo.com
 * @since		Version 1.0
 * @filesource
 *
 */

if (!function_exists('get_id_of_category')) {

    function get_id_of_category($data) {
        return $data['id'];
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
        $data[$object] = replace_recursive($data['level'], $data[$object]);
        return $data;
    }

}

//Get level
function get_level($data, $display_level = '--'){
    $result = array();
    $data_id = array();
    foreach($data as $key => $value){
        $data_id[$key] = $value['category_parent'];
    }
    if (count($data)){
        $ids = $data_id;
        recursive($data, $result, min($ids), 0,'category');
    }

    if (count($data) !== count($result)) {
        $ids = array_map('get_id_of_category', $result);
        foreach ($data as $k => $v) {
            if (!in_array($v['id'], $ids)) {
                $v['level'] = 0;
                $result[] = $v;
                unset($data[$k]);
            }
        }
    }

    if (!is_null($display_level)) {
        return array_map('replace_recursive_title', $result);
    }
    return $result;
}

function recursive($data, &$result, $parent = 0, $level = 0, $prefix = '') {
    if (count($data)) {
        $tmp = $prefix !== '' ? $prefix . '_parent' : 'parent';
        foreach ($data as $key => $value) {
            if ($value[$tmp] == $parent) {
                $id = $value['id'];
                $value['level'] = $level;
                $result[] = $value;
                unset($data[$key]);

                recursive($data, $result, $id, $level + 1, $prefix);
            }
        }
    }
}

//Public

function show_table_tag($data) {
    $table = '';
    if (count($data)) {
        foreach ($data as $value) {
            $table .= "<tr id='data_" . $value['id'] . "' data-tag='" . $value['id'] . "'>";
            $table .= '<td><input type="checkbox" name="id" value="' . $value['tag_name'] . '"/></td>';
            $table .= '<td>' . $value['tag_name'] . '</td>';
            $table .= '<td><button class="btn btn-danger btn-xs confirmDeleteTag" type="button" data-id="' . $value['id'] . '" data-name="' . $value['tag_name'] . '"><i class="fa fa-trash-o"></i></button></td';
            $table .= '</tr>';
        }
    }
    return $table;
}

function find_tag_regular($pattern, $str) {
    $output = array();
    preg_match($pattern, $str, $output);
    return $output;
}

function find_tag($tagname, $str) {
    $pattern = '/\{' . $tagname . '\}(.*)\{\/' . $tagname . '\}/s';
    return find_tag_regular($pattern, $str);
}

function transformer_post($item) {
    $enable_comment = Pf::setting()->get_element_value('pf_post', 'enable_comment');
    $format_date = Pf::setting()->get_element_value('general', 'long_date');
    $format_shortdate = Pf::setting()->get_element_value('general', 'short_date');
    $user_page = public_url() . 'user/profile/';
    $description_length = Pf::setting()->get_element_value('pf_post', 'length_of_description') > 0 ?
    Pf::setting()->get_element_value('pf_post', 'length_of_description') : 255;
    if (isset($item['post_thumbnail'])) {
        $thumbnail = $item['post_thumbnail'] == '' ? no_image() : site_url().RELATIVE_PATH."/".$item['post_thumbnail'];
    } else {
        $thumbnail = no_image();
    }
    if (isset($item['user_avatar'])) {
        $avatar = $item['user_avatar'] == '' ? no_image() : get_thumbnails($item['user_avatar'], 80);
    } else {
        $avatar = no_image();
    }
    
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );
    
    
    // переводим в транслит
    $cirurl = strtr($item['post_title'], $converter);
    // в нижний регистр
    $cirurl = strtolower($cirurl);
    // заменям все ненужное нам на "-"
    $cirurl = preg_replace('~[^-a-z0-9_]+~u', '-', $cirurl);
    // удаляем начальные и конечные '-'
    $cirurl = trim($cirurl, "-");
    
    return array(
        'id' => $item['id'],
        'title' => isset($item['post_title']) ? e($item['post_title']) : '',
        'author' => isset($item['user_firstname']) && isset($item['user_lastname']) ? e($item['user_firstname']) . ' ' . e($item['user_lastname']) : '',
        'author_id' => isset($item['post_author']) ? e($item['post_author']) : '',
        'author_link' => isset($item['user_name']) ? $user_page . 'user:' . e($item['user_name']) : '',
        'avatar' => $avatar,
        'category' => isset($item['category_name']) ? e($item['category_name']) : '',
        'category_id' => isset($item['post_category']) ? $item['post_category']: '',
        'date' => isset($item['post_created_date']) ? convert_date($item['post_created_date'], $format_date) : '',
        'short_date' => isset($item['post_created_date']) ? convert_date($item['post_created_date'], $format_shortdate) : '',
        'month' => isset($item['post_created_date']) ? convert_date($item['post_created_date'], 'm') : '',
        'day' => isset($item['post_created_date']) ? convert_date($item['post_created_date'], 'd') : '',
        'content' => isset($item['post_content']) ? e($item['post_content']) : '',
        'description' => isset($item['post_content']) ? the_excerpt($item['post_content'], $description_length) : '',
        'thumbnail' => $thumbnail,
        'tags' => $item['tags'],
        'comment' => isset($item['comments']) && (isset($item['post_allow_comment']) && $item['post_allow_comment'] == 1 && $enable_comment == 1) ? $item['comments'] : '',
        'link_detail' => $item['link_detail'] . '/' . (get_configuration('show_title_url', 'pf_post') == 1 ?  url_title(removesign($cirurl)) . '-' . $item['id'] : $item['id']),
        'views' => isset($item['post_views']) ? e($item['post_views']) : 1,
        'link_comment' => (isset($item['post_allow_comment']) && $item['post_allow_comment'] == 1 && $enable_comment == 1) ? $item['link_detail'] .'/'.url_title(removesign($cirurl)).'-'.$item['id'].'#id_post_' . $item['id'] : '#',
        'rating' => isset($item['rating']) && (isset($item['post_allow_rating']) && $item['post_allow_rating'] == 1) ? $item['rating'] : ''
    );
}

/**
 * Array map
 * @param array $item
 * @return array
 */
function generate_param_count_comment($item) {
    return 'post_' . $item;
}

function get_tag_post_select($item) {
    return $item['post_tag_tag_id'];
}

function find_tag_item($id, $url, $data) {
    foreach ($data as $item) {
        //if ($item['post_tag_post_id'] == $id) {
            return $url == '' ? '<span>' . e($item['post_tag_name']) . '</span>' : "<a href='" . public_url($url . '/tag:' . $item['post_tag_rewrite']) . (get_configuration('show_tag_name_url', 'pf_post') == 0 ? '/' . url_title(removesign($item['post_tag_post_id'])) : '') . "' class='background-color bg-hover-color'>" . e($item['post_tag_name']) . "</a>";
        //}
    }
}

function find_comment_item($id, $data) {
    foreach ($data as $item) {
        if ($item->comment_key === 'post_' . $id) {
            return $item->counted;
        }
    }
}

if (!function_exists('no_image')) {

    function no_image() {
        return "data:image/png;base64,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";
    }

}

if (!function_exists('get_current_date')) {

    function get_current_date($format = 'Y-m-d H:i:s') {
        return date($format);
    }

}

function get_rating_show($item){
    if($item['rating'] != ''){
        return $item['id'];
    }
}

if (!function_exists('generate_param_where_in')) {

    function generate_param_where_in() {
        return "?";
    }

}

if (!function_exists('multiple_item_insert')) {

    function multiple_item_insert($data) {
        $result[] = $data;
        return $result;
    }

}

function get_all_id_object($data) {
    $result = array();
    if (is_array($data)) {
        foreach ($data as $item) {
            $result[] = $item['id'];
        }
    }
    return $result;
}
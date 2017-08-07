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
function show_all_comment($list, $key, $avatar, $templete, $meta, $childClass = '') {
    $str = '';
    foreach ($list as $item) {
        $permission = false;
        $token = '';
        if ($item['comment_user_id'] == current_user('user-id')) {
            $token = Pf_Plugin_CSRF::token($key . $item['id']);
            $permission = true;
        }
        $str .= '<div class="media cmt ' . $childClass . '">';
        $str .=!empty($avatar[$item['comment_user_id']]) ? '<img src="' . get_thumbnails($avatar[$item['comment_user_id']]) . '" class="media-object pull-left avatar" alt="' . $item['comment_author'] . '" data="' . $item['comment_author'] . '">' : "<i class='fa fa-user media-object pull-left avatar text-color' style='font-size:60px' data-author='" . $item['comment_author'] . "'></i>";
        $str .= '<div class="media-body" data-token="' . $token . '" data-id=' . $item['id'] . '>';
        $str .= '<h5 class="media-heading cmt-block"><strong>' . $item['comment_author'] . '</strong><span class="text-muted pull-right"> ' . ago($item['comment_created_date']) . '</span></h5>';
        $str .= show_comment_data($templete, $meta, $item['id']);
        $str .= '<div class="message" id="comment_id_'.$item['id'].'"><p>' . nl2br(e($item['comment_content'])) . '</p></div>';
        $str .= '<ul class="toolBar">';
        $str .= is_login() ? '<li><i class="fa fa-pencil"></i> <a href="#" class="btnReply">' . __('Reply', 'comment') . '</a>' : '';
        if ($permission) {
            $str .= '<li><i class="fa fa-share"></i> <a href="#" class="btnEdit" data-id=' . $item['id'] . '>' . __('Edit', 'comment') . '</a>';
            $str .= '<li><i class="fa fa-trash-o"></i> <a href="#" class="btnRemove">' . __('Remove', 'comment') . '</a></li>';
        }
        $str .= '</ul>';
        if (!empty($item['children'])) {
            $str .= show_all_comment($item['children'], $key, $avatar, $templete, $meta, 'children');
        }
        $str .= '</div></div>';
    }
    return $str;
}

function get_users_comment($comments) {
    return $comments['comment_user_id'];
}

if (!function_exists('check_permission_approve')) {

    function check_permission_approve() {
        if (get_configuration('approve_flag', 'pf_comment') == true) {
            $users = get_configuration('approve', 'pf_comment');
            if (empty($users) || !in_array(current_user('user-id'), $users)) {
                return false;
            }
        }
        return true;
    }

}

function get_value_error($item) {
    return isset($item[0]) ? $item[0] : 0;
}

function edit_message_error($data) {
    $errors = array();
    foreach ($data as $key => $item) {
        $errors[$key] = str_replace('<span class="field"></span>', " $key ", $item);
    }
    return $errors;
}

function columns_meta_comment() {
    return array(
        'comment_meta_key',
        'comment_meta_value',
        'comment_meta_key_comment',
        'comment_meta_id_comment'
    );
}

function show_comment_data($templete, $data, $id) {
    if (empty($data[$id])) {
        return;
    }
    foreach ($data[$id] as $key => $item) {
        $templete = str_replace('{' . $key . '}', $item, $templete);
    }
    return '<div class="custom-field-message">' . $templete . '</div>';
}

function except_key_array($data, $key = array()) {
    $data = array_merge($data, $data['validate']);
    $default = array('validate', 'title', 'group');
    $keys = array_merge($default, $key);
    $data = array_diff_key($data, array_flip($keys));
    if (!empty($data)) {
        $data = array_filter($data);
    }
    return $data;
}

function checked($left, $right) {
    return $left === $right ? true : false;
}

function build_item_form($data) {
    $type = $data['type'];
    $html = '';
    if ($data['group'] === 'input') {
        $data_form = except_key_array($data);
        switch ($type) {
            case 'textarea':
                $html = form_textarea($data_form);
                break;
            default:
                $html = form_input($data_form);
                break;
        }
    } else {
        $except_select = except_key_array($data, array('name', 'options', 'value', 'type'));
        switch ($type) {
            case 'select':
                $options = array();
                foreach ($data['options'] as $item) {
                    $options[$item['value']] = $item['value'];
                }
                $html = form_dropdown($data['name'], $options, $data['value'], parse_atts($except_select));
                break;
            case 'radio':
                foreach ($data['options'] as $value) {
                    if (checked($data['value'], $value['value'])) {
                        $except_select['checked'] = 'checked';
                    } else {
                        unset($except_select['checked']);
                    }
                    $html .= '<label>' . form_radio($data['name'], $value['value'], null, parse_atts($except_select)) . " {$value['value']}</label>\n";
                }
                break;
            case 'checkbox':
                foreach ($data['options'] as $value) {
                    if (checked($data['value'], $value['value'])) {
                        $except_select['checked'] = 'checked';
                    } else {
                        unset($except_select['checked']);
                    }
                    $html .= '<label>' . form_checkbox($data['name'], $value['value'], null, parse_atts($except_select)) . " {$value['value']}</label>\n";
                }
                break;
        }
    }
    return $html;
}

function build_form($data, $keys) {
    $html = array();
    foreach ($data as $item) {
        if (isset($item['name']) && in_array($item['name'], $keys)) {
            $html[$item['name']] = build_item_form($item);
        }
    }
    return $html;
}

function replace_form($data, $html) {
    foreach ($data as $key => $item) {
        $html = str_replace('{' . $key . '}', $item, $html);
    }
    return $html;
}

function get_validate_type($type) {
    $type_validate = '';
    switch ($type) {
        case 'date':
            $type_validate = 'date';
            break;
        case 'number':
            $type_validate = 'numeric';
            break;
        case 'url':
            $type_validate = 'valid_url';
            break;
    }
    return $type_validate;
}

function get_validate_item($validate, $type) {
    $item_rule = array();
    foreach ($validate as $key => $item) {
        if ($key == 'required') {
            $item_rule[] = 'required';
        }
        if (in_array($key, array('minlength', 'maxlength'))) {
            $item_rule[] = ($key == 'minlength' ? 'min_len' : 'max_len') . ",{$item}";
        }
    }
    if (get_validate_type($type)) {
        $item_rule[] = get_validate_type($type);
    }
    if (!empty($item_rule)) {
        return implode('|', $item_rule);
    }
    return $item_rule;
}

function get_validate_form($form, $data) {
    $form = json_decode($form, true);
    $all_rules = array();
    foreach ($form as $item) {
        $validate = array_filter($item['validate']);
        if (in_array($item['name'], $data)) {
            $all_rules[$item['name']] = get_validate_item($validate, $item['type']);
        }
    }
    $maxlength = (get_configuration('maximum_characters', 'pf_comment') > 0 ? get_configuration('maximum_characters', 'pf_comment') : 255);
    if (!empty($all_rules)) {
        $all_rules['message'] = 'required|max_len,' . $maxlength;
    }
    return array_filter($all_rules);
}

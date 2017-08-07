<?php

include_once dirname(dirname(dirname(dirname(dirname(dirname(dirname(__FILE__))))))) . '/app/configs/config.php';
defined('PF_VERSION') OR header('Location:404.html');
require ABSPATH . '/lib/functions.php';
require ABSPATH . '/lib/pf-class.php';
require ABSPATH . '/lib/option.php';
require dirname(dirname(__FILE__)) . '/include/pf-plugin-csrf.php';

//
function get_value_setting($key) {
    $media_config = get_option('settings');
    $result = '';
    if (isset($media_config['pf_media'][$key])) {
        $result = $media_config['pf_media'][$key];
    }
    return $result;
}

function convert_to_array($key) {
    $str = get_value_setting($key);
    $result = array();
    if ($str != '') {
        $result = explode(',', $str);
    }
    return $result;
}

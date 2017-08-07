<?php

/**
 * Cấu hình mặc định cho shortcode player
 * @return array
 */
function player_default_attrs() {
    return array(
        'title' => '',
        'subtitle' => '',
        'summary' => '',
        'permalink' => '',
        'poster' => '',
        'chapters' => '',
        'width' => 'auto',
        'startVolume' => '0.8',
        'loop' => false,
        'preload' => true,
        'autoplay' => false,
        'allwaysShowHours' => true,
        'alwaysShowControls' => true,
        'progreess' => false,
        'summaryVisible' => false,
        'timecontrolsVisible' => false,
        'sharebuttonsVisible' => false,
        'chaptersVisible' => false,
        'fullscreen'    => true
    );
}

/**
 * Xóa những option không hợp lệ
 * @param type $attrs
 * @return type
 */
function player_clean_attrs($attrs) {
    $default = player_default_attrs();
    $attrs_keys = array_keys($attrs);
    $diff_keys = array_diff_key($attrs_keys, array_keys($default));
    if (!empty($diff_keys)) {
        $attrs = array_diff($attrs, array_flip($diff_keys));
    }
    return array_merge($default, $attrs);
}

/**
 * Lấy nguồn của media
 * @param type $attrs
 * @param type $allow_type
 * @param type $type_default
 * @return type
 */
function player_get_source($attrs, $allow_type, $type_default = 'mp3') {
    $source = array();
    if (!empty($attrs['src'])) {
        $type = isset($attrs['type']) ? $attrs['type'] : $type_default;
        $source[$type] = array(
            'src' => filter_var($attrs['src'], FILTER_VALIDATE_URL) === FALSE ? public_base_url()  . $attrs['src'] : $attrs['src']
        );
    } else {
        foreach ($allow_type as $item) {
            if (!isset($attrs[$item])) {
                continue;
            }
            $source[$item] = array(
                'src' => $attrs[$item]
            );
        }
    }
    return $source;
}

/**
 * Lấy về type audio (Audio tag)
 * @param type $item
 * @return type
 */
function get_type_audio($item) {
    switch ($item) {
        case 'ogg':
            $type = 'audio/ogg; codecs=vorbis';
            break;
        case 'opus':
            $type = 'audio/ogg; codecs=opus';
            break;
        default:
            $type = "audio/{$item}";
            break;
    }
    return $type;
}

/**
 * Javascript của player
 * @param type $id
 * @param type $config
 * @return string
 */
function get_javascript_player($id, $config) {
    $javascript = '<script>';
    $javascript .= "var $id = " . json_encode($config) . ";";
    $javascript .= 'window.setTimeout(function() {jQuery("#' . $id . '").podlovewebplayer(' . $id . ');}, 200);';
    $javascript .='</script>';
    return $javascript;
}

<?php

if (!function_exists('theme_input_color')) {

    function theme_input_color($name, $value) {
        $ipTmp = '<div class="input-group input-max-width">';

        $ipTmp .= '<input class="form-control inline color-picker" type="text"';
        $ipTmp .= 'name="' . $name . '" id="' . $name . '"';
        $ipTmp .= 'data-color="' . $value . '" value ="' . $value . '">';

        $ipTmp .= '<span class="input-group-btn color-picker"';
        $ipTmp .= 'data-color="' . $value . '" style="background-color:' . $value . ';"></span>';
        $ipTmp .= '</div>';

        return $ipTmp;
    }

}

if (!function_exists('theme_input_get_image')) {

    function theme_input_get_image($name, $value) {
        $ipTmp = '<div class="input-group">';
        $ipTmp .= '<input class="form-control" type="text" id="' . $name . '" name="' . $name . '" value="' . $value . '">';
        $ipTmp .= '<span class="input-group-btn">';
        $ipTmp .= '<a class="btn btn-default boxGetFile" type="button"';
        $ipTmp .= 'href="' . RELATIVE_PATH . '/app/plugins/default/media/filemanager/dialog.php?type=1&field_id=' . $name . '">';
        $ipTmp .= __('Select image', 'page') . '</a>';
        $ipTmp .= '</span>';
        $ipTmp .= '</div>';
        return $ipTmp;
    }

}

if (!function_exists('theme_background_position')) {

    function theme_background_position($name, $value) {
        $ipTmp = '<th><label class="control-label" for="' . $name . '">' . __('Background position', 'page') . '</label></th>';
        $ipTmp .= '<td>';
        $ipTmp .= '<input class="form-control" type="text" id="' . $name . '" name="' . $name . '" value="' . $value . '" >';
        $ipTmp .= '</td>';
        return $ipTmp;
    }

}

if (!function_exists('theme_background_repeat')) {

    function theme_background_repeat($name, $value) {
        $tmp_repeats = array(
            'repeat' => __('Repeat', 'page'),
            'repeat-x' => __('Repeat-x', 'page'),
            'repeat-y' => __('Repeat-y', 'page'),
            'no-repeat' => __('No-repeat', 'page')
        );
        $ipTmp = '<th><label class="control-label">' . __('Background repeat', 'page') . '</label></th>';
        $ipTmp .= '<td><div class="input-group">';
        foreach ($tmp_repeats as $r_key => $r_value) {
            $ipTmp .= '<div class="radio inline">';
            $ipTmp .= '<label for="r_' . $r_key . $name . '">';
            $ipTmp .= '<input type="radio" name="' . $name . '" id="r_' . $r_key . $name . $name . '"';
            $ipTmp .= 'value="' . $r_key . '" ' . (($value == $r_key) ? 'checked="checked"' : '') . '>' . $r_value;
            $ipTmp .= '</label>';
            $ipTmp .= '</div>';
        }

        $ipTmp .= '</div></td>';
        return $ipTmp;
    }

}
if (!function_exists('theme_background_attachment')) {


    function theme_background_attachment($name, $value) {
        $ipTmp = '<th><label class="control-label">' . __('Background attachment', 'page') . '</label></th>';
        $ipTmp .= '<td>';

        $ipTmp .= '<div class="radio inline">';
        $ipTmp .= '<label>';
        $ipTmp .= '<input type="radio" name="' . $name . '" value="scroll" ' . (($value == 'scroll') ? ' checked="checked"' : '') . '/>';
        $ipTmp .= __('Scroll', 'page');
        $ipTmp .= '</label>';
        $ipTmp .= '</div>';

        $ipTmp .= '<div class="radio inline">';
        $ipTmp .= '<label>';
        $ipTmp .= '<input type="radio" name="' . $name . '" value="fixed" ' . (($value == 'fixed') ? ' checked="checked"' : '') . '/>';
        $ipTmp .= __('Fixed', 'page');
        $ipTmp .= '</label>';
        $ipTmp .= '</div>';

        return $ipTmp;
    }

}
if (!function_exists('theme_background')) {

    function theme_background($name, $value) {
        $tmp_repeats = array(
            'color' => __('Color', 'page'),
            'image' => __('Image', 'page'),
        );
        $ipTmp = '<th><label class="control-label">' . __('Background type', 'page') . '</label></th>';
        $ipTmp .= '<td><div class="input-group">';
        foreach ($tmp_repeats as $r_key => $r_value) {
            $ipTmp .= '<div class="radio inline">';
            $ipTmp .= '<label for="bg-c-' . $r_key . $name . '">';
            $ipTmp .= '<input type="radio"  class="theme-bg-option" name="' . $name . '" id="bg-c-' . $r_key . $name . '"';
            $ipTmp .= 'value="' . $r_key . '" ' . (($value == $r_key) ? 'checked="checked"' : '') . '>' . $r_value;
            $ipTmp .= '</label>';
            $ipTmp .= '</div>';
        }
        $ipTmp .= '<div class="radio inline">';
        $ipTmp .= '<label for="bg-c-both-' . $name . '">';
        $ipTmp .= '<input type="radio"  class="theme-bg-option" name="' . $name . '" id="bg-c-both-' . $name . '"';
        $ipTmp .= 'value="both" ' . (($value != 'color' && $value != 'image') ? 'checked="checked"' : '') . '> ' . __('Both', 'page');
        $ipTmp .= '</label>';
        $ipTmp .= '</div>';
        $ipTmp .= '</div></td>';
        return $ipTmp;
    }

}

if (!function_exists('get_theme_background')) {

    function get_theme_background($type, $color, $image, $position, $repeat, $attachment = null) {
        $css = null;
        if ($type == 'color') {
            if ($color != '') {
                $css .= 'background-color:' . $color . ';';
            } else {
                $css .= 'background-color:transparent;';
            }
        } else {
            if (($image != '')) {
                if (preg_match('/^http/', $image)) {
                    $css .= 'background-image: url(\'' . $image . '\');';
                } else {
                    $css .= 'background-image: url(\'' . site_url() . RELATIVE_PATH . '/' . $image . '\');';
                }
            }
            $css .= ($repeat != '') ? 'background-repeat: ' . $repeat . ';' : '';
            $css .= ($position != '') ? 'background-position: ' . $position . ';' : '';
            $css .= ($attachment != '') ? 'background-attachment: ' . $attachment . ';' : '';
            if ($type == 'both') {
                $css .= ($color != '') ? 'background-color: ' . $color . ';' : 'background-color:transparent;';
            } else {
                $css .= 'background-color:transparent;';
            }
        }
        return $css;
    }

}
<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
$theme_info = array (
        'name' => 'Jupiter',
        'version' => '1.0',
        'description' => 'This is an Jupiter theme description' 
);
function theme_breadcrumb($data) {
    if (trim ( $data ) != '') {
        return $data;
    }
    
    require dirname ( __FILE__ ) . '/breadcrumb.php';
    return $data;
}
function all_children_menu(& $childrens, $menu_tree) {
    foreach ( $menu_tree as $k => $v ) {
        if (empty ( $v ['children'] )) {
            $childrens [$v ['id']] = $v ['id'];
        } else {
            all_children_menu ( $childrens, $v ['children'] );
        }
    }
}
function theme_logo($option) {
    if ($option ['logo_type'] == 'text') {
        $logoImage = '<span';
        $logoImage .= ' style="';
        if (!empty($option['font_family_logo']))
            $logoImage .= 'font-family:' . $option['font_family_logo'].';';
        if (!empty($option['font_size_logo']))
            $logoImage .= 'font-size:' . $option['font_size_logo'].';';
        if (!empty($option['color_logo']))
            $logoImage .= 'color:' . $option['color_logo'].';';
        $logoImage .= ';">';
        $logoImage .= isset ( $option ['text_logo'] ) ? $option ['text_logo'] : 'Vitubo';
        $logoImage .= '</span>';
    } else {
        $url = $option ['image_logo'];
        if (! preg_match ( '/^http/', $url )) {
            $relative = RELATIVE_PATH;
            if (empty ( $relative )) {
                $url = site_url () . '/' . $url;
            } else {
                $url = site_url () . "{$relative}/{$url}";
            }
        }
        $logoImage = '<img src="' . $url . '"';
        $logoImage .= ' style="';
        $logoImage .= 'width:' . (isset ( $option ['image_width_logo'] ) ? $option ['image_width_logo'] : '100%') . ';';
        $logoImage .= 'height:' . (isset ( $option ['image_height_logo'] ) ? $option ['image_height_logo'] : '100%');
        $logoImage .= ';">';
    }
    
    return $logoImage;
}
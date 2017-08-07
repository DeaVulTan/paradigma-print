<?php
defined('PF_VERSION') OR header('Location:404.html');
function get_themes($from_folder){
    $themes = array();
    if (is_dir ( $from_folder )) {
        if ($handle = @opendir ( $from_folder )) {
            while ( $file = readdir ( $handle ) ) {
                if (is_file ( $from_folder . '/' . $file )) {
                    /*if (strpos ( $from_folder . '/' . $file, '.php' )) {
                        $theme_info = get_theme_info($from_folder . '/' . $file);
                        eval($theme_info);
                        $themes[$file] = $theme_info;
                    }*/
                } else if ((is_dir ( $from_folder . '/' . $file )) && ($file != '.') && ($file != '..') && ($file != '.svn')) {
                    if (is_file ( $from_folder . '/' . $file . '/' . $file . '.php' )) {
                        $theme_info = get_theme_info($from_folder . '/' . $file . '/' . $file . '.php');
                        eval($theme_info);
                        $themes[$file . '/' . $file . '.php'] = $theme_info;
                    }
                }
            }
            closedir ( $handle );
        }
    }

    return $themes;
}

function get_theme_info($file) {
    $fp = fopen ( $file, 'r' );
    $file_data = fread ( $fp, 8192 );
    fclose ( $fp );
    $file_data = str_replace ( "\r", "\n", $file_data );

    preg_match( '/\$theme_info[^;]*;/si', $file_data, $match );

    return (!empty($match[0]))?$match[0]:'';
}
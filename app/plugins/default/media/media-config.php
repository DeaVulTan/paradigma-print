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
require_once ABSPATH . '/lib/common/plugin/utiles/pf-plugin-setting.php';
$setting = Pf::setting();
$setting->add_title('pf_media', __('Media settings', 'media'));
$util_setting = new Pf_Plugin_Setting;
$default = array(
    'storage_path' => '',
    'maximum_size_upload' => 2,
    'ext_image' => 'jpg,png,gif',
    'ext_audio' => 'mp3,ogg,wav',
    'ext_video' => 'mp4,mov,mpeg',
    'ext_file' => 'doc,docx,xls,csv',
    'ext_misc' => 'zip,rar',
    'hidden_folder' => 'thumbnails,thumbs',
    'hidden_file' => '',
    'allow_java' => false,
    'allow_resize_image' => false,
);
$settings = get_option('settings');
if(!is_array($settings['pf_media'])){
    $settings['pf_media'] = array();
}
$setting_media = array();
if (!empty($settings['pf_media'])) {
    foreach ($settings['pf_media'] as $k => $v) {
        $setting_media[$k] = $v;
    }
}
if (empty($setting_media)) {
    $settings['pf_media'] = $default;
} else {
    $settings['pf_media'] = array_merge($default, $setting_media);
}
//update_option('settings', $settings);
$util_setting->set_name('pf_media');
$util_setting->add_element_input(__('Path (Ex: uploads/)', 'media'), 'storage_path');
$util_setting->add_element_input(__('Maximum size of upload file (MB)', 'media'), 'maximum_size_upload');
$util_setting->add_element_input(__('Image file extention (jpg, png, gif...)', 'media'), 'ext_image');
$util_setting->add_element_input(__('Audio file extention (mp3, ogg, wav...)', 'media'), 'ext_audio');
$util_setting->add_element_input(__('Video file extention file(mp4, mov, mpeg...)', 'media'), 'ext_video');
$util_setting->add_element_input(__('Document file extention', 'ext_file', 'media'), 'ext_file');
$util_setting->add_element_input(__('Misc file extention', 'ext_misc', 'media'), 'ext_misc');
$util_setting->add_element_input(__('Hidden folder (do not remove: thumbnails)', 'media'), 'hidden_folder');
$util_setting->add_element_input(__('Hidden file', 'media'), 'hidden_file');
$util_setting->add_element_radio(__('Allow image resize when upload', 'media'), 'allow_resize_image');
$util_setting->add_element_input(__('Image width','media'), 'allow_resize_image_width');
$util_setting->add_element_input(__('Image height','media'), 'allow_resize_image_height');

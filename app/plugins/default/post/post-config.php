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
$setting->add_title('pf_post', __('Post settings', 'post'));
$util_setting = new Pf_Plugin_Setting;
$util_setting->set_name('pf_post');

$util_setting->add_element_radio(__('Enable comment?', 'post'), 'enable_comment', get_configuration('enable', 'pf_comment') ? '' : 'DISABLED = "true"');
$util_setting->add_element_radio(__('Enable rating?', 'post'), 'enable_rating', get_configuration('enable', 'pf_rating') ? '' : 'DISABLED = "true"');
$util_setting->set_data(array(
    1 => __('Newest first', 'configuration'),
    2 => __('Oldest first', 'configuration')
));
$util_setting->add_element_dropdown(__('Ordering', 'post'), 'ordering');
$util_setting->add_element_input(__('Maximum number of tags', 'post'), 'maximum_tag');
$util_setting->add_element_input(__('Length of description', 'post'), 'length_of_description');
$util_setting->set_data(Pf_Plugin_Singleton::list_page());
$util_setting->add_element_dropdown(__('Post listing page', 'post'), 'page_lists');
$util_setting->add_element_dropdown(__('Post detail page', 'post'), 'page_detail');

$util_setting->add_element_radio(__('Show post title on URL', 'post'), 'show_title_url');
$util_setting->add_element_radio(__('Show category name on URL', 'post'), 'show_category_name_url');
//$util_setting->add_element_radio(__('Show tag name on URL', 'post'), 'show_tag_name_url');
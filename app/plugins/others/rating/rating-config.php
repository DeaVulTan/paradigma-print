<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<?php

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
require_once ABSPATH . '/lib/common/plugin/helpers/helper.php';
$setting = Pf::setting();
$setting->add_title('pf_rating', __('Rating settings', 'rating'));
$util_setting = new Pf_Plugin_Setting;

$util_setting->set_name('pf_rating');
$util_setting->set_data(array(__('Only user'), __('Everyone')));
$util_setting->add_element_radio(__('Enable rating?', 'rating'), 'enable');
$util_setting->add_element_dropdown(__('Permission', 'rating'), 'permission');
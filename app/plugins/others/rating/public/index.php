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
require_once ABSPATH . '/lib/common/plugin/load.php';
require_once dirname(dirname(__FILE__)) . '/helper.php';
$shortcode = new Pf_Plugin_Shortcode_Bootstrap('rating', '/app/plugins/others/rating', 'pf-rating-model', $code, $atts);
return $shortcode->start();


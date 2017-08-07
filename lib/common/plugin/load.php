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
$common_plugin = ABSPATH . '/lib/common/plugin';
require_once ABSPATH . '/lib/Swift/lib/swift_required.php';
require_once $common_plugin . '/helpers/security.php';
require_once $common_plugin . '/helpers/helper.php';

$libraries = array(
    'pf-plugin-bootstrap.php',
    'pf-plugin-builder.php',
    'pf-plugin-model.php',
    'pf-plugin-view.php',
    'pf-plugin-controller.php',
    'pf-plugin-shortcode-bootstrap.php',
    'pf-plugin-shortcode-controller.php',
    'pf-plugin-shortcode-view.php'
);

foreach ($libraries as $library) {
    if (file_exists("{$common_plugin}/libraries/{$library}")) {
        require_once "{$common_plugin}/libraries/{$library}";
    }
}

$utiles = array(
    'pf-plugin-session.php',
    'pf-plugin-csrf.php',
    'pf-plugin-input.php',
    'pf-plugin-security.php',
    'pf-plugin-redirect.php',
    'pf-plugin-setting.php',
    'pf-plugin-mail.php'
);

foreach ($utiles as $util) {
    if (file_exists("{$common_plugin}/utiles/{$util}")) {
        require_once "{$common_plugin}/utiles/{$util}";
    }
}
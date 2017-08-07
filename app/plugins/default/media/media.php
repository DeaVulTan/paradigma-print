<?php

defined('PF_VERSION') OR header('Location:404.html');
define('PLUGIN_MEDIA', __('Media', 'media'));

class Media_Plugin extends Pf_Plugin
{

    public $name = PLUGIN_MEDIA;
    public $version = '1.0';
    public $author = 'Vitubo Team';
    public $description = 'This is the Media description';

    public function activate()
    {
        
    }

    public function deactivate()
    {
        
    }

    public function admin_init()
    {
        require_once abs_plugin_path(__FILE__) . '/media/media-config.php';
        require ABSPATH . '/lib/common/plugin/helpers/permission.php';
        if (plugin_check_acl(array(1, 2))) {
            $this->admin_menu('fa fa-file', __('Media', 'media'), 'media', 'plugin_media_manager');
        }
    }

    function plugin_media_manager()
    {
        $this->js('media/assets/fancybox/jquery.fancybox-1.3.6.pack.js');
        $this->css('media/assets/fancybox/jquery.fancybox-1.3.6.css');
        $this->js('media/assets/media.js', __FILE__);
        require_once abs_plugin_path(__FILE__) . '/media/index.php';
    }

}

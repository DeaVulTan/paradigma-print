<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<?php

define('PLUGIN_RATING', __('Rating', 'rating'));

class Rating_Plugin extends Pf_Plugin
{

    public $name = PLUGIN_RATING;
    public $version = '1.0';
    public $author = 'Vitubo Team';
    public $description = 'This is the Rating description';

    public function activate()
    {
        $db = Pf::database();
        $db->query("CREATE TABLE `".DB_PREFIX."ratings` (
            `id` int(11) NOT NULL AUTO_INCREMENT,
            `rating_rate` float NOT NULL,
            `rating_author` varchar(40) NOT NULL,
            `rating_key` varchar(50) NOT NULL,
            PRIMARY KEY (`id`)
           ) ENGINE=InnoDB AUTO_INCREMENT=1 DEFAULT CHARSET=utf8;");
    }

    public function admin_init()
    {
        require dirname(__FILE__) . '/rating-config.php';
    }

    public function deactivate()
    {
        $db = Pf::database();
        $db->query("DROP TABLE ".DB_PREFIX."ratings");
    }

    //Public
    public function public_init()
    {
        $shortcode = Pf::shortcode();
        $shortcode->add('rating', array($this, 'plugin_rating_public'));
    }

    function plugin_rating_public($atts, $content = null, $code = '')
    {
        $output = require abs_plugin_path(__FILE__) . '/rating/public/index.php';
        return $output;
    }

}

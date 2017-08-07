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
define('DB_RATINGS', DB_PREFIX.'ratings');
class Pf_Rating_Model extends Pf_Plugin_Model {

    protected $table = DB_RATINGS;
    protected $prefix_column = 'rating';
    protected $primary_key = 'id';

}

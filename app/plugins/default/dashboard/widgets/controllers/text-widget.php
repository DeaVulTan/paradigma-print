<?php
defined('PF_VERSION') OR header('Location:404.html');
/**
 * 
 * @package		Vitubo
 * @author		Vitubo Team 
 * @copyright	Vitubo Team
 * @link		http://www.vitubo.com
 * @since		Version 1.0
 * @filesource
 *
 */
class Text_Widget extends Pf_Widget{
    public $name = 'Text';
    public $version = '1.0';
    public $description = 'This is description';
    
    public function __construct($properties,$setting) {
        parent::__construct ( $properties,$setting );
    }
    
    public function index(){
        $this->view->render();
    }
    
    public function setting(){
        $this->view->render();
    }
    
}
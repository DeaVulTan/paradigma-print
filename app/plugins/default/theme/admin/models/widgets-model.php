<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Widgets_Model extends Pf_Model {
    
    public function __construct() {
        parent::__construct ( ''.DB_PREFIX.'options');
    }
    public $elements_value = array(
            'action' =>
            array(
                    'Activate' => 'Activate',
                    'Deactivate' => 'Deactivate'
            ),
    );
}
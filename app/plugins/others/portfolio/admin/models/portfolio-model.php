<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Portfolio_Model extends Pf_Model{
    
        public $rules = array(
			'portfolio_name'=>'required',
			'portfolio_category'=>'required',
			'portfolio_avatar'=>'required',
		);
    
        public $elements_value = array(
            'portfolio_status' => 
                array(
                    '1' => 'Published',
                    '0' => 'Unpublished',
                ),
        );
    
    public function __construct(){
        parent::__construct(DB_PREFIX.'portfolios');
    }
}
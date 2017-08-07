<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Polls_Model extends Pf_Model{
    
        public $rules = array(
			'polls_question'=>'required|max_len,255',
            'pollq_multiple' => 'numeric|max_len,2'
		);
    
        public $elements_value = array(
                'polls_pubdate' => 'YYYY-MM-DD H:m:s',
                'polls_unpubdate' => 'YYYY-MM-DD H:m:s',
                'polls_status' => 
                    array(
                        '1' => 'Published ',
                        '0' => 'Unpublished ',
                    ),
            );

    
    public function __construct(){
        parent::__construct(DB_PREFIX.'polls_questions');
    }
}
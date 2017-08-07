<?php
abstract class Pf_Widget extends Pf_Controller{
    public $controller_type = 'widgets';
    
    protected $id ='';
    protected $properties = array();
    protected $setting = array();
    
    public function __construct($properties,$setting_data){
        parent::__construct();
        $this->properties = $properties;
        if (!empty($properties['name'])){
            $this->id = $properties['name'];
            if (!empty($setting_data[$properties['name']])){
                $this->setting = $setting_data[$properties['name']];
            }
        }
    }
    
    public function activate(){
    
    }
    
    public function deactivate(){
    
    }
    
    public function setting(){
        $html = '
        <form role="form" class="form-horizontal">
              <div class="form-group" style="margin-bottom:0">
                <label for="title" class="col-sm-2 control-label">Title</label>
                <div class="col-sm-10">'.form_input(array('name'=>'title','id'=>'title')).'</div>
              </div>
        </form>';
    
        echo $html;
    }
    
    public function index(){
    
    }
}
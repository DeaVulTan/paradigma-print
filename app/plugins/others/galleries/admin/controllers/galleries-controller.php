<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Galleries_Controller extends Pf_Controller{
    public function __construct(){
        parent::__construct();
        $this->load_model('galleries');
        $this->view->menu_settings = get_option('admin_menu_setting');
        $this->galleries_model->rules = Pf::event()->trigger("filter","galleries-validation-rule",$this->galleries_model->rules);
    }
    public function index(){
        $params = array();
        $where = '';
        $where_values = array();
        $operator = '';
        
        $params['limit'] = NUM_PER_PAGE;
        $params['page_index'] = (isset($this->get->{$this->page}))?(int)$this->get->{$this->page}:1;
        
        
        $operator = '';
        
        if (empty($this->get->search)){
                $this->get->search = array();
        }

        if (isset($this->get->search["gallery_name"]) && trim($this->get->search["gallery_name"]) != ""){
            $where .= $operator.' `gallery_name` like ? ';
            $where_values[] = '%'.$this->get->search["gallery_name"].'%';
            $operator = ' AND ';
        }
        //view all status
        if (isset($this->post->action) && trim($this->post->action) != ""){
        	$where .= $operator.' `gallery_status` = ? ';
        	$where_values[] = $this->post->id;
        }
        //search 
        if (isset($this->get->search["gallery_status"])){
        	if (is_array($this->get->search["gallery_status"])){
        		if (!empty($this->get->search["gallery_status"])){
        			$where .= $operator.' ( ';
        			$operator1 = '';
        			foreach($this->get->search["gallery_status"] as $v){
        				$where .= $operator1.'  `gallery_status` = ?  OR `gallery_status` like ?  OR `gallery_status` like ? OR `gallery_status` like ? ';
        				$where_values[] = $v;
        				$where_values[] = $v.',%';
        				$where_values[] = '%,'.$v.',%';
        				$where_values[] = '%,'.$v;
        				$operator1 = ' OR ';
        			}
        			$where .= ' ) ';
        			$operator = ' AND ';
        		}
        	}else{
        		if (trim($this->get->search["gallery_status"]) != ""){
        			$where .= $operator.' (  `gallery_status` = ?  OR `gallery_status` like ?  OR `gallery_status` like ? OR `gallery_status` like ? )';
        			$where_values[] = $this->get->search["gallery_status"];
        			$where_values[] = $this->get->search["gallery_status"].',%';
        			$where_values[] = '%,'.$this->get->search["gallery_status"].',%';
        			$where_values[] = '%,'.$this->get->search["gallery_status"];
        			$operator = ' AND ';
        		}
        	}
        }              
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
        
        
        if (!empty($this->get->order_field) && !empty($this->get->order_type)){
            $params["order"] = "`".Pf::database ()->escape($this->get->order_field)."` ".Pf::database ()->escape($this->get->order_type);
        }

        $params = Pf::event()->trigger("filter","galleries-index-params",$params);

        
        $this->view->page_index = $params['page_index'];
        $this->view->records = $this->galleries_model->fetch($params,true);
        $this->view->total_records = $this->galleries_model->found_rows();
        $total_page = ceil($this->view->total_records/NUM_PER_PAGE);
        if (empty($this->view->records) && $total_page > 0){
            $this->get->{$this->page} = $params['page_index'] = $total_page; 
            $this->view->page_index = $params['page_index'];
            $this->view->records = $this->galleries_model->fetch($params,true);
            $this->view->total_records = $this->galleries_model->found_rows();
        }
        $this->view->pagination = new Pf_Paginator($this->view->total_records, NUM_PER_PAGE, $this->page);
        
        $template = null;
        $template = Pf::event()->trigger("filter","galleries-index-template",$template);
        
        $this->view->render($template);
    }
    
    public function add(){
        $this->galleries_model->rules = Pf::event()->trigger("filter","galleries-adding-validation-rule",$this->galleries_model->rules);
        
        $template = null;
        $template = Pf::event()->trigger("filter","galleries-add-template",$template);
        
        if ($this->request->is_post()){
            $data = array();
         		
                $data["gallery_name"] = $this->post->{"gallery_name"};
                $data["gallery_status"] = $this->post->{"gallery_status"};
                $data["gallery_description"] = $this->post->{"gallery_description"};             
                if (!empty($_POST['image_url'])) {
                	foreach ($_POST['image_url'] as $k => $v) {
                		if (empty($v)) {
                		    unset($_POST['image_url'][$k]);
                			unset($_POST['image_title'][$k]);
                			unset($_POST['image_alt'][$k]);
                		}
                	}
                	$data["gallery_data"] = serialize((array(array_values($_POST['image_url']), array_values($_POST['image_title']), array_values($_POST['image_alt']))));
                }
            $data = Pf::event()->trigger("filter","galleries-post-data",$data);
            $data = Pf::event()->trigger("filter","galleries-adding-post-data",$data);
            
            $var = array();
            $data["gallery_cover"] = $this->post->{"cover"};
            unset($data['image_url']);
            unset($data['image_alt']);
            unset($data['image_title']);
            unset($data['cover']);
            if (!$this->galleries_model->insert($data)){
                $this->view->errors = $this->galleries_model->errors;                
                $var['error'] = 1;
            }else{
                Pf::event()->trigger("action","galleries-add-successfully",$this->galleries_model->insert_id(),$data);
                $var['error'] = 0;
                $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
            }
            
            echo json_encode($var);
        }else{
        
            $this->view->render($template);
        }
    }
    
    public function edit(){
        $this->galleries_model->rules = Pf::event()->trigger("filter","galleries-editing-validation-rule",$this->galleries_model->rules);
        $template = null;
        $template = Pf::event()->trigger("filter","galleries-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                $data['id'] = $this->get->id;
                
                $data["gallery_name"] = $this->post->{"gallery_name"};
                $data["gallery_status"] = $this->post->{"gallery_status"};
                $data["gallery_description"] = $this->post->{"gallery_description"};               
                if (!empty($_POST['image_url'])) {
                	foreach ($_POST['image_url'] as $k => $v) {
                		if (empty($v)) {
                			unset($_POST['image_url'][$k]);
                			unset($_POST['image_title'][$k]);
                			unset($_POST['image_alt'][$k]);
                		}
                	}
                	$data["gallery_data"] = serialize((array(array_values($_POST['image_url']), array_values($_POST['image_title']), array_values($_POST['image_alt']))));
                }
                
                $data = Pf::event()->trigger("filter","galleries-post-data",$data);
                $data = Pf::event()->trigger("filter","galleries-editing-post-data",$data);
                
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                $data["gallery_cover"] = $this->post->{"cover"};
                unset($data['image_url']);
                unset($data['image_alt']);
                unset($data['image_title']);
                unset($data['cover']);
                if (!$this->galleries_model->save($data)){
                    $this->view->errors = $this->galleries_model->errors;                
                    $var['error'] = 1;
                }else{
                	$params = array();
                	$params['where'] = array('id=?',array((int)$this->get->id));
                	$data = $this->galleries_model->fetch_one($params);
                	
                    Pf::event()->trigger("action","galleries-edit-successfully",$data);
                    $var['error'] = 0;
                    $this->post->datas($data);
                    $var['content'] = $this->view->fetch($template);
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->galleries_model->fetch_one($params);
                    
                    $data = Pf::event()->trigger("filter","galleries-database-data",$data);
                    $data = Pf::event()->trigger("filter","galleries-editing-database-data",$data);
                    $this->post->datas($data);
                    $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 0;
                }else{
                    $var['error'] = 1;
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }
        }else{
            $var['error'] = 1;
            $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
        }
        
        echo json_encode($var);
    }
    
    public function copy(){
        $this->galleries_model->rules = Pf::event()->trigger("filter","galleries-copy-validation-rule",$this->galleries_model->rules);
    
        $template = null;
        $template = Pf::event()->trigger("filter","galleries-edit-template",$template);
        
        $var = array();
        
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->request->is_post()){
                $data = array();
                
            	$data["gallery_name"] = $this->post->{"gallery_name"};
                $data["gallery_status"] = $this->post->{"gallery_status"};
                $data["gallery_description"] = $this->post->{"gallery_description"};             
                if (!empty($_POST['image_url'])) {
                	foreach ($_POST['image_url'] as $k => $v) {
                		if (empty($v)) {
                		    unset($_POST['image_url'][$k]);
                			unset($_POST['image_title'][$k]);
                			unset($_POST['image_alt'][$k]);
                		}
                	}
                	$data["gallery_data"] = serialize((array(array_values($_POST['image_url']), array_values($_POST['image_title']), array_values($_POST['image_alt']))));
                }
                
                $data = Pf::event()->trigger("filter","galleries-post-data",$data);
                $data = Pf::event()->trigger("filter","galleries-copy-post-data",$data);
                
                $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                
                $data["gallery_cover"] = $this->post->{"cover"};
                unset($data['image_url']);
                unset($data['image_alt']);
                unset($data['image_title']);
                unset($data['cover']);
                if (!$this->galleries_model->insert($data)){
                    $this->view->errors = $this->galleries_model->errors;                   
                    $var['error'] = 1;
                }else{
                    Pf::event()->trigger("action","galleries-copy-successfully",$data);
                    $var['error'] = 0;
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
                
            }else{
                if (isset($this->get->id)){
                    $params = array();
                    $params['where'] = array('id=?',array((int)$this->get->id));
                    $data = $this->galleries_model->fetch_one($params);
                    
                    
                    $data = Pf::event()->trigger("filter","galleries-database-data",$data);
                    $data = Pf::event()->trigger("filter","galleries-copy-database-data",$data);
                    
                    $this->post->datas($data);
                    $this->view->token = Pf_Plugin_CSRF::token($this->key.$this->get->id);
                    $var['content'] = $this->view->fetch($template);
                    $var['error'] = 0;
                }else{
                    $var['error'] = 1;
                    $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
                }
            }
        }else{
            $var['error'] = 1;
            $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
        }
        
        echo json_encode($var);
    }
    
    public function bulk_action(){
        $var = array();
        if (Pf_Plugin_CSRF::is_valid($this->post->token,$this->key)){
            switch ($this->get->type){
                case 'delete':
                    if (!empty($this->post->id) && is_array($this->post->id)){
                        foreach ($this->post->id as $id){
                            $this->galleries_model->delete('id=?',array($id));
                        }
                    }
                    break;
                    case 'publish':
                    
                    	if (!empty($this->post->id) && is_array($this->post->id)){
                    		foreach ($this->post->id as $id){
                    			$params['where'] = array('id=?',array($id));
                    			$data = $this->galleries_model->fetch_one($params);
                    			$data['gallery_status'] = 1;
                    			$this->galleries_model->save($data);
                    		}
                    	}
                    	$var['action'] = 'publish';
                    	break;
                    case 'unpublish':
                    	if (!empty($this->post->id) && is_array($this->post->id)){
                    		foreach ($this->post->id as $id){
                    			$params['where'] = array('id=?',array($id));
                    			$data = $this->galleries_model->fetch_one($params);
                    			$data['gallery_status'] = 2;
                    			$this->galleries_model->save($data);
                    		}
                    	}
                    	$var['action'] = 'unpublish';
                    	break;
            }
            Pf::event()->trigger("action","galleries-bulk-action-successfully",$this->get->type,$this->post->id);
            $var['error'] = 0;
        }else{
            $var['error'] = 1;
        }
        
        $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=&type=');
        
        echo json_encode($var);
    }
    
    public function delete(){
        $var = array();
        if (isset($this->get->id) && isset($this->get->token) && Pf_Plugin_CSRF::is_valid($this->get->token, $this->key.$this->get->id)){
            if ($this->galleries_model->delete('id=?',array($this->get->id))){
                $var['error'] = 0;
                Pf::event()->trigger("action","galleries-delete-successfully",$this->get->id);
            }else{
                $var['error'] = 1;
            }
        }
        
        $var['url'] = admin_url($this->action.'=index&ajax=&id=&token=');
        
        echo json_encode($var);
    }
    public function change_status() {
    	$data = array();
    	$status = $this->post->status;
    	$params = array();
    	$params['where'] = array('id=?',array((int)$this->post->id));
    	$data = $this->galleries_model->fetch_one($params);
    
    	switch ($status) {
    		case 'publish':
    			$data['gallery_status'] = 1;
    			$this->galleries_model->save($data);
    			break;
    		case 'unpublish':
    			$data['gallery_status'] = 2;
    			$this->galleries_model->save($data);
    			break;
    	}
    }
}
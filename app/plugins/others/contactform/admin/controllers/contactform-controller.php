<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Contactform_Controller extends Pf_Controller{
    
    public function __construct(){
        parent::__construct();
        $this->load_model('contactform');
        $this->view->menu_settings = get_option('admin_menu_setting');
        $this->contactform_model->rules = Pf::event()->trigger("filter","contactform-validation-rule",$this->contactform_model->rules);
    }
	public function index(){
		$params [] = array ();
		$where = '';
		$where_values = array ();
		$operator = '';
		$data = '';
		
		$params ['contactform'] = get_option ('contactform');
		$params ['limit'] = NUM_PER_PAGE;
		$params ['page_index'] = (isset ( $this->get->{$this->page} )) ? ( int ) $this->get->{$this->page} : 1;
		$operator = '';
		$url = admin_url('admin-page=contactform', false);
		//view all status
		if (isset ( $this->post->action ) && trim ( $this->post->action ) != "") {
		    $data_contactform = get_option ( 'contactform' );
			foreach ( $data_contactform as $key => $value ) {
				if ($this->post->id == $data_contactform [$key] ['status']) {
					$data [$key] = $data_contactform [$key];
				} else {
				}
			}
			//search
		}else{
			$data =  $params ['contactform'];
			if(isset($this->get->search["title"])){
				$title = $this->get->search["title"];
				$data = $this->search_by_key($data, $title, 'title');
				$url .= "&search[title]={$title}";
			}
		
			if(isset($this->get->search["status"])){
				$status = $this->get->search["status"];
				$data = $this->search_by_key($data, $status, 'status');
				$url .= "&search[status]={$title}";
			}
		}
		if (! empty ( $where_values )) {
			$params ['where'] = array (
					$where,
					$where_values
			);
		}
		$params = Pf::event ()->trigger ( "filter", "contactform-index-params", $params );
		if (!empty ( $this->get->order_field ) && !empty ( $this->get->order_type )) {
			if($this->get->order_field == 'title' && $this->get->order_type == 'asc'){
				asort($data);
			}else if($this->get->order_field == 'title' && $this->get->order_type == 'desc'){
				arsort($data);
			}
			if($this->get->order_field == 'status' && $this->get->order_type == 'asc'){
				uasort($data, "cmp");
			}else if($this->get->order_field == 'status' && $this->get->order_type == 'desc'){
				uasort($data, "cmp1");
			}
		}
		$this->view->page_index = $params ['page_index'];
		$this->view->records = $data;
		$this->view->total_records = count ( $params ['contactform'] );
		$total_page = ceil ( $this->view->total_records / NUM_PER_PAGE );
		$this->view->pagination = new Pf_Paginator ( $this->view->total_records, NUM_PER_PAGE, $this->page );
		if (!empty($this->view->records) && $total_page > 1){
			$tesst = $this->pagination($data,$params['page_index']);
			$this->view->records = $tesst;
		}else{
			$this->view->records = $data;
		}
		$template = null;
		$template = Pf::event()->trigger("filter","contactform-index-template",$template);
		$this->view->render($template);
	}
	public function find($id) {
		$data = get_option ( 'contactform' );
		if (is_array ( $data )) {
			foreach ( $data as $key => $value ) {
				if ($key == $id) {
					return $value;
				}
			}
		}
		return false;
	}
	public function clean_control_name($str)
	{
	    return strtolower(preg_replace(array('/[^a-zA-Z0-9-]/'), array(''), $str));
	}
	
	private function build_validator($atts)
	{
	    $validator = array();
	    $correct = array('email' => 'email', 'url' => 'url', 'number' => '', 'date' => '', 'text' => '', 'textarea' => '');
	    $att = array();
	    foreach ($atts as $item) {
	        $att = $item['attr'];
	        if (!isset($att['type']) || $att['type'] == '') {
	            return;
	        } elseif ($att['type'] === 'captcha') {
	            $att['name'] = 'captcha';
	        } elseif ($att['type'] === 'acceptance') {
	            $att['name'] = 'acceptance';
	        }
	        if (!isset($att['name']) || $att['name'] == '') {
	            continue;
	        }
	        if ($att['type'] == 'acceptance') {
	            $validator['acceptance'][] = 'required|boolean';
	            continue;
	        }
	        if ($att['type'] == 'captcha') {
	            $validator['captcha'] = 'captcha';
	            continue;
	        }
	        $key = $this->clean_control_name($att['name']);
	        if (isset($att['required']) && $att['required'] == 1) {
	            $validator[$key][] = 'required';
	        }
	        if ($att['type'] == 'date') {
	            $validator[$key][] = 'date';
	        }
	        if ($att['type'] == 'numeric') {
	            $validator[$key][] = 'numeric';
	        }
	        if (in_array($att['type'], array_keys($correct))) {
	            $correct[$att['type']] == '' ? '' : $validator[$key][] = 'valid_' . $correct[$att['type']];
	        }
	        if (isset($att['maxlength']) && is_numeric($att['maxlength'])) {
	            $validator[$key][] = 'max_len,' . $att['maxlength'];
	        }
	    }
	    return $validator;
	}
	private function get_validator($atts)
	{
	    $data = $this->build_validator($atts);
	    foreach ($data as $key => $item) {
	        if ($key == 'check') {
	            $data[$key] = $item;
	        } else {
	            $data[$key] = is_array($item) ? implode('|', $item) : $item;
	        }
	    }
	    return $data;
	}
	
	public function add(){
		$this->contactform_model->rules = Pf::event()->trigger("filter","contactform-adding-validation-rule",$this->contactform_model->rules);
		$template = null;
		$template = Pf::event()->trigger("filter","contactform-add-template",$template);
	    if ($this->request->is_post ()) {
	        $data = get_option ( 'contactform' );
	        if (! array (
	                $data 
	        )) {
	            $data = array ();
	        }
	        $rules = array();
	        $rules['to'] = $this->post->{'to'};
	        $rules['title'] = $this->post->{'title'};
	        $rules['from'] = $this->post->{'from'};
	        $rules['form'] = $this->post->{'form'};
	        $rules['name'] = $this->post->{'name'};
	        $rules['message'] = $this->post->{'message'};
	        $error = array();
	        $form = $this->post->{'form'};
	        $validator = Pf::shortcode()->scan($form);
	        $contactform = array (
	                'title' => $this->post->{'title'},
                    'status' => !empty($this->post->{'status'})? 1:0,
                    'form' => $this->post->{'form'},
                    'mail' => array(
                        'config' => array(
                            'to' => $this->post->{'to'},
                            'recipient' => $this->post->{'recipient'},
                            'from' => $this->post->{'from'},
                            'from_list' => $this->post->{'to_list'},
                            'name' =>  $this->post->{'name'},
                            'subject' => $this->post->{'subject'},
                            'use_as_html' => isset($this->post->{'use_as_html'}) ? 1 : 0
                        ),
                        'message' => $this->post->{'message'},
                        'notify' => $this->post->{'notify'},
                    ),
                    'validator' => $this->get_validator($validator)
	        );
	        if ($this->post->id) {
                $id = $this->post->id;
            } else {
                $id = generate_id ( 15 );
                while ( isset ( $data [$id] ) ) {
                    $id = generate_id ( 15 );
                }
            }
	        $data [$id] = $contactform;
	        $data = Pf::event ()->trigger ( "filter", "contactform-post-data", $data );
	        $data = Pf::event ()->trigger ( "filter", "contactform-adding-post-data", $data );
	        $var = array ();
	        $this->contactform_model->validate ($rules);
	        $errors = Pf::validator()->get_readable_errors(false);
	        foreach ($errors as $key => $value) {
	            $error[$key][0] = $errors[$key][0];
	        }
	        if (count($error) > 0) {
	            $this->view->errors =  $error;
	            $var['content'] = $this->view->fetch($template);
	            $var ['error'] = 1;
	        }else {
	            update_option('contactform', $data);
	            Pf::event ()->trigger ( "action", "contactform-add-successfully", $this->contactform_model->insert_id (), $data );
	            $var ['error'] = 0;
	            $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
	        }
	        echo json_encode ( $var );
	    } else {
	        $this->view->render ( $template );
	    }
	}
	
	public function edit(){
		$this->contactform_model->rules = Pf::event ()->trigger ( "filter", "contactform-editing-validation-rule", $this->contactform_model->rules );
		$template = null;
		$var = array ();
		$id = $this->get->id;
		$data_contactform = $this->find ( $id );
		$template = Pf::event()->trigger("filter","contactform-edit-template",$template);
		if (isset ( $this->get->id ) && isset ( $this->get->token )) {
	        if ($this->request->is_post ()) {
	            $data = get_option ( "contactform" );
	            if (! array (
	                    $data 
	            )) {
	                $data = array ();
	            }
	            $rules = array();
	            $rules['to'] = $this->post->{'to'};
	            $rules['title'] = $this->post->{'title'};
	            $rules['from'] = $this->post->{'from'};
	            $rules['form'] = $this->post->{'form'};
	            $rules['name'] = $this->post->{'name'};
	            $rules['message'] = $this->post->{'message'};
	            $error = array();
	            $form = $this->post->{'form'};
	            $validator = Pf::shortcode()->scan($form);
	            $contactform = array (
	                    'title' => $this->post->{'title'},
                        'status' => !empty($this->post->{'status'})? 1:0,
                        'form' => $this->post->{'form'},
                        'mail' => array(
                            'config' => array(
                                'to' => $this->post->{'to'},
                                'recipient' => $this->post->{'recipient'},
                                'from' => $this->post->{'from'},
                                'from_list' => $this->post->{'to_list'},
                                'name' =>  $this->post->{'name'},
                                'subject' => $this->post->{'subject'},
                                'use_as_html' => isset($this->post->{'use_as_html'}) ? 1 : 0
                            ),
                            'message' => $this->post->{'message'},
                            'notify' => $this->post->{'notify'},
                        ),
                        'validator' => $this->get_validator($validator),
	            );
	            if ($this->get->id) {
                    $id = $this->get->id;
                } else {
                    $id = generate_id(15);
                    while (isset($data[$id])) {
                        $id = generate_id(15);
                    }
                }
	            $data [$id] = $contactform;
	            $data = Pf::event ()->trigger ( "filter", "contactform-post-data", $data );
	            $data = Pf::event ()->trigger ( "filter", "contactform-editing-post-data", $data );
	            $this->contactform_model->validate ($rules);
	            $errors = Pf::validator()->get_readable_errors(false);
	            foreach ($errors as $key => $value) {
	                $error[$key][0] = $errors[$key][0];
	            }
	            $this->view->token = Pf_Plugin_CSRF::token ( $this->key . $this->get->id );
	            if (count($error) > 0) {
	                $this->view->errors =  $error;
	                $var ['content'] = $this->view->fetch ( $template );
	                $var ['error'] = 1;
	            } else {
	                update_option('contactform', $data);
	                $this->post->datas ( $data );
	                Pf::event ()->trigger ( "action", "contactform-edit-successfully", $data );
	                $var ['error'] = 0;
	                $var ['content'] = $this->view->fetch ($template);
	                $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
	            }
	        } else {
	            if (isset ( $this->get->id )) {
	                $data = $data_contactform;
	                $contactform =  $data;
	                $data['to'] = $contactform['mail']['config']['to'];
	                $data['message'] = $contactform['mail']['message'];
	                $data['from'] = $contactform['mail']['config']['from'];
	                $data['name'] =  $contactform['mail']['config']['name'];
	                $data['subject'] = $contactform['mail']['config']['subject'];
	                $data['notify'] = $contactform['mail']['notify'];
	                $data['to_list'] = $contactform['mail']['config']['from_list'];
	                $data['recipient'] = $contactform['mail']['config']['recipient'];
	                $data['use_as_html'] = $contactform['mail']['config']['use_as_html'];
	                $data = Pf::event ()->trigger ( "filter", "contactform-database-data", $data );
	                $data = Pf::event ()->trigger ( "filter", "contactform-editing-database-data", $data );
	                $this->post->datas ( $data );
	                $this->view->token = Pf_Plugin_CSRF::token ( $this->key . $this->get->id );
	                $var ['content'] = $this->view->fetch ( $template );
	                $var ['error'] = 0;
	            } else {
	                $var ['error'] = 1;
	                $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
	            }
	        }
	    } else {
	        $var ['error'] = 1;
	        $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
	    }
		echo json_encode($var);
	}
	
	public function copy(){
	$this->contactform_model->rules = Pf::event ()->trigger ( "filter", "contactform-copy-validation-rule", $this->contactform_model->rules );
    
    $template = null;
    $template = Pf::event ()->trigger ( "filter", "contactform-copy-template", $template );
    
    $var = array ();
    $id = $this->get->id;
    $data_contactform = $this->find ( $id );
    
    if (isset ( $this->get->id ) && isset ( $this->get->token )) {
        if ($this->request->is_post ()) {
	            $data = get_option ( 'contactform' );
	            if (! array (
	                    $data 
	            )) {
	                $data = array ();
	            }
	            $rules = array();
	            $rules['to'] = $this->post->{'to'};
	            $rules['title'] = $this->post->{'title'};
	            $rules['from'] = $this->post->{'from'};
	            $rules['form'] = $this->post->{'form'};
	            $rules['name'] = $this->post->{'name'};
	            $rules['message'] = $this->post->{'message'};
	            $error = array();
	            $form = $this->post->{'form'};
	            $validator = Pf::shortcode()->scan($form);
	            $contactform = array (
	                    'title' => $this->post->{'title'},
                        'status' => !empty($this->post->{'status'})? 1:0,
                        'form' => $this->post->{'form'},
                        'mail' => array(
                            'config' => array(
                                'to' => $this->post->{'to'},
                                'recipient' => $this->post->{'recipient'},
                                'from' => $this->post->{'from'},
                                'from_list' => $this->post->{'to_list'},
                                'name' =>  $this->post->{'name'},
                                'subject' => $this->post->{'subject'},
                                'use_as_html' => isset($this->post->{'use_as_html'}) ? 1 : 0
                            ),
                            'message' => $this->post->{'message'},
                            'notify' => $this->post->{'notify'},
                        ),
                        'validator' => $this->get_validator($validator),
	            );
	            $id = generate_id ( 15 );
	            while ( isset ( $data [$id] ) ) {
	                $id = generate_id ( 15 );
	            }
	            $data [$id] = $contactform;
	            $data = Pf::event ()->trigger ( "filter", "contactform-post-data", $data );
	            $data = Pf::event ()->trigger ( "filter", "contactform-copy-post-data", $data );
	            $this->view->token = Pf_Plugin_CSRF::token ( $this->key . $this->get->id );
	            $this->contactform_model->validate ($rules);
	            $errors = Pf::validator()->get_readable_errors(false);
	            foreach ($errors as $key => $value) {
	                $error[$key][0] = $errors[$key][0];
	            }
	            if (count($error) > 0) {
	                $this->view->errors =  $error;
	                $var ['content'] = $this->view->fetch ( $template );
	                $var ['error'] = 1;
	            }else {
	                update_option('contactform', $data);
	                Pf::event ()->trigger ( "action", "contactform-copy-successfully", $data );
	                $var ['error'] = 0;
	                $var ['content'] = $this->view->fetch ( $template );
	                $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
	            }
	        } else {
	            if (isset ( $this->get->id )) {
	                $data = $data_contactform;
	                $contactform =  $data;
	                $data['to'] = $contactform['mail']['config']['to'];
	                $data['message'] = $contactform['mail']['message'];
	                $data['from'] = $contactform['mail']['config']['from'];
	                $data['name'] =  $contactform['mail']['config']['name'];
	                $data['subject'] = $contactform['mail']['config']['subject'];
	                $data['notify'] = $contactform['mail']['notify'];
	                $data['to_list'] = $contactform['mail']['config']['from_list'];
	                $data['recipient'] = $contactform['mail']['config']['recipient'];
	                $data['use_as_html'] = $contactform['mail']['config']['use_as_html'];
	                $data = Pf::event ()->trigger ( "filter", "contactform-database-data", $data );
	                $data = Pf::event ()->trigger ( "filter", "contactform-copy-database-data", $data );
	                $this->post->datas ( $data );
	                $this->view->token = Pf_Plugin_CSRF::token ( $this->key . $this->get->id );
	                $var ['content'] = $this->view->fetch ( $template );
	                $var ['error'] = 0;
	            } else {
	                $var ['error'] = 1;
	                $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
	            }
	        }
	    } else {
	        $var ['error'] = 1;
	        $var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
	    }
	    
	    echo json_encode ( $var );
	}
	
	public function bulk_action() {
		$var = array ();
		$data = array ();
		$params = array ();
	
		if (Pf_Plugin_CSRF::is_valid ( $this->post->token, $this->key )) {
			switch ($this->get->type) {
				case 'delete' :
	
					if (! empty ( $this->post->id ) && is_array ( $this->post->id )) {
						$data_contactform = get_option ( 'contactform' );
						$id = $this->post->id;
						foreach ( $data_contactform as $key => $value ) {
							foreach ( $id as $key2 => $value2 ) {
								if ($key == $value2) {
									unset ( $data_contactform [$key] );
								}
							}
						}
						update_option ( 'contactform', $data_contactform );
					}
					$var ['action'] = 'delete';
					break;
				case 'publish' :
	
					if (! empty ( $this->post->id ) && is_array ( $this->post->id )) {
						$data_contactform = get_option ( 'contactform' );
						$id = $this->post->id;
						foreach ( $data_contactform as $key => $value ) {
							foreach ( $id as $key2 => $value2 ) {
								if ($key == $value2) {
									$data_contactform [$key] ['status'] = 1;
								}
							}
						}
						update_option ( 'contactform', $data_contactform );
					}
					$var ['action'] = 'publish';
					break;
				case 'unpublish' :
					if (! empty ( $this->post->id ) && is_array ( $this->post->id )) {
						$data_contactform = get_option ( 'contactform' );
						$id = $this->post->id;
						foreach ( $data_contactform as $key => $value ) {
							foreach ( $id as $key2 => $value2 ) {
								if ($key == $value2) {
									$data_contactform [$key] ['status'] = 0;
								}
							}
						}
						update_option ( 'contactform', $data_contactform );
					}
					$var ['action'] = 'unpublish';
					break;
			}
			Pf::event ()->trigger ( "action", "contactform-bulk-action-successfully", $this->get->type, $this->post->id );
			$var ['error'] = 0;
		} else {
			$var ['error'] = 1;
		}
		$var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=&type=' );
		echo json_encode ( $var );
	}

	public function delete() {
		$var = array ();
		if (isset ( $this->get->id ) && isset ( $this->get->token ) && Pf_Plugin_CSRF::is_valid ( $this->get->token, $this->key . $this->get->id )) {
			$data_contactform = get_option ( 'contactform' );
			foreach ( $data_contactform as $key => $value ) {
				if ($key == $this->get->id) {
					unset ( $data_contactform [$key] );
					$var ['error'] = 0;
					Pf::event ()->trigger ( "action", "contactform-delete-successfully", $this->get->id );
				} else {
					$var ['error'] = 1;
				}
			}
			update_option ( 'contactform', $data_contactform );
		}
		$var ['url'] = admin_url ( $this->action . '=index&ajax=&id=&token=' );
		echo json_encode ( $var );
	}
	
	public function change_status() {
		$data = array ();
		$status = $this->post->status;
		$id = $this->post->id;
		$data = get_option ( 'contactform' );
	
		if ($status == 'publish') {
			foreach ( $data as $key => $value ) {
				if ($key == $id) {
					$data [$key] ['status'] = 1;
					break;
				}
			}
			update_option ( 'contactform', $data );
		} else {
			foreach ( $data as $key => $value ) {
				if ($key == $id) {
					$data [$key] ['status'] = 0;
					break;
				}
			}
			update_option ( 'contactform', $data );
		}
	}
	
	private function search_title($data, $condition) {
		$input = preg_quote(strtolower($condition), '~');
		$result = preg_grep('~' . $input . '~', $data);
		return $result;
	}
	
	private function search_status($data, $condtion) {
		$status = array();
		foreach ($data as $key => $value) {
			foreach ($condtion as $key1 => $value1)
				if ($value == $value1) {
					$status[$key] = $condtion[$key1];
				}
		}
		return $status;
	}
	
	private function search_by_key($data, $condition, $key_search) {
		$data_get = array_map('get_' . $key_search, $data);
		$result_search = is_string($condition) ? $this->search_title($data_get, $condition) : $this->search_status($data_get, $condition);
		$data_search = array();
		if (count($result_search)) {
			$data_search = array_intersect_key($data, $result_search);
		}
	
		return $data_search;
	}
	
	private function pagination($data, $curent_page) {
		if (is_array($data)) {
			$total = count($data);
			$total_page = ceil($total / NUM_PER_PAGE);
			if ($curent_page > $total_page) {
				$curent_page = $total_page;
			}
	
			$start = ($curent_page - 1) * NUM_PER_PAGE;
			return array_slice($data, $start, NUM_PER_PAGE);
		}
	}
	
}
function cmp($a, $b)
{
    return strcmp($a["status"], $b["status"]);
}
function cmp1($a, $b)
{
    return strcmp($b["status"], $a["status"]);
}

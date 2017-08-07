<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Galleries_Shortcode extends PF_Shortcode_Controller{
	public function __construct(){
		parent::__construct();
		$this->load_model('shortcode-galleries');
	}
	public function display($atts,$conten = null,$tag){
		$catid  =   !empty($_GET['gallery'])?$_GET['gallery']:'';
		$id = (!empty($atts['id'])) ? $atts['id'] : '';
		$params = array();
		$operator = '';
		$params['limit'] = NUM_PER_PAGE;
        $params['page_index'] = (isset($this->get->{$this->page}))?(int)$this->get->{$this->page}:1;
        $data = '';
		
        if(!empty($id)){
            $page_url = $this->get->pf_page_url;
            $galleries_url = explode('/',$page_url);
            Pf::event()->on("theme-breadcrumb",array($this,'galleries_breadcrumb'),10);
			$where = "id = ? ";
        	$where_values[] = $id;
        	$operator = ' AND ';
        	$where .= $operator."gallery_status = ? ";
        	$where_values[] = '1';
        	
        	if (!empty($where_values)){
        		$params['where'] = array($where,$where_values);
        	}
        	$atts['url']    =   public_url('',true);
        	$atts['page_index'] = $params['page_index'];
        	$atts['records'] = $this->shortcode_galleries_model->fetch_one($params);
        	$this->view->breadcrumb_title = $atts['records']['gallery_name'];
        	$this->view->breadcrumb_title_galleries = __($galleries_url[0],'galleries');
        	$atts['total_records'] = $this->shortcode_galleries_model->found_rows();
        	$list_img = unserialize($atts['records']['gallery_data']);
        	
        	$count  =   count($list_img[0]);
        	for($i=0; $i<$count; $i++){
        		$list_show[]  =   array($list_img[0][$i],$list_img[1][$i],$list_img[2][$i]);
        	}
	        foreach ($list_show as $item) {
	        	$atts['size'] = $this->shortcode_galleries_model->get_size($item[0]);
        	}
        	$data['id'] = $id;
        	$data['gallery_views'] = $atts['records']['gallery_views'] + 1;
        	$this->shortcode_galleries_model->save($data);
        	$this->view->atts = $atts;
        	$this->view->render('display_once');
        }else if(!empty($catid)){
            $page_url = $this->get->pf_page_url;
            $galleries_url = explode('/',$page_url);
            
            Pf::event()->on("theme-breadcrumb",array($this,'galleries_breadcrumb'),10);
        	$where = "id = ? ";
        	$where_values[] = $catid;
        	$operator = ' AND ';
        	$where .= $operator."gallery_status = ? ";
        	$where_values[] = '1';
        	 
        	if (!empty($where_values)){
        		$params['where'] = array($where,$where_values);
        	}
        	$atts['catid'] = $catid;
        	$atts['url']    =   public_url('',true);
        	$atts['page_index'] = $params['page_index'];
        	$atts['records'] = $this->shortcode_galleries_model->fetch_one($params);
        	$this->view->breadcrumb_title = $atts['records']['gallery_name'];
        	$this->view->breadcrumb_title_galleries = __($galleries_url[0],'galleries');
        	$atts['total_records'] = $this->shortcode_galleries_model->found_rows();
        	$atts['num_page'] = ceil($atts['total_records'] / 8);
        	$list_img = unserialize($atts['records']['gallery_data']);
        	 
        	$count  =   count($list_img[0]);
        	for($i=0; $i<$count; $i++){
        		$list_show[]  =   array($list_img[0][$i],$list_img[1][$i],$list_img[2][$i]);
        	}
        	foreach ($list_show as $item) {
        		$atts['size'] = $this->shortcode_galleries_model->get_size($item[0]);
        	}
        	
        	$data['id'] = $catid;
        	$data['gallery_views'] = $atts['records']['gallery_views'] + 1;
        	$this->shortcode_galleries_model->save($data);
        	$this->view->atts = $atts;
        	$this->view->render('display_once');
        }else {
            $where = "gallery_status = ? ";
            $where_values[] = '1';
        
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }			
	        $atts['url']    =   public_url('',true);
			$atts['page_index'] = $params['page_index'];
			$atts['records'] = $this->shortcode_galleries_model->fetch($params,true);
			$atts['total_records'] = $this->shortcode_galleries_model->found_rows();
			$atts['num_page'] = ceil($atts['total_records'] / 8);
			$this->view->atts = $atts;
			$this->view->render('display');
			
			$enable =   Pf::setting()->get_element_value('pf_comment','enable');
			if($enable==1 && !empty($comment) && check_plugin_active('comment')){
				echo Pf::shortcode()->exec("{pf:comment key=".$comment."}");
			}
		}
	}
	public function data($atts,$conten = null,$tag){
	    $page_no = $this->post->page_no;
		$catid  =   !empty($_GET['gallery'])?$_GET['gallery']:'';
		$id = (!empty($atts['id'])) ? $atts['id'] : '';
		$params = array();
		$operator = '';
		$params['limit'] = NUM_PER_PAGE;
		$params['page_index'] = (isset($this->get->{$this->page}))?(int)$this->get->{$this->page}: 1;
		$data = '';
		
		$where = "gallery_status = ? ";
		$where_values[] = '1';
		
		if (!empty($where_values)){
			$params['where'] = array($where,$where_values);
		}
		
		$atts['url']    =   public_url('',true);
		$atts['url_get'] = $this->get->pf_page_url;
		$atts['page_index'] = $params['page_index'];
		$atts['records'] = $this->shortcode_galleries_model->fetch($params,true);
		$atts['total_records'] = $this->shortcode_galleries_model->found_rows();
		$atts['num_page'] = ceil($atts['total_records'] / 8);
		$this->view->atts = $atts;
		$this->view->render('data');
	}
	public function galleries_breadcrumb($breadcrumb = ''){
        return $this->view->fetch('breadcrumb');
    }
}

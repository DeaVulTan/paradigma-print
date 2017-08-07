<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Shortcode_Galleries_Model extends Pf_Model{
	public function __construct(){
		parent::__construct(DB_PREFIX.'galleries');
	}
	//public function add_view(){
	//}
	public function get_size($img_url) {
		if(is_file(urldecode($img_url))){
			$size   = getimagesize($img_url);
			return array('width'=>$size[0],'height'=>$size[1]);
		}
		else
			return 0;
	}
	public function add_views($id) {
		$this->db->select('gallery_views',''.DB_PREFIX.'galleries',"`id`= ? ",array($id));
		$result     =   $this->db->fetch_assoc_all();
		$current    =   !empty($result[0]['	'])?$result[0]['gallery_views']:0;
		$this->db->update(''.DB_PREFIX.'galleries',array('gallery_views'=>($current+1)),"`id`=?",array($id));
		return  $current+1;
	}
}
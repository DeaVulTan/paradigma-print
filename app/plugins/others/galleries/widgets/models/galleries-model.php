<?php
class Galleries_Model extends Pf_Model{
    public $rules = array('subject'=> 'required');
    
    public function __construct(){
        parent::__construct(DB_PREFIX.'galleries');
    }
    
    public function pf_get_img($source) {
        $params = array();
        $params['where'] = array('gallery_status = ?',array(1));
        $params['fields'] = 'id,gallery_data';
        
        $result = $this->fetch($params);
        
        if ($source == 'rand' && count($result)!=0) {
            $result = array($result[array_rand($result)]);
        } elseif (is_numeric($source)) {
            for ($i = 0; $i < count($result); $i++) {
                if ($result[$i]['id'] == $source)
                    $result = array($result[$i]);
            }
        }
        $list = array();
        for ($i = 0; $i < count($result); $i++) {
            $data = unserialize($result[$i]['gallery_data']);
            if (!empty($data[0])) {
                foreach ($data[0] as $img) {
                    $list[] = array('gallery' => $result[$i]['id'], 'img' => $img);
                }
            }
        }
        return $list;
    }
    
    public function gallery_list(){
        $params = array();
        $params['where'] = array('gallery_status = ?',array(1));       
        $data   =   $this->fetch($params);
        $list   =   array();
        if(!empty($data)){
            foreach($data as $item){
                $list[$item['id']]  =   $item['gallery_name'];
            }
        }
        return $list;
    }
}
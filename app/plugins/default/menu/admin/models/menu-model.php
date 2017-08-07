<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Menu_Model extends Pf_Model {
    
    public $rules = array (
        'title' => 'required|max_len,255',
    );
    
    public $elements_value = array (
        'page_status' => array (
            '1' => 'Published',
            '2' => 'Unpublished' 
        ),
        'page_created_date' => 'DD-MM-YYYY' 
    );
    
//put your code here
    public $db;
    protected $_k;

    public function __construct() {
        parent::__construct();
        //$this->db = Pf::database();
    }

    public function choose_menu($menu_list, $menu_id) {
        foreach ($menu_list as $k => $v) {
            if ($menu_id == $v['id']) {
                $key_menu = $k;
                break;
            }
        }
        return isset($key_menu) ? $key_menu : '';
    }

    public function check_child($arr) {
        if (!empty($arr['children']))
            return TRUE;
        else
            return FALSE;
    }

    public function add_menu($list_menu, $id) {
        $default_data = default_item();
        $list_menu[] = array(
            'id' => $id,
            'name' => $_POST['title'],
            'data' => $default_data,
        );
        update_option('menu', $list_menu);
    }
    public function filter_data($list, $data) {
        $result = array();
        if(is_array($list)){
            foreach($list as $k=>$v){
                if($this->check_child($v) == true){
                    $list[$k]['children'] = $this->filter_data($v['children'], $data);
                }
                if(empty($data[$v['id']])){
                    unset($list[$k]);
                }
            }
            return $list;
        }
    }
    public function menu_($arrays, $data) {

        foreach ($arrays as $array) {
            $_k = $this->choose_menu($data, $array['id']);
            $target = $data[$_k]['type'] == 'page' ? '#new-page' : '#new-url';
            $options  =   isset($data[$_k]['options'])?$data[$_k]['options']:'';
            $icontype  =   isset($data[$_k]['icontype'])?$data[$_k]['icontype']:'';
            $icons  =   isset($data[$_k]['icon'])?$data[$_k]['icon']:'';
            $desc  =   isset($data[$_k]['desc'])?$data[$_k]['desc']:'';
            $icon   =   $data[$_k]['status']==1?"<i id='icon-".$array['id'] ."' class='fa fa-check-square'></i>":"<i id='icon-".$array['id'] ."' class='fa fa-square'></i>";
            $color  =   isset($data[$_k]['color'])?$data[$_k]['color']:'';
            echo "<li class='dd-item' id='" . $array['id'] . "' data-id='" . $array['id'] . "'>
                      <div class='manage-item'>
                      <a class='btn btn-success btn-xs width24' onclick=\"publicid('" . $array['id'] . "')\">$icon</a>
                      <a class='btn btn-info btn-xs' id='edit-" . $array['id'] . "' data-toggle='modal' data-target='$target' onclick=\"edit_item('" . addslashes($array['id']) . "','" . addslashes($data[$_k]['name']) . "','" . addslashes($data[$_k]['type']) . "','" . addslashes($data[$_k]['call']) . "','" . addslashes($options) . "','" . addslashes($icontype) . "','" . addslashes($icons) . "','" . addslashes($desc) . "','" . addslashes($color) . "'); \"><i class='fa fa-pencil-square-o'></i></a>
                      <a class='btn btn-danger btn-xs' onclick=\"deleteid('" . $array['id'] . "')\"><i class='fa fa-times-circle'></i></a>
                        </div>
                      <div class='dd-handle'>" . $data[$_k]['name'] . "</div>";
            if ($this->check_child($array) == true) {
                echo "<ol class='dd-list'>";
                $this->menu_($array['children'], $data);
                echo "</ol>";
            }
            echo "</li>";
        }
    }

    public function delete_item($array, $id) {
        for ($i = 0; $i < count($array); $i++) {
            if ($this->check_child($array[$i]) == true) {
                $array[$i]['children'] = $this->delete_item($array[$i]['children'], $id);
            }
            if ($array[$i]['id'] == $id) {
                unset($array[$i]);
            }
        }
        return $array;
    }

    public function edit_item($array, $data, $id) {
        for ($i = 0; $i < count($array); $i++) {
            if ($array[$i]['id'] == $id) {
                $array[$i]['name'] = $data['name'];
                $array[$i]['call'] = $data['call'];
            }
            if ($this->check_child($array[$i]) == true) {
                $array[$i]['children'] = $this->edit_item($array[$i]['children'], $data, $id);
            }
        }
        return $array;
    }

    public function search_menu($text, $value) {
        $ok = FALSE;
        for ($i = 0; $i < strlen($value) - strlen($text); $i++) {
            $s = '';
            for ($j = $i; $j < $i + strlen($text); $j++) {
                $s .= $value[$j];
            }
            if ($s == $text) {
                $ok = TRUE;
            }
        }
        return $ok;
    }
    
    public function get_page(){
        $this->db->select(
                'id,page_url, page_title',
                ''.DB_PREFIX.'pages',
                'page_system = 0 and page_status = ?',
                array(1), 'id desc'
        );
        return $this->db->fetch_assoc_all();
    }
}
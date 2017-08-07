<?php
defined('PF_VERSION') OR header('Location:404.html');
class Widgets_Shortcode extends Posts_Shortcode{
    protected $attrs;
    public function __construct(){
        parent::__construct();
        $this->load_model('posts');
    }
    public function display($atts, $content = null,$tag) {
        $this->attrs = $atts;
        $params = array();
        $where = '';
        $where_values = array();
        $operator = '';
        
        $params['limit'] = $atts['number_items'];
        
        $where .= $operator.' `post_status` = ? ';
        $where_values[] = 1;
        $params['fields'] = $this->attrs['select'];
        
        if (!empty($where_values)){
            $params['where'] = array($where,$where_values);
        }
        
        if($this->attrs['order_field'] == 'views'){
            $sort_by = 'post_views';
        }else{
            $sort_by = 'id';
        }
        $params["order"] = "`".Pf::database ()->escape($sort_by)."` ".Pf::database ()->escape($this->attrs['order_type']);
        
        $atts['posts'] = $this->posts_model->fetch($params,true);
        $this->view->atts = $atts;
        $this->view->render();
    }
}

function converter_post($data){
    $converter = array(
        'а' => 'a',   'б' => 'b',   'в' => 'v',
        'г' => 'g',   'д' => 'd',   'е' => 'e',
        'ё' => 'e',   'ж' => 'zh',  'з' => 'z',
        'и' => 'i',   'й' => 'y',   'к' => 'k',
        'л' => 'l',   'м' => 'm',   'н' => 'n',
        'о' => 'o',   'п' => 'p',   'р' => 'r',
        'с' => 's',   'т' => 't',   'у' => 'u',
        'ф' => 'f',   'х' => 'h',   'ц' => 'c',
        'ч' => 'ch',  'ш' => 'sh',  'щ' => 'sch',
        'ь' => '\'',  'ы' => 'y',   'ъ' => '\'',
        'э' => 'e',   'ю' => 'yu',  'я' => 'ya',

        'А' => 'A',   'Б' => 'B',   'В' => 'V',
        'Г' => 'G',   'Д' => 'D',   'Е' => 'E',
        'Ё' => 'E',   'Ж' => 'Zh',  'З' => 'Z',
        'И' => 'I',   'Й' => 'Y',   'К' => 'K',
        'Л' => 'L',   'М' => 'M',   'Н' => 'N',
        'О' => 'O',   'П' => 'P',   'Р' => 'R',
        'С' => 'S',   'Т' => 'T',   'У' => 'U',
        'Ф' => 'F',   'Х' => 'H',   'Ц' => 'C',
        'Ч' => 'Ch',  'Ш' => 'Sh',  'Щ' => 'Sch',
        'Ь' => '\'',  'Ы' => 'Y',   'Ъ' => '\'',
        'Э' => 'E',   'Ю' => 'Yu',  'Я' => 'Ya',
    );
    
    // переводим в транслит
    $cirurl = strtr($data, $converter);
    // в нижний регистр
    $cirurl = strtolower($cirurl);
    // заменям все ненужное нам на "-"
    $cirurl = preg_replace('~[^-a-z0-9_]+~u', '-', $cirurl);
    // удаляем начальные и конечные '-'
    $cirurl = trim($cirurl, "-");
    return $cirurl;
}
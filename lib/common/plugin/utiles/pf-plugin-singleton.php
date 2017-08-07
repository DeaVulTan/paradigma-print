<?php
defined('PF_VERSION') OR header('Location:404.html');

class Pf_Plugin_Singleton
{

    static private $object = array();

    static public function categories($type = 1)
    {
        if (isset(self::$object['categories'])) {
            return self::$object['categories'];
        }
        Pf::database()->select('id, category_name, category_parent', ''.DB_PREFIX.'post_categories', 'category_status = 1 and category_type = ?', array($type));
        $data = Pf::database()->fetch_obj_all();
        if (!empty($data)) {
            self::$object['categories'] = $data;
            return self::$object['categories'];
        }
    }

    static public function list_page()
    {
        if (isset(self::$object['list_page'])) {
            return self::$object['list_page'];
        }
        Pf::database()->select('id,page_title', ''.DB_PREFIX.'pages', 'page_system = 0 and page_status = ?', array(1), 'id desc');
        $data = Pf::database()->fetch_assoc_all();
        if (empty($data)) {
            return;
        }
        $list = array();
        if (!empty($data)) {
            foreach ($data as $item) {
                $list[$item['id']] = $item['page_title'];
            }
        }
        self::$object['list_page'] = $list;
        return self::$object['list_page'];
    }

    static public function list_users($conditions, $param, $select = '')
    {
        if (isset(self::$object['list_users'])) {
            return self::$object['list_users'];
        }
        
        if($select == NULL){
            $select = "".DB_PREFIX."users.id as uid,user_name,user_email";
            Pf::database()->select($select, ''.DB_PREFIX.'users', $conditions, $param);
        }
        $data = Pf::database()->fetch_assoc_all();
        if (empty($data)) {
            return;
        }
        $list = array();
        foreach ($data as $item) {
            $list[$item['uid']] = $item['user_name'];
        }
        self::$object['list_users'] = $list;
        return self::$object['list_users'];
    }

}

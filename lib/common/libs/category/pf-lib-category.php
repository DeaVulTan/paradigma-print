<?php
defined('PF_VERSION') OR header('Location:404.html');
class Pf_Lib_Category
{

    private $model;

    public function __construct()
    {
        $this->model = new Pf_Plugin_Model;
        $this->model->table(''.DB_PREFIX.'categories')->set_prefix_column('category')->set_primary_key('id');
    }

    public function get($select, $join = false, $conditions = '', $param = array(), $display_level = '--')
    {
        $this->model->select($select);
        if ($join === true) {
            $this->model->join(''.DB_PREFIX.'users', ''.DB_PREFIX.'categories.category_author = '.DB_PREFIX.'users.id', 'LEFT JOIN');
        }
        if (!empty($conditions)) {
            $this->model->conditions($conditions);
        }
        if (!empty($param)) {
            $this->model->param($param);
        }
        $data = $this->model->get();
        $result = array();
        if (count($data)) {
            $ids = array_map('get_parent_of_category', $data);
            recursive($data, $result, min($ids), 0, 'category');
        }
        if (count($data) !== count($result)) {
            $ids = array_map('get_id_of_category', $result);
            foreach ($data as $k => $v) {
                if (!in_array($v->id, $ids)) {
                    $v->level = 0;
                    $result[] = $v;
                    unset($data[$k]);
                }
            }
        }
        if (!is_null($display_level)) {
            return array_map('replace_recursive_title', $result);
        }
        return $result;
    }
    
    public function get_by_id($id){
        return $this->model->conditions("where id = {$id}")->get(3);
    }

    public function dropdown($no_parent = true, $conditions = '', $param = array(), $display_level = '--')
    {
        $result = $this->get('id,category_name, category_parent', FALSE, $conditions, $param, $display_level);
        
        
        $dropdown = array();
        foreach ($result as $v) {
            $dropdown[] = array($v->id, $v->category_name);
        }
        if ($no_parent === true) {
            array_unshift($dropdown, array(0, __('No parent', 'system')));
        }
        return $dropdown;
    }

    public function insert($data, $validator = true, $rules = array())
    {
        return $this->model->insert($data, $validator, $rules);
    }

    public function update($data, $validator = true, $rules = array(), $conditions = "", $param = array())
    {
        return $this->model->conditions($conditions)->param($param)->update($data, $validator, $rules);
    }

    public function delete($conditions, $param)
    {

        return $this->model->conditions($conditions)->param($param)->delete();
    }

    public function count($conditions, $param)
    {
        return $this->model->conditions($conditions)->param($param)->count();
    }

    public function get_errors()
    {
        return $this->model->get_errors();
    }

    public function get_input($execpt = '')
    {
        return $this->model->get_data_input($execpt);
    }

}

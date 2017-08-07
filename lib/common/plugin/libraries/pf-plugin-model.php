<?php
defined('PF_VERSION') OR header('Location:404.html');
/**
 *
 * @package		Vitubo
 * @author		Vitubo Team 
 * @copyright   Vitubo Team
 * @link		http://www.vitubo.com
 * @since		Version 1.0
 * @filesource
 *
 */
class Pf_Plugin_Model extends Pf_Plugin_Builder
{

    protected $table;
    protected $prefix_column;
    protected $primary_key;
    protected $rules;
    protected $errors;
    protected $db;

    public function __construct()
    {
        $this->input = new Pf_Plugin_Input;
        $this->validator = Pf::validator();
        $this->db = Pf::database();
    }

    public function get_db()
    {
        return $this->db;
    }

    public function set_prefix_column($prefix_column)
    {
        $this->prefix_column = $prefix_column;
        return $this;
    }

    public function set_primary_key($primary_key)
    {
        $this->primary_key = $primary_key;
        return $this;
    }

    public function table($table)
    {
        $this->table = $table;
        return $this;
    }

    public function get_errors()
    {
        return $this->errors;
    }

    public function set_rules($rules)
    {
        $this->rules = $rules;
        return $this;
    }
    
    
    public function set_timestamps($flag = true)
    {
        $this->timestamps = $flag;
        return $this;
    }

    
    /*
     * Select
     */

    public function get($type = '', $sql = '')
    {
        $this->fetch($type);
        return $this->excute($sql);
    }

    public function find($id, $columns = '*', $type = 3)
    {
        $this->select($columns)
                ->conditions("WHERE {$this->primary_key} = {$id}");
        return $this->get($type);
    }

    public function count($sql = '')
    {
        $condition = ($sql == '') ? $this->conditions : $sql;
        if (count($this->param)) {
            return $this->db->dcount($this->primary_key, $this->table, $condition, $this->param);
        } else {
            return $this->db->dcount($this->primary_key, $this->table, $condition);
        }
    }

    public function counts($table)
    {
        $this->select("count($table.$this->primary_key) as counted");
        $get = $this->get(2);
        if (count($get)) {
            return $get['counted'];
        }
        return false;
    }

    /*
     * Get data to insert and update
     */

    public function add_prefix_columns($data)
    {
        foreach ($data as $k => $v) {
            $prefix = $this->get_key_input() . $k;
            $data[$prefix] = $v;
            unset($data[$k]);
        }
        return $data;
    }

    public function get_key_input()
    {
        return $this->prefix_column !== '' ? "{$this->prefix_column}_" : '';
    }

    public function get_data_input($execpt = '')
    {
        $data = $execpt === '' ? $this->input->post() : $this->input->post_except($execpt);
        return $this->add_prefix_columns($data);
    }

    /*
     * INSERT, UPDATE, DELETE
     */

    public function validator($data, $rules = array())
    {
        if (!empty($rules)) {
            $this->rules = $rules;
        }
        if (!empty($this->rules)) {
            $data = count($data) ? $data : $this->input->post();
            $this->validator->validation_rules($this->rules);

            if ($this->validator->run($data) !== false) {
                return true;
            } else {
                $this->errors = $this->validator->get_readable_errors(false);
                return false;
            }
        }
        return true;
    }

    public function save($data = array(), $validate = true, $validator = array(), $insert = true)
    {
        if ($validate) {
            if (!$this->validator(array(), $validator)) {
                return false;
            }
        }
        if (count($data)) {
            $this->data = $data;
        }

        if ($insert) {
            $this->timestamps();
            $this->db->insert($this->table, $this->data);
            return $this->db->insert_id();
        } else {
            $this->timestamps(false);
            if (!count($this->param)) {
                $result = $this->db->update($this->table, $this->data, $this->conditions);
                $this->conditions('');
                return $result;
            } else {
                $result = $this->db->update($this->table, $this->data, $this->conditions, $this->param);
                $this->conditions('');
                return $result;
            }
        }
    }

    public function insert($data, $validate = true, $validator = array())
    {
        return $this->save($data, $validate, $validator, true);
    }

    public function get_insert_id()
    {
        return $this->db->insert_id();
    }

    public function update($data, $validate = true, $validator = array())
    {
        return $this->save($data, $validate, $validator, false);
    }

    public function delete()
    {
        if (count($this->param)) {
            return $this->db->delete($this->table, $this->conditions, $this->param);
        } else {
            return $this->db->delete($this->table, $this->conditions);
        }
    }

    //insert_bulk
    public function insert_bulk($columns, $data)
    {
        if ($this->db->insert_bulk($this->table, $columns, $data)) {
            return true;
        }
        return false;
    }

    /**
     * Clean query
     * @return \Pf_Plugin_Model
     */
    public function clean()
    {
        $this->conditions = '';
        $this->param = array();
        $this->columns = '';
        $this->joins = array();
        return $this;
    }

}

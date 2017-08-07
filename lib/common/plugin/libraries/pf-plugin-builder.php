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
class Pf_Plugin_Builder
{

    protected $columns;
    protected $table;
    protected $joins;
    protected $data;
    protected $conditions;
    protected $param;
    protected $fetch;
    protected $timestamps = true;

    public function get_prefix_column()
    {
        return $this->prefix_column;
    }

    public function select($columns = array())
    {
        $this->columns = is_array($columns) ? $columns : func_get_args();
        return $this;
    }

    public function table($name)
    {
        $this->table = $name;
        return $this;
    }

    public function join($table, $on, $join_type = 'JOIN')
    {
        $this->joins[] = "{$join_type} {$table} on {$on}";
        return $this;
    }

    public function left_join($table, $on)
    {
        return $this->join($table, $on, 'LEFT JOIN');
    }

    public function right_join($table, $on)
    {
        return $this->join($table, $on, 'RIGHT JOIN');
    }

    public function full_join($table, $on)
    {
        return $this->join($table, $on, 'FULL OUTER JOIN');
    }

    public function get_joins()
    {
        return $this->joins;
    }

    public function set_joins($joins = array())
    {
        $this->joins = $joins;
        return $this;
    }

    public function conditions($conditions)
    {
        $this->conditions = $conditions;
        return $this;
    }

    public function data($data)
    {
        $this->data = is_array($data) ? $data : func_get_args();
        return $this;
    }

    public function param($param = array())
    {
        $this->param = is_array($param) ? $param : func_get_args();
        return $this;
    }

    public function fetch($type)
    {
        switch ($type) {
            case 1:
                $fetch = 'assoc_all';
                break;
            case 2:
                $fetch = 'assoc';
                break;
            case 3:
                $fetch = 'obj';
                break;
            default:
                $fetch = 'obj_all';
                break;
        }

        $this->fetch = "fetch_{$fetch}";
        return $this;
    }

    protected function compile_select()
    {
        $joins = count($this->joins) ? implode(' ', $this->joins) : '';
        $column = count($this->columns) ? implode(',', $this->columns) : '*';
        $condition = !is_null($this->conditions) ? $this->conditions : '';
        $sql = "SELECT  {$column} FROM {$this->table} {$joins}  $condition";
        //echo ($sql);
        return $sql;
    }

    protected function excute($sql)
    {
        $query = ($sql == '') ? $this->compile_select() : $sql;
        if (is_array($this->param)) {
            $this->db->query($query, $this->param);
        } else {
            $this->db->query($query);
        }
        $this->conditions('');
        $this->param('');
        return $this->db->{$this->fetch}();
    }

    protected function timestamps($type = true)
    {
        if ($this->timestamps) {
            if ($type) {
                $date = 'created_date';
            } else {
                $date = 'modified_date';
            }
            $key = ($this->prefix_column != '') ? "{$this->prefix_column}_{$date}" : $date;
            $this->data[$key] = date('Y-m-d H:i:s');
        }
    }

}

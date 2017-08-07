<?php
abstract class Pf_Model extends Pf_Base_Object {
    protected $table;
    protected $validator;
    public $db;
    public $errors = array ();
    public $filters = array ();
    public $rules = array ();
    /**
     * 
     * @param string $table
     */
    public function __construct($table = '') {
        $this->table = $table;
        $this->db = Pf::database ();
        $this->validator = Pf::validator ();
    }
    
    public function table($table = null){
        if ($table === null){
            return $this->table;
        }else{
            $this->table = $table;
        }
    }
    /**
     * 
     * @param array $data
     * @return boolean
     */
    public function validate($data = array()) {
        if (! empty ( $this->rules ) && is_array ( $this->rules )) {
            $data = (! empty ( $data ) && is_array ( $data )) ? $data : $this->data;
            
            if (! empty ( $this->filters ) && is_array ( $this->filters )) {
                $data = $this->validator->filter ( $data, $this->filters );
            }
            
            return $this->validator->validate($data, $this->rules );
        } else {
            return true;
        }
    }
    /**
     * 
     * @param array $data
     * @param string $validate
     * @return mixed
     */
    public function insert($data = array(), $validate = true) {
        $data = (! empty ( $data ) && is_array ( $data )) ? $data : $this->data;
        $inserted = true;
        if (! empty ( $data ) && is_array ( $data )) {
            $validated = true;
            if ($validate == true) {
                $validated = $this->validate ( $data );
            }
            
            if ($validated === true) {
                $inserted = $this->db->insert ( $this->table, $data );
            } else {
                $this->errors = $this->validator->get_readable_errors(false);
                $inserted = false;
            }
        }
        $this->data = array ();
        
        return $inserted;
    }
    /**
     * 
     * @param array $data
     * @param boolean $validate
     * @return boolean
     */
    public function save($data = array(), $validate = true) {
        $data = (! empty ( $data ) && is_array ( $data )) ? $data : $this->data;
        $saved = true;
        if (! empty ( $data ) && is_array ( $data )) {
            $validated = true;
            if ($validate == true) {
                $validated = $this->validate ( $data );
            }
            
            if ($validated === true) {
                $saved = $this->db->insert_update ( $this->table, $data, $data );
            } else {
                $this->errors = $this->validator->get_readable_errors(false);
                $saved = false;
            }
        }
        $this->data = array ();
        
        return $saved;
    }
    /**
     * 
     * @param array $datas
     * @param boolean $validate
     * @return boolean
     */
    public function insert_bulk($datas = array(), $validate = true) {
        $this->db->query ( 'START TRANSACTION' );
        $inserted = true;
        if (! empty ( $datas ) && is_array ( $datas )) {
            foreach ( $datas as $data ) {
                if (! empty ( $data ) && is_array ( $data )) {
                    $inserted = $this->insert ( $data, $validate );
                    if ($inserted === false || count ( $this->errors ) > 0) {
                        $this->db->query ( 'ROLLBACK' );
                        break;
                    }
                }
            }
        }
        if ($inserted === false || count ( $this->errors ) > 0) {
            return false;
        } else {
            $this->db->query ( 'COMMIT' );
            return true;
        }
    }
    /**
     * 
     * @param array $data
     * @param string $where
     * @param mixed $replacements
     * @param boolean $validate
     * @return boolean
     */
    public function update($data, $where = '', $replacements = '', $validate = true) {
        $updated = false;
        $data = (! empty ( $data ) && is_array ( $data )) ? $data : $this->data;
        if (! empty ( $data ) && is_array ( $data )) {
            $validated = true;
            if ($validate == true) {
                $validated = $this->validate ( $data );
            }
            if ($validated === true) {
                $updated = $this->db->update ( $this->table, $data, $where, $replacements );
            } else {
                $this->errors = $this->validator->get_readable_errors(false);
                $updated = false;
            }
        }
        $this->data = array ();
        
        return $updated;
    }
    /**
     * 
     * @param string $where
     * @param mixed $replacements
     * @return boolean
     */
    public function delete($where = '', $replacements = '') {
        return $this->db->delete ( $this->table, $where, $replacements );
    }
    /**
     * 
     * @param array $params
     * @param boolean $calc_rows
     * @param string $fetch
     * @return mixed
     */
    public function fetch($params = array(),$calc_rows = false, $fetch = 'assoc') {
        if (!empty($params['fields'])) {
            $fields = (is_array($params['fields'])) ? implode(',', $params['fields']) : $params['fields'];
        }else{
            $fields = '*';
        }
        
        $replacements = array();
        $join = '';
        $where = '';
        $group = '';
        $having = '';
        $order = '';
        $limit = '';
        
        if (!empty($params['join'])) {
            foreach ($params['join'] as $v) {
                $join .= ' ' . strtoupper($v[0]) . ' JOIN ' . $v[1] . ' ON ' . $v[2] . ' ';
            }
        }
        
        if (!empty($params['where'])) {
            if (is_array($params['where'])) {
                if (!empty($params['where'][0])) {
                    $where .= $params['where'][0];
                }
                if (!empty($params['where'][1]) && is_array($params['where'][1])) {
                    foreach ($params['where'][1] as $v) {
                        $replacements[] = $v;
                    }
                }
            } else {
                $where .= $params['where'];
            }
        }
        
        if (!empty($params['having'])) {
            if (is_array($params['having'])) {
                if (!empty($params['having'][0])) {
                    $having .= $params['having'][0];
                }
                if (!empty($params['having'][1]) && is_array($params['having'][1])) {
                    foreach ($params['having'][1] as $v) {
                        $replacements[] = $v;
                    }
                }
            } else {
                $having .= $params['having'];
            }
        }
        
        if (!empty($params['order'])) {
            $order .= (is_array($params['order'])) ? implode(',', $params['order']) : $params['order'];
        }
        
        if (!empty($params['group'])) {
            $group .= (is_array($params['group'])) ? implode(',', $params['group']) : $params['group'];
        }
        if (!empty($params['limit']) && (int) $params > 0) {
            $page = (isset($params['page_index'])) ? (int) $params['page_index'] : 0;
            if ($page <= 0) {
                $limit .= $params['limit'];
            } else {
                $offset = ($page - 1) * (int) $params['limit'];
                $limit .= $offset . ',' . $params['limit'];
            }
        }
        
        $where = ($where != '') ? ' WHERE ' . $where : $where;
        $group = ($group != '') ? ' GROUP BY ' . $group : $group;
        $having = ($having != '') ? ' HAVING  ' . $having : $having;
        $order = ($order != '') ? ' ORDER BY ' . $order : $order;
        $limit = ($limit != '') ? ' LIMIT ' . $limit : $limit;
        
        $sql = "SELECT " . $fields . " FROM `" . $this->table . "` " . $join . $where . $group . $having . $order . $limit;
        
        return $this->query($sql,$replacements,$calc_rows,$fetch);
    }
    
    public function fetch_one($params = array(),$calc_rows = false, $fetch = 'assoc'){
        $params['limit'] = 1;
        $record = $this->fetch($params,$calc_rows,$fetch);
        
        return (!empty($record) && is_array($record))?$record[0]:false;
    }
    /**
     * 
     * @param string $sql
     * @param mixed $replacements
     * @param string $calc_rows
     * @param string $fetch
     * @return mixed
     */
    public function query($sql,$replacements = '', $calc_rows = false, $fetch = 'assoc'){
        if ($this->db->query($sql,$replacements,false,$calc_rows) !== false){
            switch (strtolower($fetch)){
                case 'assoc':
                    return $this->db->fetch_assoc_all();
                    break;
                case 'obj':
                    return $this->db->fetch_obj_all();
                    break;
            }            
        }else{
            return false;
        }
    }
    /**
     * @return mixed
     */
    public function insert_id() {
        return $this->db->insert_id ();
    }
    /**
     * * @return mixed
     */
    public function affected_rows(){
        return $this->db->affected_rows;
    }
    /**
     * @return mixed
     */
    public function returned_rows(){
        return $this->db->returned_rows;
    }
    /**
     * @return mixed
     */
    public function found_rows(){
        return $this->db->found_rows;
    }
    /**
     * 
     * @param object $db
     * @return $db
     */
    public function db(& $db = null) {
        if ($db == null) {
            return $this->db;
        } else {
            $this->db = &$db;
        }
    }
}
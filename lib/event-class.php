<?php
class Pf_Event {
    private $filter = array ();
    private $ksort_flag = array ();
    public function __construct() {}
    /**
     * 
     * @param unknown $tag
     * @param unknown $fnc
     * @param number $priority
     * @param number $arg_num
     * @return boolean
     */
    public function on($tag, $fnc, $priority = 10, $arg_num = 1) {
        $unique_id = $this->unique_id ( $tag, $fnc, $priority );
        $this->filter [$tag] [$priority] [$unique_id] = array (
                'fnc' => $fnc,
                'arg_num' => $arg_num
        );
        unset ( $this->ksort_flag [$tag] );
        return true;
    }
    /**
     * 
     * @param unknown $tag
     * @param string $fnc
     * @return boolean|unknown
     */
    public function has($tag, $fnc = false) {
        $has = ! empty ( $this->filter [$tag] );
        if (false === $fnc || false == $has)
            return $has;
        
        if (! $unique_id = $this->unique_id ( $tag, $fnc, false ))
            return false;
        
        foreach ( ( array ) array_keys ( $this->filter [$tag] ) as $priority ) {
            if (isset ( $this->filter [$tag] [$priority] [$unique_id] ))
                return $priority;
        }
        
        return false;
    }
    /**
     * 
     * @param unknown $tag
     * @param unknown $fnc
     * @param number $priority
     * @return unknown
     */
    public function remove($tag, $fnc, $priority = 10) {
        $fnc = $this->unique_id ( $tag, $fnc, $priority );
        
        $r = isset ( $this->filter [$tag] [$priority] [$fnc] );
        
        if (true === $r) {
            unset ( $this->filter [$tag] [$priority] [$fnc] );
            if (empty ( $this->filter [$tag] [$priority] ))
                unset ( $this->filter [$tag] [$priority] );
            unset ( $this->ksort_flag [$tag] );
        }
        
        return $r;
    }
    /**
     * 
     * @param unknown $tag
     * @param string $priority
     * @return boolean
     */
    public function remove_all($tag, $priority = false) {
        if (isset ( $this->filter [$tag] )) {
            if (false !== $priority && isset ( $this->filter [$tag] [$priority] ))
                unset ( $this->filter [$tag] [$priority] );
            else
                unset ( $this->filter [$tag] );
        }
        
        if (isset ( $this->ksort_flag [$tag] ))
            unset ( $this->ksort_flag [$tag] );
        
        return true;
    }
    /**
     * 
     * @param unknown $type
     * @param unknown $tag
     * @param unknown $args
     */
    public function trigger($type,$tag,$value = '') {
        $args = func_get_args ();
        array_shift($args);
        
        $type = strtolower(trim($type));
        switch ($type){
        	case 'filter':
        	    return $this->filters($tag, $args);
        	    break;
        	case 'action':
        	    $this->actions($tag, $args);
        	    break;
        }
    }
    /**
     * 
     * @param unknown $tag
     * @param unknown $value
     * @return unknown|Ambigous <unknown, mixed>
     */
    private function filters($tag,$args) {
        if (! isset ( $this->filter [$tag] )) {
            return (!empty($args [1]))?$args [1]:null;
        }
        
        if (! isset ( $this->ksort_flag [$tag] )) {
            ksort ( $this->filter [$tag] );
            $this->ksort_flag [$tag] = true;
        }
        
        reset ( $this->filter [$tag] );
        
        do {
            foreach ( ( array ) current ( $this->filter [$tag] ) as $the_ )
            if (! is_null ( $the_ ['fnc'] )) {
                $args [1] = call_user_func_array ( $the_ ['fnc'], array_slice ( $args, 1, ( int ) $the_ ['arg_num'] ) );
            }
        } while ( next ( $this->filter [$tag] ) !== false );
        
        return $args [1];
        
    }
    
    private function actions($tag,$args) {
        
        if (! isset ( $this->filter [$tag] )) {
            return;
        }
        
        if (! isset ( $this->ksort_flag [$tag] )) {
            ksort ( $this->filter [$tag] );
            $this->ksort_flag [$tag] = true;
        }
        
        reset ( $this->filter [$tag] );
        
        do {
            foreach ( ( array ) current ( $this->filter [$tag] ) as $the_ )
            if (! is_null ( $the_ ['fnc'] ))
                call_user_func_array ( $the_ ['fnc'], array_slice ( $args, 1, ( int ) $the_ ['arg_num'] ) );
        } while ( next ( $this->filter [$tag] ) !== false );
    }
    
    /**
     *
     * @since Version 1.0
     * @param string $tag            
     * @param unknown $fnc            
     * @param unknown $priority            
     * @return unknown string boolean
     */
    private function unique_id($tag, $fnc, $priority) {
        static $_unique_count = 0;
        
        if (is_string ( $fnc ))
            return $fnc;
        
        if (is_object ( $fnc )) {
            $fnc = array (
                    $fnc,
                    '' 
            );
        } else {
            $fnc = ( array ) $fnc;
        }
        
        if (is_object ( $fnc [0] )) {
            if (function_exists ( 'spl_object_hash' )) {
                return spl_object_hash ( $fnc [0] ) . $fnc [1];
            } else {
                $unique_id = get_class ( $fnc [0] ) . $fnc [1];
                if (! isset ( $fnc [0]->filter_id )) {
                    if (false === $priority)
                        return false;
                    $unique_id .= isset ( $this->filter [$tag] [$priority] ) ? count ( ( array ) $this->filter [$tag] [$priority] ) : $_unique_count;
                    $fnc [0]->filter_id = $_unique_count;
                    ++ $_unique_count;
                } else {
                    $unique_id .= $fnc [0]->filter_id;
                }
                
                return $unique_id;
            }
        } else if (is_string ( $fnc [0] )) {
            
            return $fnc [0] . '::' . $fnc [1];
        }
    }
}
<?php
defined('PF_VERSION') OR header('Location:404.html');
/**
 *
 * @package		Vitubo
 * @author		Vitubo Team 
 * @copyright	Vitubo Team
 * @link		http://www.vitubo.com
 * @since		Version 1.0
 * @filesource
 *
 */
global $option_datas;
$option_datas = array();

/**
 *
 * @param unknown $name        	
 * @return NULL
 */
function get_option($name) {
    global $option_datas;
    
	$name = trim ( $name );
    
    if (!empty($option_datas[$name])){
        return $option_datas[$name];
    }
    
	$db = Pf::database ();
	
	$db->select ( 'option_value', ''.DB_PREFIX.'options', 'option_name=?', array (
			$name 
	), '', '1' );
	$records = $db->fetch_assoc_all ();
	if (! empty ( $records )) {
		$option_value = @unserialize ( $records [0] ['option_value'] );
		if ($option_value == false){
			$option_value = $records [0] ['option_value'];
		}
		$option_datas[$name] = $option_value; 
		return $option_value;
	} else {
		return null;
	}
}
/**
 *
 * @param unknown $name        	
 * @param unknown $value        	
 */
function add_option($name, $value) {
    global $option_datas;

    $name = trim ( $name );
    $option_datas[$name] = $value;
    $db = Pf::database ();
	$serialized_value = $value;
	
	if (is_array ( $value ) || is_object ( $value ))
		$serialized_value = serialize ( $value );
	
	$result = $db->insert ( ''.DB_PREFIX.'options', array (
			'option_name' => $name,
			'option_value' => $serialized_value 
	) );
	
	return $result;
}
/**
 *
 * @param unknown $name        	
 * @param unknown $value        	
 */
function update_option($name, $value) {
    global $option_datas;
	$db = Pf::database ();
	
	if (is_array($value) && empty($value)){
	    $value = '';
	}
	
	$name = trim ( $name );
	$serialized_value = $value;
	
	if (is_array ( $value ) || is_object ( $value ))
		$serialized_value = serialize ( $value );
	
	$old = get_option ( $name );
	
	if ($serialized_value === $old)
		return false;
	
	if ($old === null)
		return add_option ( $name, $serialized_value );
	
	$result = $db->update ( ''.DB_PREFIX.'options', array (
			'option_name' => $name,
			'option_value' => $serialized_value
	), 'option_name = ?', array (
			$name
	) );

	$option_datas[$name] = $value;

	return $value;
}
/**
 *
 * @param unknown $name        	
 */
function delete_option($name) {
    global $option_datas;
    
	$db = Pf::database();
	$name = trim($name);
	unset($option_datas[$name]);
	
	$result = $db->delete(''.DB_PREFIX.'options', 'option_name = ?', array($name));
	
	return $result;
}
<?php
defined('PF_VERSION') OR header('Location:404.html');
/**
 * 
 * @return string
 */
function site_url() {
	$protocol = (! empty ( $_SERVER ['HTTPS'] ) && $_SERVER ['HTTPS'] !== 'off' || $_SERVER ['SERVER_PORT'] == 443) ? "https://" : "http://";
	$domainName = $_SERVER ['HTTP_HOST'];
	return $protocol . $domainName;
}
/**
 * 
 * @param string $params
 * @param string $merger
 * @return string
 */
function admin_url($params = null,$merger = true) {
	$url = site_url () . RELATIVE_PATH . '/'.ADMIN_FOLDER.'/';
	
	$query_string = array ();
	if (! empty ( $_SERVER ['QUERY_STRING'] )) {
		parse_str ( $_SERVER ['QUERY_STRING'], $query_string );
	}

	if (! empty ( $params )) {
		if (is_string ( $params )) {
			$new_params = array ();
			parse_str ( $params, $new_params );
			$params = $new_params;
		}
	}
	
	if (true == $merger){
		$params = (! empty ( $params )) ? array_merge (
				$query_string,
				$params 
		) : $query_string;
	}
	
	foreach ( $params as $k => $v ) {
		if (empty ( $v )) {
			unset ( $params [$k] );
		}
	}
	
	$url .= '?' . http_build_query ( $params, '', '&' );
	
	$url = str_replace ( array (
	        "'",
	        '"'
	), array (
	        "&#39;",
	        "&quot;"
	), $url );
	
	return $url;
}
/**
 * 
 * @param unknown $file
 * @return string
 */
function abs_plugin_path($file){
    $file = preg_replace('/\\\/', '/', dirname($file));
    return (strpos($file, '/plugins/default/') !== false)?DEFAULT_PLUGIN_PATH:PLUGIN_PATH;
}
function _get($param){
    if(isset($_GET[$param]))
        return $_GET[$param];
    else
        return '';
}
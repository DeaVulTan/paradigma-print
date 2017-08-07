<?php
defined('PF_VERSION') OR header('Location:404.html');
class Pf_Shortcode {
	private $tags = array ();
	private $exec_tag = true;
	private $scanned_tags = array();
	/**
	 * 
	 * @param unknown $tag
	 * @param unknown $func
	 */
	public function add($tag, $func, $ns = 'pf') {
		
		if (is_callable ( $func )){
			$this->tags [$ns][$tag] = $func;
		}
	}
	/**
	 * 
	 * @param unknown $tag
	 */
	public function remove($tag,$ns = 'pf') {
		
		unset ( $this->tags[$ns][$tag] );
	}
	/**
	 * 
	 */
	public function remove_all() {
		
		$this->tags = array ();
	}
	/**
	 * Return all shortcode tags
	 * @param unknown $content
	 * @return boolean|unknown
	 */
	public function get_tags(){
	    
	    return $this->tags;
	}
	/**
	 * 
	 * @param unknown $content
	 * @return unknown|mixed
	 */
	public function exec($content, $exec = true) {
	    if (empty($content)) return '';
	    
	    $this->exec_tag = (bool) $exec;
	    $this->scanned_tags = array();
	    
		if (empty ( $this->tags ) || ! is_array ( $this->tags ))
			return $content;
		$tagnames = array();
		
		preg_match_all("/\{([a-z]*:[a-zA-Z0-9_-]*)/s", $content,$match);
		
		if (empty($match[1])){
		    return $content;
		}else{
            foreach($this->tags as $ns => $tags){
        		$tmp_tags = array_keys ( $tags );
        		foreach ($tmp_tags as $k => $v){
        		    if (in_array($ns.':'.$v, $match[1])){
        		      $tagnames[count($tagnames)] = $ns.':'.$v;
        		    }
        		}
            }
            
    		$tagregexp = join ( '|', array_map ( 'preg_quote', $tagnames ) );
    		
    		$content = preg_replace("/(<p>)?\{($tagregexp)(\s[^\}]+)?\}(<\/p>|<br \/>)?/",'{$2$3}',$content);
    		$content = preg_replace("/(<p>)?\{\/($tagregexp)\}(<\/p>|<br \/>)/",'{/$2}',$content);
    		
    		
    		$pattern = $this->get_pattern ($tagregexp);
    		return preg_replace_callback ( "/$pattern/s", array($this, 'do_tag'), $content );
		}
	}
	
	public function scan($content){
	    $this->exec($content,false);
	    
	    return $this->scanned_tags;
	}
	
	/**
	 * 
	 * @return string
	 */
	private function get_pattern($tagregexp) {
		
		return '\\{(\\{?)('.$tagregexp.')(?![\\w-])([^\\}\\/]*(?:\\/(?!\\})[^\\}\\/]*)*?)(?:(\\/)\\}|\\}(?:([^\\{]*+(?:\\{(?!\\/\\2\\})[^\\{]*+)*+)\\{\\/\\2\\})?)(\\}?)';                          
	}
	/**
	 * 
	 * @param unknown $m
	 * @return string
	 */
	private function do_tag($m) {
		if ($m [1] == '{' && $m [6] == '}') {
			return substr ( $m [0], 1, - 1 );
		}
		
		$tag = explode(':', $m [2]);
		$attr = $this->parse_atts ( $m [3] );
		
		if (!empty($m [5])){
		    $m [5] = preg_replace("/(\&nbsp;)*/", '', $m [5]);
		    $m [5] = preg_replace("/<p[^>]*>/", '', $m [5]);
		    $m [5] = preg_replace("/<\/p>/", '', $m [5]);
		}
		
		if ($this->exec_tag){
		    $return = '';
		    ob_start();
    		if (isset ( $m [5] )) {
    			$return = $m [1] . call_user_func ( $this->tags [$tag[0]] [$tag[1]], $attr, $m [5], $tag[1] ) . $m [6];
    		} else {
    			$return = $m [1] . call_user_func ( $this->tags [$tag[0]] [$tag[1]], $attr, null, $tag[1] ) . $m [6];
    		}
    		$return2 = ob_get_contents();
    		ob_get_clean();
    		
    		$return = (!empty($return))?$return:(!empty($return2)?$return2:'');
    		
    		return $return;
		}else{
            $content = (isset($m[5]))?$m[5]:null;
		    $this->scanned_tags[] = array('tag' => $tag,'attr' => $attr, 'content' => $content);
		}
	}
	/**
	 * 
	 * @param unknown $text
	 * @return Ambigous <string, multitype:string >
	 */
	private function parse_atts($text) {
		$atts = array ();
		$pattern = '/([\w-:]+)\s*=\s*"([^"]*)"(?:\s|$)|([\w-]+)\s*=\s*[\'|\"]([^\'^\"]*)[\'|\"](?:\s|$)|(\w+)\s*=\s*([^\s\'"]+)(?:\s|$)|"([^"]*)"(?:\s|$)|(\S+)(?:\s|$)/';
		$text = preg_replace ( "/[\x{00a0}\x{200b}]+/u", " ", $text );
		if (preg_match_all ( $pattern, $text, $match, PREG_SET_ORDER )) {
			foreach ( $match as $m ) {
				if (! empty ( $m [1] )){
					$atts [strtolower ( $m [1] )] = stripcslashes ( $m [2] );
				}elseif (! empty ( $m [3] )){
					$atts [strtolower ( $m [3] )] = stripcslashes ( $m [4] );
				}elseif (! empty ( $m [5] )){
					$atts [strtolower ( $m [5] )] = stripcslashes ( $m [6] );
				}elseif (isset ( $m [7] ) && strlen ( $m [7] )){
					$atts [] = stripcslashes ( $m [7] );
				}elseif (isset ( $m [8] )){
					$atts [] = stripcslashes ( $m [8] );
				}
			}
		} else {
			$atts = ltrim ( $text );
		}
		return $atts;
	}
}
<?php
defined('PF_VERSION') OR header('Location:404.html');
class Pf {
	static protected $object = array();
	
	static public function database(){
		if (isset(self::$object['database'])){
			return self::$object['database'];
		}
		
		require ABSPATH . '/lib/zebra/database/Zebra_Database.php';
		self::$object['database'] = new Zebra_Database();
		if (is_ajax()){
			self::$object['database']->debug = false;
		}else{
			self::$object['database']->debug = DEBUG;
		}
		self::$object['database']->connect(DB_HOST, DB_USER, DB_PASSWORD, DB_NAME,DB_PORT,DB_SOCKET);
		self::$object['database']->set_charset(DB_CHARSET,DB_COLLATE);
		
		return self::$object['database'];
	}
	static public function event(){
	    if (isset(self::$object['event'])){
	        return self::$object['event'];
	    }
	    require ABSPATH . '/lib/event-class.php';
	    self::$object['event'] = new Pf_Event();
	    return self::$object['event'];
	}
	static public function auth(){
        if (isset(self::$object['auth'])){
            return self::$object['auth'];
        }
        self::$object['auth'] = new Auth();
        return self::$object['auth'];
    }
	static public function validator(){
		if (isset(self::$object['validator'])){
			return self::$object['validator'];
		}
		
		require ABSPATH . '/lib/gump/gump.class.php';
		self::$object['validator'] = new GUMP();
		
		return self::$object['validator'];
	}
	
	static public function shortcode(){
		if (isset(self::$object['shortcode'])){
			return self::$object['shortcode'];
		}
		
		require ABSPATH . '/lib/shortcode-class.php';
		self::$object['shortcode'] = new Pf_Shortcode();
		
		return self::$object['shortcode'];
	}
	
	static public function setting(){
	    if (isset(self::$object['setting'])){
	        return self::$object['setting'];
	    }
	    
	    require ABSPATH . '/lib/setting-class.php';
	    self::$object['setting'] = new Pf_Setting();
	    
	    return self::$object['setting'];
	}
	
	static public function language(){
		if (isset(self::$object['language'])){
			return self::$object['language'];
		}
		
		require ABSPATH . '/lib/language-class.php';
		self::$object['language'] = new Pf_Language();
		
		return self::$object['language'];
	}
	
	static public function email_template(){
	    if (isset(self::$object['email_template'])){
	        return self::$object['email_template'];
	    }
	    
	    require ABSPATH . '/lib/email-template-class.php';
	    self::$object['email_template'] = new Pf_Email_Template();
	    
	    return self::$object['email_template'];
	}
	
	static public function security(){
	    if (isset(self::$object['security'])){
	        return self::$object['security'];
	    }
	    require ABSPATH . '/lib/security-class.php';
	    self::$object['security'] = new Pf_Security();
	     
	    return self::$object['security'];
	}
	
	static public function cookie(){
	    if (isset(self::$object['cookie'])){
	        return self::$object['cookie'];
	    }
	    
	    require ABSPATH . '/lib/cookie-class.php';
	    self::$object['cookie'] = new Pf_Cookie();
	    
	    return self::$object['cookie'];
	}
    static public function acl(){
        if (isset(self::$object['acl'])){
            return self::$object['acl'];
        }

        self::$object['acl'] = new Pf_Acl();

        return self::$object['acl'];
    }
}
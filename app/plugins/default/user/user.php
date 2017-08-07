<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class User_Plugin extends Pf_Plugin {
    public $name = 'user';
    public $version = '1.0';
    public $author = 'Vitubo Team';
    public $description = 'This is the user description';
    public function activate() {
        $db = Pf::database();
        $sql = "DROP TABLE IF EXISTS `".DB_PREFIX."users`";
        $db->query($sql);
        $sql = "DROP TABLE IF EXISTS `".DB_PREFIX."role`";
        $db->query($sql);
        $sql = "CREATE TABLE `".DB_PREFIX."users` (
                `id` int(11) NOT NULL AUTO_INCREMENT,
                `user_name` varchar(20) NOT NULL,
                `user_firstname` varchar(50) NOT NULL,
                `user_lastname` varchar(50) NOT NULL,
                `user_password` varchar(40) NOT NULL,
                `user_email` varchar(50) NOT NULL,
                `user_role` smallint(6) NOT NULL,
                `user_registered_date` timestamp NULL DEFAULT NULL,
                `user_activation_key` varchar(10) NOT NULL,
                `user_activation` tinyint(4) NOT NULL DEFAULT '2',
                `user_forgot_pass_key` varchar(150) DEFAULT NULL,
                `user_login_time` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
                `user_login_ip` varchar(20) DEFAULT NULL,
                `user_avatar` varchar(100) NOT NULL,
                `login_attemp` timestamp NULL DEFAULT NULL,
                `public_profile` tinyint(4) NOT NULL DEFAULT '1',
                `user_delete_flag` tinyint(1) NOT NULL DEFAULT '0',
                PRIMARY KEY (`id`)
               ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
        $db->query($sql);
        $sql = "
                CREATE TABLE `".DB_PREFIX."role` (
                        `id` int(11) NOT NULL AUTO_INCREMENT,
                        `role_name` varchar(20) CHARACTER SET latin1 NOT NULL,
                        PRIMARY KEY (`id`)
                       ) ENGINE=InnoDB AUTO_INCREMENT=6 DEFAULT CHARSET=utf8;
                      ";
        $db->query($sql);
        $db->insert_bulk(''.DB_PREFIX.'users', array('id', 'role_name'), array(
                array('1', 'Administrator'),
                array('2', 'Editor'),
                array('3', 'Author'),
                array('4', 'Contributor'),
                array('5', 'User'),
        ));
    }
    public function deactivate() {
        $db = Pf::database();
        $sql = "DROP TABLE IF EXISTS `".DB_PREFIX."users`";
        $db->query($sql);
        $sql = "DROP TABLE IF EXISTS `".DB_PREFIX."role`";
        $db->query($sql);
    }
    
    public function public_init() {
    	require_once abs_plugin_path(__FILE__) ."/user/class/pf-user-security.php";
    	require_once abs_plugin_path(__FILE__) ."/user/class/pf-role.php";
    	$this->add_shortcode('user', 'signin');
    	$this->add_shortcode('user', 'signup');
    	$this->add_shortcode('user', 'signout');
    	$this->add_shortcode('user', 'activation');
    	$this->add_shortcode('user', 'recover');
    	$this->add_shortcode('user', 'profile');
    	$this->add_shortcode('user', 'inbox');
    	$this->add_shortcode('user', 'lostpassword');
    }
    
    public function admin_init() {
    	require_once abs_plugin_path(__FILE__) . "/user/user-config.php";
    	$this->admin_menu ( 'fa fa-tasks', __('Users', 'user'), 'user', 'user');
    	if (is_admin()){
            $this->admin_children_menu('fa fa-angle-double-right', __('User Manager', 'user'),'user', 'user', 'user');
            $this->admin_children_menu('fa fa-angle-double-right', __('Custom Fields', 'user'),'customfields', 'customfields', 'user');
        }else{
            $this->admin_children_menu('fa fa-angle-double-right', __('My Profile', 'user'),'user', 'myprofile', 'user');
        }
    }
    
    public function user(){
        $this->js('media/assets/jquery-loadmask-0.4/jquery.loadmask.min.js');
        $this->css('media/assets/jquery-loadmask-0.4/jquery.loadmask.css');
        
        $this->js('media/assets/bootstrap-notification/js/bootstrap.notification.js');
        $this->js ('media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
        $this->css('media/assets/bootstrap-notification/css/animate.min.css');
        
        $this->js  ( 'media/assets/bootstrap-modal/js/bootstrap.modal.js' );
        $this->js  ( ADMIN_FOLDER.'/themes/default/assets/tinymce/js/tinymce/tinymce.min.js' );
        $this->css('app/plugins/default/user/assets/users.css');
        $this->js('media/assets/moment/js/moment.js');        
        $this->js('media/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');
        $this->css('media/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');
        
        $this->js ( 'media/assets/fancybox/jquery.fancybox-1.3.6.pack.js' );
        $this->css ('media/assets/fancybox/jquery.fancybox-1.3.6.css' );
    }
    public function customfields(){
        $this->js('media/assets/jquery-loadmask-0.4/jquery.loadmask.min.js');
        $this->css('media/assets/jquery-loadmask-0.4/jquery.loadmask.css');
    
        $this->js('media/assets/bootstrap-notification/js/bootstrap.notification.js');
        $this->js ('media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
        $this->css('media/assets/bootstrap-notification/css/animate.min.css');
        $this->css('app/plugins/default/user/assets/users.css');
        $this->js  ( 'media/assets/bootstrap-modal/js/bootstrap.modal.js' );
        $this->js  ( ADMIN_FOLDER.'/themes/default/assets/tinymce/js/tinymce/tinymce.min.js' );
        $this->js('media/assets/moment/js/moment.js');
        $this->js('media/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');
        $this->css('media/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');
    
        $this->js ( 'media/assets/fancybox/jquery.fancybox-1.3.6.pack.js' );
        $this->css ('media/assets/fancybox/jquery.fancybox-1.3.6.css' );
    }
    public function myprofile(){
        $this->js('media/assets/jquery-loadmask-0.4/jquery.loadmask.min.js');
        $this->css('media/assets/jquery-loadmask-0.4/jquery.loadmask.css');
        $this->css('app/plugins/default/user/assets/users.css');
        $this->js('media/assets/bootstrap-notification/js/bootstrap.notification.js');
        $this->js ('media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
        $this->css('media/assets/bootstrap-notification/css/animate.min.css');
    
        $this->js  ( 'media/assets/bootstrap-modal/js/bootstrap.modal.js' );
        $this->js  ( ADMIN_FOLDER.'/themes/default/assets/tinymce/js/tinymce/tinymce.min.js' );
    
        $this->js('media/assets/moment/js/moment.js');
        $this->js('media/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');
        $this->css('media/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');
    
        $this->js ( 'media/assets/fancybox/jquery.fancybox-1.3.6.pack.js' );
        $this->css ('media/assets/fancybox/jquery.fancybox-1.3.6.css' );
    }
}
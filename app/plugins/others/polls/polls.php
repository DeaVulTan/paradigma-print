<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Polls_Plugin extends Pf_Plugin {
    public $name = 'Polls';
    public $version = '1.0';
    public $author = 'Vitubo Team';
    public $description = 'This is the Polls description';
    public function activate() {
        $db = Pf::database();
        
        $sql = "DROP TABLE IF EXISTS `".DB_PREFIX."polls_questions`,`".DB_PREFIX."polls_ip`,`".DB_PREFIX."polls_answers`;";
        $db->query($sql);
        
        $db->query("CREATE TABLE `".DB_PREFIX."polls_questions` (
               `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
               `polls_question` varchar(200) NOT NULL,
               `polls_pubdate` timestamp NULL DEFAULT NULL,
               `polls_unpubdate` timestamp NULL DEFAULT NULL,
               `polls_status` tinyint(4) NOT NULL,
               `polls_totalvote` int(10) NOT NULL,
               `polls_multiple` tinyint(3) NOT NULL,
               PRIMARY KEY (`id`)
                ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
            ");
        
        $db->query("CREATE TABLE `".DB_PREFIX."polls_ip` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `pollsip_qid` varchar(10) NOT NULL,
              `pollsip_aid` varchar(10) NOT NULL,
              `pollsip_ip` varchar(50) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        ");
        
        $db->query("CREATE TABLE `".DB_PREFIX."polls_answers` (
              `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
              `pollsa_qid` int(10) NOT NULL,
              `pollsa_answers` varchar(200) NOT NULL,
              `pollsa_vote` int(10) NOT NULL,
              PRIMARY KEY (`id`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8 AUTO_INCREMENT=1 ;
        ");
    }
    public function deactivate() {
        $db = Pf::database();
        $sql = "DROP TABLE IF EXISTS `".DB_PREFIX."polls_questions`,`".DB_PREFIX."polls_ip`,`".DB_PREFIX."polls_answers`;";
        $db->query($sql);
    }
    public function admin_init() {
        require_once abs_plugin_path(__FILE__) . "/polls/polls-config.php";
        $this->admin_menu ( 'fa fa-signal', __('Polls', 'polls'), 'polls', 'polls' );
    }
    
    public function polls(){
        $this->js('media/assets/jquery-loadmask-0.4/jquery.loadmask.min.js');
        $this->css('media/assets/jquery-loadmask-0.4/jquery.loadmask.css');
        
        $this->js('media/assets/bootstrap-notification/js/bootstrap.notification.js');
        $this->js ('media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
        $this->css('media/assets/bootstrap-notification/css/animate.min.css');
        $this->css('app/plugins/others/polls/admin/assets/polls.css');
        $this->js  ( 'media/assets/bootstrap-modal/js/bootstrap.modal.js' );
        $this->js  ( ADMIN_FOLDER.'/themes/default/assets/tinymce/js/tinymce/tinymce.min.js' );
        $this->js('media/assets/moment/js/moment.js');        
        $this->js('media/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');
        $this->css('media/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');
        $this->js ( 'media/assets/fancybox/jquery.fancybox-1.3.6.pack.js' );
        $this->css ('media/assets/fancybox/jquery.fancybox-1.3.6.css' );
        $this->js('media/assets/handlebars/js/handlebars.min.js');
    }
    public function public_init() {
        $this->add_shortcode('polls', 'display','display');
    }
}
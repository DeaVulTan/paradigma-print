<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Contactform_Plugin extends Pf_Plugin {
	public $name = 'Contactform';
	public $version = '1.0';
	public $author = 'Vitubo Team';
	public $description = 'This is Contactform_Plugin description';
	public function activate() {
		$db = Pf::database();
        $count = $db->dcount('*', ''.DB_PREFIX.'options', "`option_name`='contactform'");
        if ($count == 0) {
            $sql = "INSERT INTO `".DB_PREFIX."options` ( `option_name`, `option_value`) VALUES ('contactform', '')";
            $db->query($sql);
        }
	}
	public function deactivate() {
	
	}
	public function admin_init() {
		if (is_admin() or is_editor())
		$this->admin_menu ( 'fa fa-tasks', __('Contact Forms', 'contactform'), 'contactform', 'contactform' );
		$shortcode = Pf::shortcode();
		$shortcode->add('text', array($this, 'plugin_contactform_admin'),'ct');
		$shortcode->add('email', array($this, 'plugin_contactform_admin'),'ct');
		$shortcode->add('radio', array($this, 'plugin_contactform_admin'),'ct');
		$shortcode->add('dropdown', array($this, 'plugin_contactform_admin'),'ct');
		$shortcode->add('textarea', array($this, 'plugin_contactform_admin'),'ct');
		$shortcode->add('submit', array($this, 'plugin_contactform_admin'),'ct');
		$shortcode->add('captcha', array($this, 'plugin_contactform_admin'),'ct');
		$shortcode->add('acceptance', array($this, 'plugin_contactform_admin'),'ct');
		$shortcode->add('date', array($this, 'plugin_contactform_admin'),'ct');
		$shortcode->add('number', array($this, 'plugin_contactform_admin'),'ct');
		$shortcode->add('url', array($this, 'plugin_contactform_admin'),'ct');
		$shortcode->add('checkbox', array($this, 'plugin_contactform_admin'),'ct');
	}
	public function public_init() {
		$this->add_shortcode('contactform', 'display','display');
		$this->add_shortcode('ct','text','text');
		$this->add_shortcode('ct','hidden','hidden');
		$this->add_shortcode('ct','email','email');
		$this->add_shortcode('ct','radio','radio');
		$this->add_shortcode('ct','dropdown','dropdown');
		$this->add_shortcode('ct','textarea','textarea');
		$this->add_shortcode('ct','submit','submit');
		$this->add_shortcode('ct','reset','reset');
		$this->add_shortcode('ct','button','button');
		$this->add_shortcode('ct','captcha','captcha');
		$this->add_shortcode('ct','acceptance','acceptance');
		$this->add_shortcode('ct','date','date');
		$this->add_shortcode('ct','number','number');
		$this->add_shortcode('ct','url','url');
		$this->add_shortcode('ct','checkbox','checkbox');
		require_once ABSPATH . '/lib/recaptchalib.php';
	}
	
	public function contactform(){
		$this->js('media/assets/jquery-loadmask-0.4/jquery.loadmask.min.js');
		$this->css('media/assets/jquery-loadmask-0.4/jquery.loadmask.css');

		$this->css('media/assets/magicsuggest/css/magicsuggest-min.css');
		$this->js('media/assets/magicsuggest/js/magicsuggest-min.js');

		$this->js('media/assets/bootstrap-notification/js/bootstrap.notification.js');
		$this->js ('media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
		$this->css('media/assets/bootstrap-notification/css/animate.min.css');

		$this->js  ( 'media/assets/bootstrap-modal/js/bootstrap.modal.js' );
		$this->js  ( ADMIN_FOLDER.'/themes/default/assets/tinymce/js/tinymce/tinymce.min.js' );

		$this->js('media/assets/moment/js/moment.js');
		$this->js('media/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');
		$this->css('media/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');
		admin_js('lib/common/plugin/assets/base.js?t=95');

		$this->js ( 'media/assets/fancybox/jquery.fancybox-1.3.6.pack.js' );
		$this->css ('media/assets/fancybox/jquery.fancybox-1.3.6.css' );
		$this->js('media/assets/handlebars/js/handlebars.min.js');
		$this->css('app/plugins/others/contactform/assets/contactform.css');
		//admin_js('app/plugins/others/contactform/assets/contactform.js?t=' . uniqid());
		admin_js('app/plugins/others/contactform/assets/jquery.selection.js');
	}
	function plugin_contactform_admin()
	{
	
	}
	
}
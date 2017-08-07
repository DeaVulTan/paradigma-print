<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Plugin_Plugin extends Pf_Plugin {
    public $name = 'Plugin';
    public $version = '2.0';
    public $author = 'Vitubo Team';
    public $description = 'This is the Plugin system';
       
    public function admin_init() {
        if(is_admin()){
            $this->admin_menu ( 'fa fa-cogs', __('Plugins', 'plugin'), 'plugin', 'plugin' );
        }
    }
    public function plugin(){
        $this->js('media/assets/jquery-loadmask-0.4/jquery.loadmask.min.js');
        $this->css('media/assets/jquery-loadmask-0.4/jquery.loadmask.css');

        $this->js('media/assets/bootstrap-notification/js/bootstrap.notification.js');
        $this->js ('media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js' );
        $this->css('media/assets/bootstrap-notification/css/animate.min.css');

        $this->js  ('media/assets/bootstrap-modal/js/bootstrap.modal.js' );
        $this->js  ( ADMIN_FOLDER.'/themes/default/assets/tinymce/js/tinymce/tinymce.min.js' );

        $this->js('media/assets/moment/js/moment.js');
        $this->js('media/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');
        $this->css('media/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');

        $this->js ('media/assets/fancybox/jquery.fancybox-1.3.6.pack.js' );
        $this->css ('media/assets/fancybox/jquery.fancybox-1.3.6.css' );

        $this->js ('media/assets/bootbox/js/bootbox.min.js');
        $this->js ('lib/common/plugin/assets/base.js');
        //<script src="/pageflex/admin/themes/default/assets/admin-lte/js/AdminLTE/app.js"></script>
        $this->js ('admin/themes/default/assets/admin-lte/js/AdminLTE/app.js');
    }
}

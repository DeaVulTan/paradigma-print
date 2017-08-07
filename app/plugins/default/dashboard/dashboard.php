<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php
/**
 *
 * @package		Vitubo
 * @author		Vitubo Team 
 * @copyright   Vitubo Team
 * @link		http://www.vitubo.com
 * @since		Version 1.0
 * @filesource
 *
 */
define("P", "position");
define("W", "widget");
define('PLUGIN_DASHBOARD', __('Dashboard', 'dashboard'));

class Dashboard_Plugin extends Pf_Plugin
{

    public $name = PLUGIN_DASHBOARD;
    public $version = '1.0';
    public $author = 'Vitubo Team';
    public $description = 'This is the Dashboard description';
    public $config;

    public function __construct()
    {
        
    }

    public function activate()
    {
        update_option('dashboard', array());
    }

    public function deactivate()
    {
        delete_option('dashboard');
    }

    public function insert_calendar()
    {
        $default_layout = get_default_layout();
        $data = get_option('dashboard');
        $id = current_user('user-id');
        if (!count($data)) {
            $data = array();
            $data[$id] = array(
                'id' => $id,
                'data' => array(),
                'layout' => $default_layout
            );
            update_option('dashboard', $data);
        } else {
            if (!isset($data[$id])) {
                $data[$id] = array(
                    'id' => $id,
                    'data' => array(),
                    'layout' => $default_layout
                );
                update_option('dashboard', $data);
            }
        }
    }

    public function admin_init()
    {
        include_once "dashboard-helper.php";
        if (is_login()) {
            $this->insert_calendar();
        }
        $this->admin_menu('fa fa-dashboard', __('Dashboard', 'dashboard'), 'dashboard', 'plugin_dashboard_manager');
    }

    public function plugin_dashboard_manager()
    {
        $this->js('media/assets/bootstrap-notification/js/jquery/jquery.easing.1.3.js');
        $this->js('media/assets/bootstrap-notification/js/bootstrap.notification.js');
        $this->js('lib/common/plugin/assets/base.js?t=95');

        //Fancy box
        $this->js('media/assets/fancybox/jquery.fancybox-1.3.6.pack.js');
        $this->css('media/assets/fancybox/jquery.fancybox-1.3.6.css');

        $this->js('app/plugins/default/dashboard/assets/validate-base.js');
        $this->js('app/plugins/default/dashboard/assets/dashboard.js');
        $this->js('app/plugins/default/dashboard/assets/dashboard-sortable.js');
        $this->css('app/plugins/default/dashboard/assets/dashboard.css');
        $this->js('media/assets/fullcalendar/js/fullcalendar.min.js');
        $this->css('media/assets/fullcalendar/css/fullcalendar.css');
        $this->js('media/assets/bootbox/js/bootbox.min.js');
        //Datetimepicker
        $this->js('media/assets/moment/js/moment.js');
        $this->js('media/assets/bootstrap-datetimepicker/js/bootstrap-datetimepicker.js');
        $this->css('media/assets/bootstrap-datetimepicker/css/bootstrap-datetimepicker.css');
        $this->css('app/plugins/default/dashboard/assets/alert.css');


        require_once abs_plugin_path(__FILE__) . '/dashboard/include/pf-dashboard.php';
        require_once abs_plugin_path(__FILE__) . '/dashboard/index.php';
    }

}

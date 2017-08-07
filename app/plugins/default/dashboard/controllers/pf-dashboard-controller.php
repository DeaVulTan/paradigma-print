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
class Pf_Dashboard_Controller extends Pf_Plugin_Controller
{
    public $config;
    private $option_user;
    private function getOptionUser(){
        $data = get_option('dashboard');
        $this->option_all_dashboard = $data;
    }
    public function __construct()
    {
        parent::__construct();

        $this->dashboard = new Pf_Dashboard();
        $this->getOptionUser();
        $this->view->set_path('dashboard',true);
    }
    public function main()
    {
        $this->update_status();
        $id = current_user('user-id');
        $this->data['dash'] = $this->dashboard;
        $this->data['layout'] = $this->option_all_dashboard[$id]['layout'];
        $this->view->render('main', $this->data);
    }
    private function update_status(){
        if ($this->input->post('token_key')) {
            $data = $this->input->post();
            if(isset($data['id']) && count($data['id'])){
               $array_config_model = $this->dashboard->check_exits_key($data['token_key']);
                if(count($array_config_model)){
                    if(isset($array_config_model['table'])){
                        $db = Pf::database();
                        $table = $array_config_model['table'];
                        $key_primary = (isset($array_config_model['action']['approve']['columns']['id']))?$array_config_model['action']['approve']['columns']['id']:"id";
                        $columns_status = (isset($array_config_model['action']['approve']['columns']['status']))?$array_config_model['action']['approve']['columns']['status']:"status";
                        foreach($data['id'] as $id){
                           if(Pf_Plugin_CSRF::is_valid($data['token'][$id],$id)){
                               $db->update(
                                   $table,
                                   array(
                                       $columns_status => !$data['status_'.$id],
                                   ),
                                   $key_primary.' = ?',
                                   array($id)
                               );
                           }
                       }
                    }
                }
            }
        }
    }
    public function ajax(){
        $request = ($this->input->post('action'))?$this->input->post():$this->input->get();
        if (isset($request['action'])) {
            $data = $request;
            switch($request['action']){
                case 'update_approve_all':

                    if(isset($data['token_key'])){
                        $array_config_model = $this->dashboard->check_exits_key($data['token_key']);
                        if(count($array_config_model)){
                            if(isset($array_config_model['table'])){
                                $db = Pf::database();
                                $table = $array_config_model['table'];

                                $columns_status = (isset($array_config_model['action']['approve']['columns']['status']))?$array_config_model['action']['approve']['columns']['status']:"status";
                                $db->update(
                                    $table,
                                    array(
                                        $columns_status =>1
                                    ),
                                    $columns_status.' = 0'
                                );
                            }
                        }
                    }

                    echo json_encode(array());
                break;
                case 'get_info':
                    $array = array();
                    if(isset($data['token_key'])){
                       $db = Pf::database();
                       $array_config_model = $this->dashboard->check_exits_key($data['token_key']);
                       if(count($array_config_model)){
                           if(isset($array_config_model['table'])){
                               $table = $array_config_model['table'];
                               $key_primary = (isset($array_config_model['action']['approve']['columns']['id']))?$array_config_model['action']['approve']['columns']['id']:"id";
                               $columns_select ="";
                               foreach($array_config_model['action']['approve']['columns'] as $__row){
                                   $columns_select.= $__row.",";
                               }
                               $columns_select = empty($columns_select)?rtrim($columns_select):"*";
                               $db->select(
                                    $columns_select,
                                    $table,
                                    $key_primary.' = ?',
                                    array($data['id'])
                               );
                               $rows = $db->fetch_assoc_all();
                               if(count($rows)){
                                   foreach($rows as $row){
                                        foreach($array_config_model['action']['approve']['columns'] as $k=>$r){
                                            $array[$k] = $row[$r];
                                        }
                                   }
                               }
                           }
                       }
                    }
                   echo json_encode($array);
                break;
                case 'calendar_get_data':
                    $id = current_user('user-id');
                    $array_json = $this->option_all_dashboard[$id]['data'];
                    echo json_encode($array_json);
                    exit;
                break;
                case 'calendar_save':
                    $data_dashboard = $this->option_all_dashboard;
                    $id = current_user('user-id');
                    $table_columns = array(
                        'title',
                        'url',
                        'content',
                        'start',
                        'end'
                    );
                    foreach($data as $key=>$value){
                        if(in_array($key,$table_columns)){
                            $column_update[$key] = $value;
                        }
                    }
                    if(empty($column_update['end'])){
                        $column_update['end'] = $column_update['start'];
                    }
                    if(isset($data['id'])){
                        dashboard_save($id,$column_update,$data_dashboard,$data['id']);
                    }else{
                        dashboard_save($id,$column_update,$data_dashboard);
                    }
                    update_option('dashboard',$data_dashboard);
                    echo json_encode($column_update);
                break;
                case 'clear_events':
                    $data_dashboard = $this->option_all_dashboard;
                    $id = current_user('user-id');
                    if($data['type'] == 'all'){
                        dashboard_event_delete($id,$data_dashboard);
                        update_option('dashboard',$data_dashboard);
                    }else{
                        dashboard_event_delete($id,$data_dashboard,null,$data['type']);
                        update_option('dashboard',$data_dashboard);
                    }
                    echo json_encode($data);
                break;
                case 'delete_events':
                    $data_dashboard = $this->option_all_dashboard;
                    $id = current_user('user-id');
                    if(is_numeric($data['id'])){
                        dashboard_event_delete($id,$data_dashboard,$data['id']);
                        update_option('dashboard',$data_dashboard);
                    }
                    echo json_encode($data);
                break;
                case 'layout':
                    $id = current_user('user-id');
                    $layout_array = $this->option_all_dashboard[$id]['layout'];
                    foreach($layout_array as $key=>$rows){
                        foreach($rows as $k=>$row){
                            if(isset($row[P])){
                                $layout_array[$key][$k][W] = array();
                                if(isset($data[$row[P]])){
                                    foreach($data[$row[P]] as $_k=>$_v){
                                        $layout_array[$key][$k][W][$_k] = str_replace("list-sort-","",$_v);
                                    }
                                }
                            }
                        }
                    }
                    $this->option_all_dashboard[$id]['layout'] = $layout_array;
                    update_option('dashboard',$this->option_all_dashboard);
                    echo json_encode($data);
                break;
            }
        }
        die();
    }
}
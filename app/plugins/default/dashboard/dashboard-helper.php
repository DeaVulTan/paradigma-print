<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php
if (!function_exists('check_permission_approve')) {

    function check_permission_approve_comment()
    {
        if (get_configuration('approve_flag', 'pf_comment') == true) {
            $users = get_configuration('approve', 'pf_comment');
            if (empty($users) || !in_array(current_user('user-id'), $users)) {
                return false;
            }
        }
        return true;
    }

}
function render_layout_dashboard($lay,$dash,$col=1){
    if(isset($lay['widget'])){
        $sortable = isset($lay[P])?" connectedSortable":"";
        $sortable = "";
        $id_html = "";
        $sortable_tmp ="";
        if(isset($lay[P])){
            $sortable = " connectedSortable";
            $sortable_tmp = " position-sort-temp";
            $id_html = 'id="'.$lay[P].'"';
        }
        $path = abs_plugin_path(__FILE__).'/dashboard/views/widget/';

        echo '<section class="col-lg-'.(12/$col).$sortable.'" '.$id_html.' >';
        echo '<div class="box box-warning'.$sortable_tmp.'"  style="height: 10px;display: none;"><div class="box-header"></div><div class="box-body no-padding"></div></div>';
        if(is_array($lay[W])){
            foreach($lay[W] as $value){
                if(strpos($value,"/")){
                    if(preg_match('#^/?(?P<control>[\w]+)?(?:/(?P<action>[\w]+))?(?:/(?P<param>[\d]+))?#',$value,$matches)) {
                        extract($matches);
                        $dashboards = $dash->run_action($control,$action);
                        $id = $value;
                        $include = true;
                        if(isset($dashboards[$action][$control]['acl'])){
                             if(!plugin_check_acl($dashboards[$action][$control]['acl'])){
                                continue;
                             }
                        }
                        if(isset($dashboards[$action][$control]['active'])&&!$dashboards[$action][$control]['active']){
                            continue;
                        }
                        /* thay doi*/
                        $permission_approve = true;
                        if($control == 'comment'){
                            $permission_approve = check_permission_approve_comment();
                        }
                        /*end thay doi*/
                        if(isset($dashboards['plugin_active'])){
                            if($dashboards['plugin_active'][$control]['active']){
                                if(file_exists($path.$control.'.php')){
                                    include $path.$control.'.php';
                                }
                            }
                        }else{
                            $view_url = false;
                            if(is_admin() || is_editor()){
                                $view_url = true;
                            }

                            include $path.$action.'.php';
                        }

                    }
                }else if(!empty($value)){
                    $id = $value;
                    if(file_exists($path.$value.'.php')){
                        include $path.$value.'.php';
                    }
                }
            }
        }else{
            $input =  $lay['widget'];
            $sortable = isset($lay[P])?" connectedSortable":"";
            if(strpos($input,"/")){
                if(preg_match('#^/?(?P<control>[\w]+)?(?:/(?P<action>[\w]+))?(?:/(?P<param>[\d]+))?#',$value,$matches)) {
                    extract($matches);
                    $dashboards = $dash->run_action($control,$action);
                    $id = $value;

                    if(file_exists($path.$action.'.php')){
                        include $path.$action.'.php';
                    }

                }
            }else if(!empty($input)){
                $id = $input;

                if(file_exists($path.$id.'.php')){
                    include $path.$id.'.php';
                }
            }
        }
        echo "</section>";

    }else if(is_array($lay)){
        foreach($lay as $key=>$value){
            if(is_numeric($key)){
                $col=count($value);
            }
            render_layout_dashboard($value,$dash,12/$col);
        }
    }
}
function get_row_new($row){
    if(!is_array($row)){
        return false;
    }
    if(isset($row['id'])){
        $_row = array(
            'id'=>'',
            'data'=>'[]'
        );
        return array_merge($_row,$row);
    }
}
function dashboard_build_data($column_event){
    $_column_event = array(
        'id'=>"",
        "title"=>"",
        'content'=>"",
        'url'=>"",
        'start'=>"",
        'end'=>""
    );
    return (array_merge($_column_event,$column_event));
}

function update_json($json_data,$primary){
    foreach($json_data as $key=>$row){
        if($row['id'] == $primary){
            return (array) $row;
        }
    }
}

function find_key_json($json_data,$primary){

    foreach($json_data as $key=>$row){
            if($row['id'] == $primary){
                return $key;
            }
    }
    return -1;
}
function dashboard_save($id,$data,&$mongodb,$id_data=null){
    $_mongodb = array();
    $data['id']=1;
    $row = array("user"=>$id,'data'=>array(dashboard_build_data($data)));
    if(isset($mongodb[$id])){
        /*Up*/
        $data_user = $mongodb[$id];
        $data_event =  $mongodb[$id]['data'];
       // print_r($data_event);
        if(is_null($id_data)){
            /*Insert Json*/
             if(count($data_event)){
                 $data['id']=$data_event[count($data_event)-1]['id']+1;
             }else{
                 $data['id'] = 1;
             }
             array_push($data_event,dashboard_build_data($data));
        }else{
            /*Update Json*/
            $key_update = find_key_json($data_event,$id_data);
            $data['id'] =  $id_data;
            if($key_update!=-1){
                $__data = dashboard_build_data($data);
                foreach($__data as $v=>$l){
                    if(empty($l)){
                        $__data[$v] = $data_event[$key_update][$v];
                    }
                }

                $data_event[$key_update] = $__data;
            }
        }
        $mongodb[$id]['data'] = $data_event;
    }else{
        $mongodb[$id] = $row;
    }
}

function dashboard_event_delete($id,&$mongodb,$id_data =null,$type='all'){
    if(isset($mongodb[$id])){
        $data_event =  $mongodb[$id]['data'];
        if(is_null($id_data)){
            if($type=='all'){
                $data_event = array();
            }else if($type == 'time'){
                foreach($data_event as $key=>$value){
                    if($value['end'] < date("Y-m-d")){
                        unset($data_event[$key]);
                        break;
                    }
                }

            }
        }else{
            foreach($data_event as $key=>$value){
                if($value['id'] == $id_data){
                    unset($data_event[$key]);
                    break;
                }
            }
        }
        $data_event = (count($data_event))?$data_event:array();
        $mongodb[$id]['data'] = array_values($data_event);
    }
}
function get_default_layout(){
     return array(
        array(
            'full1'=>array(
                W=>array(
                    'announcement'
                )
            )
        ),
        array(
            'full2'=>array(
                W=>array(
                    'comment/count',
                    'users/count',
                    'posts/count',
                    'pages/count'
                )
            )
        ),
        array(
            'full3'=>array(
                P=>'full',
                W=>array('comment/approve','posts/approve')
            )
        ),
        array(
            'left'=>array(
                P=>'left',
                W=>'',
            ),
            'right'=>array(
                P=>'right',
                W=>''
            )
        ),
        array(
            'full4'=>array(
                P=>'content',
                W=>array(
                    'calendar'
                )
            )
        )
    );
}
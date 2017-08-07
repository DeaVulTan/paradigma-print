<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<!-- index -->
<?php
$search_btn = '<div class="btn-group">';
$search_btn .= form_button ( "<i class='fa fa-search'></i> " . __ ( 'Search', 'plugin' ), array (
        'onclick' => 'plugin_Search_modal()',
        'style' => 'display:none',
        'class' => 'btn btn-default btn-index' 
) );
$src_param = 'admin-page=' . $this->controller->get->data ( 'admin-page' );
if (isset ( $this->controller->get->sub_page )) {
    $src_param .= 'sub_page=' . $this->controller->get->sub_page;
}
$search_btn .= form_button ( "<i class='fa fa-times'></i>", array (
        'onclick' => 'cancel_plugin_Search()',
        'style' => 'display:none',
        'class' => 'btn btn-default btn-index' 
) );
$search_btn .= '</div>';
add_toolbar_button ( $search_btn );
?>

<div class="row" style="margin-bottom: 20px; margin-top: -15px;">
    <div class="col-sm-12">
        <h3>
            <?php if (!empty($this->menu_settings[$this->controller->get->{"admin-page"}])){ ?>
                <?php echo '<i class="' . $this->menu_settings[$this->controller->get->{"admin-page"}]['icon'] . '" style="color:'.$this->menu_settings[$this->controller->get->{"admin-page"}]['icon_color'].'"></i>'; ?>
            <?php }else{ ?>
                <?php global $_admin_menu; ?>
                <?php echo (!empty($_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'])) ? '<i class="' . $_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'] . '"></i>' : ''; ?>
            <?php } ?>
            <?php echo __('Plugins','plugin'); ?>
        </h3>
    </div>
</div>

<div class="row">
    <div class="col-md-8">
        <div class="btn-group" id="bulk-action-container"
            style="display: none;">
            <button type="button"
                class="btn btn-default dropdown-toggle"
                data-toggle="dropdown">
                <?php echo __('Bulk Actions','plugin'); ?> <span
                    class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#" onclick="bulk_action('activate'); return false;"><?php echo __('Activate','plugin'); ?></a></li>
                <li><a href="#" onclick="bulk_action('deactivate'); return false;"><?php echo __('Deactivate','plugin'); ?></a></li>
            </ul>
        </div>
    </div>
    <div class="col-md-4" style="height: 34px;">&nbsp;</div>
</div>
<div class="row" style="padding: 10px 0px;">
    <div class="col-md-6">
        <div class="btn-group">
            <button type="button"
                class="btn btn-default btn-sm btn-flat dropdown-toggle"
                data-toggle="dropdown">
                <span id="view-title"><?php echo __('All', 'plugin'); ?></span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a
                    href="<?php echo admin_url("admin-page=plugin", false); ?>"> <?php echo __('All', 'plugin'); ?></a></li>
                <li><a href="javascript:view_allstatus('activate');"> <?php echo __('Activate', 'plugin'); ?></a></li>
                <li><a
                    href="javascript:view_allstatus('deactivate');"> <?php echo __('Deactivate', 'plugin'); ?></a></li>
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="pull-right">
            <?php echo $this->pagination->short_page_link(); ?>
        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-12">
        <div class="table-responsive">
            <form method="post" role="form"
                action="<?php echo admin_url('&action=bulk_action'); ?>"
                id="frm-bulk-action">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                                <th class="text-center" style="width: 30px; background: #3c8dbc; color: white;">#</th>
                                <th class="text-center" style="width: 30px; background: #3c8dbc; color: white;"><input type="checkbox" id="check-all"></th>
                                <th style="background: #3c8dbc; color: white;width: 250px;">
                                    <?php
                                    $title_icon = "";
                                    if (! empty ( $this->controller->get->order_field ) && $this->controller->get->order_field == "name") {
                                        if (! empty ( $this->controller->get->order_type ) && strtolower ( $this->controller->get->order_type ) == "desc") {
                                            $title_icon = "fa-sort-desc";
                                        } else {
                                            $title_icon = "fa-sort-asc";
                                        }
                                    }
                                    
                                    ?>
                                    <div style="cursor: pointer; position: relative;" id="order_title" onclick="plugin_Oder(this,'name');">
                                        <?php echo __("Plugins","plugin"); ?> 
                                        <i class="fa fa-sort" style="position: absolute; top: 4px; right: 0; color: #7fb1ce;"></i>
                                        <i class="fa order-icon <?php echo $title_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($title_icon == ""){ ?> display:none;<?php } ?>"></i>
                                    </div>
                                </th>
                                <th style="background: #3c8dbc; color: white;width:100px;"><?php echo __('Version','plugin'); ?></th>
                                <th style="background: #3c8dbc; color: white;width:858px"><?php echo __('Description','plugin'); ?></th>
                                <th style="background: #3c8dbc; color: white;width:88px;"><?php echo __("Actions","plugin"); ?> </th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php if (!empty($this->records) && is_array($this->records)){?>
                    <?php
                        $stt =1;
                        foreach ( $this->records as $index => $record ) {
                            $token = Pf_Plugin_CSRF::token ( $this->key . $index );
                            ?>
                            <tr>
                                <td class="text-center"><?php echo $stt; ?></td>
                                <td class="text-center"><input type="checkbox" name="id[]" value="<?php echo $index;?>">
                                    <input type="hidden" name="<?php echo 'token[' . $index . ']' ?>" value="<?php echo $token; ?>">
                                </td>                                                        
                                <td><?php echo $record['name'];  ?></td>
                                <td><?php echo $record['version']; ?></td>
                                <td><?php echo $record['description']; ?></td>
                                <td>
                                    <?php if (!in_array($index, $this->actived_plugins)){?>
                        			<span id="actions_<?php echo $stt;?>"><a class="btn btn-primary btn-xs" href="javascript:change_actions('<?php echo $index;?>','activate',<?php echo $stt;?>);"><?php echo __('Activate', 'plugin'); ?></a></span>
                        			<?php }else{ ?>
                        			<span id="actions_<?php echo $stt;?>"><a class="btn btn-danger btn-xs" href="javascript:change_actions('<?php echo $index;?>','deactivate',<?php echo $stt;?>);"><?php echo __('Deactivate', 'plugin'); ?></a></span>
                        			<?php }?>
                                </td>
                            </tr>
                    <?php $stt++;}?>
                    <?php }else{ ?>
                            <tr>
                                <td id="data_empty">Empty!</td>
                            </tr>
                            <script>
                                $(document).ready(function(){
                                    $('#data_empty').attr('colspan',$('#frm-bulk-action  th').length).css({'text-align':'center'});
                                });
                            </script>
                    <?php } ?>
                </tbody>
                    </table>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="modal fade" id="search_modal" tabindex="-1" role="dialog"
    aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span
                        class="sr-only"><?php echo __('Close','plugin'); ?></span>
                </button>
                <h4 class="modal-title"><?php echo __('Plugins','plugin'); ?> <?php echo __('Search','plugin'); ?> </h4>
            </div>
            <form name="frm_plugin_Search"
                id="frm_plugin_Search" class="form-horizontal"
                role="form" onsubmit="return false;">
            <?php echo form_hidden('admin-page'); ?>
            <?php if (isset($this->controller->get->sub_page)){ echo form_hidden('sub_page'); } ?>
            <div class="modal-body">
                    <div class="form-group">
                        <label for="subject"
                            class="col-sm-2 control-label">
                            <?php echo __("Plugins","plugin"); ?>
                        </label>
                                <div class="col-sm-10">
                            <?php echo form_input("search[name]",(isset($this->controller->get->search["name"]))?$this->controller->get->search["name"]:""); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject" class="col-sm-2 control-label">
                            <?php echo __("Actions","plugin"); ?>
                        </label>
                        <div class="col-sm-10">
                            <div class="row"><?php foreach($this->controller->plugin_model->elements_value["action"] as $k => $v){ ?>    <label class="checkbox-inline"><?php echo form_checkbox("search[action][]",$k,(isset($this->controller->get->search["action"]) && in_array($k,$this->controller->get->search["action"]))?true:false);?> <?php echo __($v,'plugin'); ?></label><?php }?></div>
                        </div>
                    </div>
                <?php echo Pf::event()->trigger("filter","plugin-search-form"); ?>
            </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="plugin_Search();">
                    <i class='fa fa-search'></i> <?php echo __('Search','plugin'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close','plugin'); ?></button>
            </div>

        </div>
    </div>
</div>

<div class="row">
    <div class="col-sm-6">
        <?php echo $this->pagination->page_lable(); ?>
    </div>
    <div class="col-sm-6">
        <div class="pull-right">
            <?php echo $this->pagination->page_links(admin_url($this->page.'=') . '&'); ?>
        </div>
    </div>
</div>
<script>
$(document).ready(function(){
	$('.btn-index').css({display:'inline-block'});
	$('.btn-edit').css({display:'none'});
	$('.btn-copy').css({display:'none'});
	$('.btn-add').css({display:'none'});
	
	try{
       tinymce.remove();
    }catch(e){}
	
	$('#check-all').on('ifChecked', function(event){
		$('input[name="id\[\]"]').iCheck('check');
		$('#bulk-action-container').show();
	});

	$('#check-all').on('ifUnchecked', function(event){
		$('input[name="id\[\]"]').iCheck('uncheck');
		$('#bulk-action-container').hide();
	});

	$('input[name="id\[\]"]').on('ifToggled', function(event){
	    if ($('#main-content input[name="id\[\]"]:checked').length > 0){
	    	$('#bulk-action-container').show();
	    }else{
	    	$('#bulk-action-container').hide();
	    	$('#check-all').iCheck('uncheck');
	    }
	});
	
	$('.pagination a').click( function(event) {
		$("#main-content").mask("<?php echo __('Loading...','plugin'); ?>");
		$('#main-content').load($(this).attr('href'),function(){
			$("#main-content").unmask();
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		});
		event.preventDefault();
	});

	$('#pagingControl select').attr('onchange','return false;');
	
	$('#pagingControl select').change(function(event){
		$("#main-content").mask("<?php echo __('Loading...','plugin'); ?>");
		$('#main-content').load($(this).data('url')+$(this).val(),function(){
			$("#main-content").unmask();
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
			$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		});
	});
});


function bulk_action(type){
	if (type != undefined){
		$.sModal({
	    	image:'<?php echo RELATIVE_PATH; ?>/app/plugins/default/theme/admin/images/confirm.png',
	    	content:'<?php echo __('Are you sure with this action?','plugin'); ?>',
	    	animate:'fadeDown',
	    	buttons:[
	                 {
                         text:'<i class="fa fa-times-circle"></i> OK',
                         addClass:'btn-danger',
                         click:function(m_id,data){
                        	    $("#main-content").mask("<?php echo __('Loading...','plugin'); ?>");
                        	    $.post($('#frm-bulk-action').attr('action')+'&type='+type,$('#frm-bulk-action').serialize(),function(obj){
    	              			    $('#main-content').load(obj.url,function(){
    	              				$("#main-content").unmask();
    	              				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
    	              				$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
    	              				   $('#sidebar-menu').load('<?php echo admin_url($this->action.'=load');?>',function(){
    	              					    $(".sidebar .treeview").tree();
    	              				   });
    	              				 if (obj.error == 0){
    	              				   switch(obj.action){
    	              					    case 'activate':
        	              				        $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Plugins is activated successfully','plugin'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
        	              				    break;
        	              				    case 'deactivate':
        	              				        $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Plugins is deactivated successfully','plugin'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
    										break;
    	              				   }
    	              				}else{
                  				        $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','plugin'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
                  				   }
    	              				});
    	              			  $.sModal('close',m_id);
                  			    },'json');
	                     }
	                 },
	                 {
	                     text:'Cancel',
	                     click:function(id,data){
	                         $.sModal('close',id);
	                     }
	                 },
	             ]
	    });
	}
}
function plugin_Search_modal(){
    $('#search_modal').modal('show');
}
function plugin_Search(){
	$("#main-content").mask("<?php echo __('Loading...','plugin'); ?>");
	$('#search_modal').modal('hide');
	$('#search_modal').on('hidden.bs.modal', function (e) {
		$.get('<?php echo admin_url($src_param,false); ?>',$('#frm_plugin_Search').serialize(),function(html){
	 		$('#main-content').html(html);
	 		$("#main-content").unmask();
	 		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
			$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	 	},'html');
	});
}

function cancel_plugin_Search(){
	$("#main-content").mask("<?php echo __('Loading...','plugin'); ?>");
	$('#main-content').load('<?php echo admin_url($src_param,false); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}
//ober Plugins
function plugin_Oder(obj,field){
    $("#main-content").mask("<?php echo __('Loading...','plugin'); ?>");
    var order_type = '';
    if ($(obj).find('.fa-sort-asc').length <= 0){
        order_type = 'asc';  
    }else{
        order_type = 'desc';
    }
    
    $('#main-content').load('<?php echo admin_url('order_field=&order_type='); ?>&order_field=' + field +'&order_type=' + order_type ,function(){
        $("#main-content").unmask();
    });
}

//Change actions
function change_actions(id,status,stt) {
	$.sModal({
    	image:'<?php echo RELATIVE_PATH; ?>/app/plugins/default/theme/admin/images/confirm.png',
    	content:'<?php echo __('Are you sure with this action?','plugin'); ?>',
    	animate:'fadeDown',
    	buttons:[
                 {
                     text:'<i class="fa fa-times-circle"></i> <?php echo "OK"; ?>',
                     addClass:'btn-danger',
                     click:function(m_id,data){
                    	 $("#main-content").mask("<?php echo __('Loading...','plugin'); ?>");
                    	 $.post("<?php echo admin_url($this->action.'=change_actions');?>", {action: "update-status",status: status,id: id},function (data) {
                    		 $("#main-content").unmask();
                    	        var result = "";
                    	        if (status == "deactivate") {
                    	            result = "<a class='btn btn-primary btn-xs' href=\"javascript:change_actions('" + id + "','activate',"+stt+")\"><?php echo __("Activate","plugin"); ?></a>";
                    	            $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Plugins is deactivated successfully','post'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
                    	        } else {
                    	        	result = "<a class='btn btn-danger btn-xs' href=\"javascript:change_actions('" + id + "','deactivate',"+stt+")\"><?php echo __("Deactivate","plugin"); ?></a>";
                    	        	$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Plugins is activated successfully','post'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
                    	        }
                    	        //load slidebar menu
                    	        $('#sidebar-menu').load('<?php echo admin_url($this->action.'=load');?>',function(){
                    					$(".sidebar .treeview").tree();
                    				});
                    	        $('#actions_' + stt).html(result);
                    		 $.sModal('close',m_id);
                    	 });
                     }
                 },
                 {
                     text:'Cancel',
                     click:function(id,data){
                         $.sModal('close',id);
                     }
                 },
             ]
    });
}
//View all action
function view_allstatus(status) {
    $("#main-content").mask("<?php echo __('Loading...','plugin'); ?>");
    $.post("<?php echo admin_url($src_param,false); ?>", {action: "view-status",status: status},
    	function (html) {
    	$("#main-content").unmask();
    	$('#main-content').html(html);
    	if(status == 'activate'){
    		$('#view-title').html('<?php echo __('Activate','plugin');?>');
    	}else if(status == 'deactivate'){
    		$('#view-title').html('<?php echo __('Deactivate','plugin');?>');
    	}
    	$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
    });
}
</script>

<style type="text/css">
table.table.table-bordered tr td {
    vertical-align: middle;
}

span#view-title {
    text-transform: capitalize;
}
</style>
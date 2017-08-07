<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<!-- index -->
<?php
$search_btn = '<div class="btn-group">';
$search_btn .= form_button ( "<i class='fa fa-search'></i> " . __ ( 'Search', 'menu' ), array (
        'onclick' => 'Menu_Search_modal()',
        'style' => 'display:none',
        'class' => 'btn btn-default btn-index' 
) );
$src_param = 'admin-page=' . $this->controller->get->data ( 'admin-page' );
if (isset ( $this->controller->get->sub_page )) {
    $src_param .= 'sub_page=' . $this->controller->get->sub_page;
}
$search_btn .= form_button ( "<i class='fa fa-times'></i>", array (
        'onclick' => 'cancel_Menu_Search()',
        'style' => 'display:none',
        'class' => 'btn btn-default btn-index' 
) );
$search_btn .= '</div>';
add_toolbar_button ( $search_btn );
?>
<?php add_toolbar_button(form_button("<i class='fa fa-plus'></i> ".__('New','menu'), array('onclick' => 'Menu_Add();','style' => 'display:none', 'class' => 'btn btn-primary btn-index'))); ?>
<!-- /index -->

<!-- add -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','menu'), array('onclick' => 'Menu_New();','style' => 'display:none', 'class' => 'btn btn-primary btn-add'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','menu'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-add'))); ?>
<!-- /add -->

<!-- edit -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','menu'), array('onclick' => 'Menu_Edit();','style' => 'display:none', 'class' => 'btn btn-primary btn-edit'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Apply','menu'), array('onclick' => 'Menu_Apply();','style' => 'display:none', 'class' => 'btn btn-info btn-edit'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','menu'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-edit'))); ?>
<!-- /edit -->

<!-- copy -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','menu'), array('onclick' => 'Menu_Copy();','style' => 'display:none', 'class' => 'btn btn-primary btn-copy'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','menu'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-copy'))); ?>
<!-- /copy -->

<div class="row" style="margin-bottom: 20px; margin-top: -15px;">
    <div class="col-sm-12">
        <h3>
            <?php if (!empty($this->menu_settings[$this->controller->get->{"admin-page"}])){ ?>
                <?php echo '<i class="' . $this->menu_settings[$this->controller->get->{"admin-page"}]['icon'] . '" style="color:'.$this->menu_settings[$this->controller->get->{"admin-page"}]['icon_color'].'"></i>'; ?>
            <?php }else{ ?>
                <?php global $_admin_menu; ?>
                <?php echo (!empty($_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'])) ? '<i class="' . $_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'] . '"></i>' : ''; ?>
            <?php } ?>
            <?php echo __('Menu','menu'); ?>  <small><?php echo __('List','menu'); ?></small>
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
                <?php echo __('Bulk Actions','menu'); ?> <span
                    class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#" onclick="bulk_action('delete'); return false;"><?php echo __('Delete','menu'); ?></a></li>
            </ul>
        </div>
    </div>
    <div class="col-md-4" style="height: 34px;">&nbsp;</div>
</div>
<div class="row" style="padding: 10px 0px;height:30px">
    <div class="col-md-6">
        &nbsp;
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
            <form method="post" role="form" action="<?php echo admin_url('&action=bulk_action'); ?>" id="frm-bulk-action">
                <div class="table-responsive">
                    <table class="table table-bordered table-hover">
                        <thead>
                            <tr>
                            <th class="text-right" style="width: 30px; background: #3c8dbc; color: white;">#</th>
                            <th class="text-right" style="width: 30px; background: #3c8dbc; color: white;"><input type="checkbox" id="check-all"></th>
                            <th style="background: #3c8dbc; color: white;">
                        <?php
                        $menu_title_icon = "";
                        if (! empty ( $this->controller->get->order_field ) && $this->controller->get->order_field == "menu_title") {
                            if (! empty ( $this->controller->get->order_type ) && strtolower ( $this->controller->get->order_type ) == "desc") {
                                $menu_title_icon = "fa-sort-desc";
                            } else {
                                $menu_title_icon = "fa-sort-asc";
                            }
                        }
                        
                        ?>
                        <div style="cursor: pointer; position: relative;" id="order_menu_title" onclick="Menu_Oder(this,'menu_title');">
                            <?php echo __("Menu Name","menu"); ?> 
                            <i class="fa fa-sort" style="position: absolute; top: 4px; right: 0; color: #7fb1ce;"></i>
                            <i class="fa order-icon <?php echo $menu_title_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($menu_title_icon == ""){ ?> display:none;<?php } ?>"></i>
                        </div>
                        </th>
                        <th style="background: #3c8dbc; color: white;width:150px;">
                            <div style="position: relative;width:350px">
                                <?php echo __("Shortcode","menu"); ?> 
                            </div>
                        </th>
                        <th class="text-center" style="width: 100px; background: #3c8dbc; color: white;"><?php echo __('Actions','menu'); ?></th>
                    </tr>
                        </thead>
                            <tbody>
                            <?php if (!empty($this->records) && is_array($this->records)){?>
                            <?php
                                
                                foreach ( $this->records as $index => $record ) {
                                    $token = Pf_Plugin_CSRF::token ( $this->key . $record ['id'] );
                                    ?>
                                    <tr>
                                        <td class="text-right"><?php echo (($this->page_index) * NUM_PER_PAGE + $index +1); ?></td>
                                        <td><input type="checkbox" name="id[]" value="<?php echo $record['id'];?>">
                                            <input type="hidden" name="<?php echo 'token[' . $record['id'] . ']' ?>" value="<?php echo $token; ?>"></td>
                                        <td><?php echo $record["name"]; ?></td>
                                        <td>{menu:display id=<?php echo $record['id']; ?> }</td>
                                        <td class="text-center">
                                            <button type="button" onclick="Menu_Edit('<?php echo $record['id'];?>','<?php echo $token; ?>');" class="btn btn-info btn-xs">
                                                <i class="fa fa-pencil"></i>
                                            </button>
                                            <button type="button" onclick="Menu_Delete('<?php echo $record['id'];?>','<?php echo $token; ?>');" class="btn btn-danger btn-xs">
                                                <i class="fa fa-minus-circle"></i>
                                            </button>
                                        </td>
                                    </tr>
                            <?php }?>
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
                        class="sr-only"><?php echo __('Close','menu'); ?></span>
                </button>
                <h4 class="modal-title"><?php echo __('Menu','menu'); ?> <?php echo __('Search','menu'); ?> </h4>
            </div>
            <form name="frm_Menu_Search" id="frm_Menu_Search"
                class="form-horizontal" role="form"
                onsubmit="return false;">
            <?php echo form_hidden('admin-page'); ?>
            <?php if (isset($this->controller->get->sub_page)){ echo form_hidden('sub_page'); } ?>
            <div class="modal-body">
                <div class="form-group">
                    <label for="subject" class="col-sm-2 control-label">
                        <?php echo __("Menu Title","menu"); ?>
                    </label>
                    <div class="col-sm-10">
                        <?php echo form_input("search[menu_title]",(isset($this->controller->get->search["menu_title"]))?$this->controller->get->search["menu_title"]:""); ?>
                    </div>
                </div>
                <?php echo Pf::event()->trigger("filter","menu-search-form"); ?>
            </div>

            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary"
                    onclick="Menu_Search();">
                    <i class='fa fa-search'></i> <?php echo __('Search','menu'); ?></button>
                <button type="button" class="btn btn-default"
                    data-dismiss="modal"><?php echo __('Close','menu'); ?></button>
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
		$("#main-content").mask("<?php echo __('Loading...','menu'); ?>");
		$('#main-content').load($(this).attr('href'),function(){
			$("#main-content").unmask();
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		});
		event.preventDefault();
	});

	$('#pagingControl select').attr('onchange','return false;');
	
	$('#pagingControl select').change(function(event){
		$("#main-content").mask("<?php echo __('Loading...','menu'); ?>");
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
	    	content:'<?php echo __('Are you sure with this action?','menu'); ?>',
	    	animate:'fadeDown',
	    	buttons:[
	                 {
	                     text:'<i class="fa fa-times-circle"></i> <?php echo __('OK','menu'); ?>',
	                     addClass:'btn-danger',
	                     click:function(m_id,data){
	                            $("#main-content").mask("<?php echo __('Loading...','menu'); ?>");
	                                $.post($('#frm-bulk-action').attr('action')+'&type='+type,$('#frm-bulk-action').serialize(),function(obj){
	                                   $('#main-content').load(obj.url,function(){
	                                   $('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		                               $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		                               $("#main-content").unmask();
		                               if (obj.error == 0){
		                               $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Menu is deleted successfully','menu'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		                            }else{
		                               $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','menu'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
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

function Menu_Search_modal(){
    $('#search_modal').modal('show');
}

function Menu_Search(){
	$("#main-content").mask("<?php echo __('Loading...','menu'); ?>");
	$('#search_modal').modal('hide');
	$('#search_modal').on('hidden.bs.modal', function (e) {
		$.get('<?php echo admin_url($src_param,false); ?>',$('#frm_Menu_Search').serialize(),function(html){
	 		$('#main-content').html(html);
	 		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	 		$("#main-content").unmask();
	 	},'html');
	});
}

function cancel_Menu_Search(){
	$("#main-content").mask("<?php echo __('Loading...','menu'); ?>");
	$('#main-content').load('<?php echo admin_url($src_param,false); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}

function Menu_Add(){
	$("#main-content").mask("<?php echo __('Loading...','menu'); ?>");
	$('#main-content').load('<?php echo admin_url($this->action.'=add'); ?>',function(){
		$("#main-content").unmask();
	});
}

function Menu_Edit(id,token){
	$("#main-content").mask("<?php echo __('Loading...','menu'); ?>");
	$.get('<?php echo admin_url($this->action.'=edit');?>&id='+id+'&token='+token,function(obj){
		$("#main-content").unmask();
		if (obj.error == 0){
			$('#main-content').html(obj.content);
		}else if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','menu'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url);
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}

function Menu_Delete(id,token){
	$.sModal({
    	image:'<?php echo RELATIVE_PATH; ?>/app/plugins/default/theme/admin/images/confirm.png',
    	content:'<?php echo __('Are you sure to delete this item?','menu'); ?>',
    	animate:'fadeDown',
    	buttons:[
                 {
                     text:'<i class="fa fa-times-circle"></i> <?php echo __('Delete','menu'); ?>',
                     addClass:'btn-danger',
                     click:function(m_id,data){
                    	 $("#main-content").mask("<?php echo __('Loading...','menu'); ?>");
                    	 $.get('<?php echo admin_url($this->action.'=delete');?>&id='+id+'&token='+token,function(obj){
                    	     if (obj.error == 0){
                    		    $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Menu is deleted successfully','menu'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
                    		 }else{
                    		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','menu'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
                    		 }
                    		 $('#main-content').load(obj.url,function(){
                    			 $("#main-content").unmask();
                    			 $('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		                         $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
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

function Menu_Oder(obj,field){
    $("#main-content").mask("<?php echo __('Loading...','menu'); ?>");
    var order_type = '';
    if ($(obj).find('.fa-sort-asc').length <= 0){
        order_type = 'asc';  
    }else{
        order_type = 'desc';
    }
    
    $('#main-content').load('<?php echo admin_url('order_field=&order_type='); ?>&order_field=' + field +'&order_type=' + order_type ,function(){
        $("#main-content").unmask();
        $('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
    });
}

</script>

<style type="text/css">
    table.table.table-bordered tr td {vertical-align: middle;}
    span#view-title {text-transform: capitalize;}
</style>
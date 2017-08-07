<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<!-- index -->
<?php 
    $search_btn = '<div class="btn-group">';
    $search_btn .= form_button("<i class='fa fa-search'></i> ".__('Search','galleries'), array('onclick' => 'Galleries_Search_modal()','style' => 'display:none', 'class' => 'btn btn-default btn-index'));
    $src_param = 'admin-page='.$this->controller->get->data('admin-page');
    if (isset($this->controller->get->sub_page)){ $src_param .= 'sub_page='.$this->controller->get->sub_page; }
    $search_btn .= form_button("<i class='fa fa-times'></i>", array('onclick' => 'cancel_Galleries_Search()','style' => 'display:none', 'class' => 'btn btn-default btn-index'));
    $search_btn .= '</div>';
    add_toolbar_button($search_btn); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-plus'></i> ".__('New','galleries'), array('onclick' => 'Galleries_Add();','style' => 'display:none', 'class' => 'btn btn-primary btn-index'))); ?>
<!-- /index -->

<!-- add -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','galleries'), array('onclick' => 'Galleries_New();','style' => 'display:none', 'class' => 'btn btn-primary btn-add'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','galleries'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-add'))); ?>
<!-- /add -->

<!-- edit -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','galleries'), array('onclick' => 'Galleries_Edit();','style' => 'display:none', 'class' => 'btn btn-primary btn-edit'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Apply','galleries'), array('onclick' => 'Galleries_Apply();','style' => 'display:none', 'class' => 'btn btn-info btn-edit'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','galleries'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-edit'))); ?>
<!-- /edit -->

<!-- copy -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','galleries'), array('onclick' => 'Galleries_Copy();','style' => 'display:none', 'class' => 'btn btn-primary btn-copy'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','galleries'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-copy'))); ?>
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
            <?php echo __('Galleries','galleries'); ?> <small><?php echo __('List','galleries'); ?></small>
        </h3>
    </div>
</div>

<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-6">
        <div class="btn-group" id="bulk-action-container"
            style="display: none;">
            <button type="button"
                class="btn btn-default dropdown-toggle"
                data-toggle="dropdown">
                <?php echo __('Bulk Actions','galleries'); ?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#" onclick="bulk_action('delete'); return false;"><?php echo __('Delete','galleries'); ?></a></li>
                <li><a href="#" onclick="bulk_action('publish'); return false;"><?php echo __('Publish','galleries'); ?></a></li>
                <li><a href="#" onclick="bulk_action('unpublish'); return false;"><?php echo __('Unpublish','galleries'); ?></a></li>    
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
                <span id="view-title"><?php echo __('All', 'galleries'); ?></span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="<?php echo admin_url("admin-page=galleries", false); ?>"> <?php echo __('All', 'galleries'); ?></a></li>
                <li><a href="javascript:view_allstatus(1,'published');"> <?php echo __('Published', 'galleries'); ?></a></li>
                <li><a href="javascript:view_allstatus(2,'unpublished');"> <?php echo __('Unpublished', 'galleries'); ?></a></li>
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
            <form method="post" role="form" action="<?php echo admin_url('&action=bulk_action'); ?>" id="frm-bulk-action">
                <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-right" style="width: 30px; background:#3c8dbc; color:white;">#</th>
                            <th class="text-right" style="width: 30px; background:#3c8dbc; color:white;"><input type="checkbox" id="check-all"></th>
                            
            <th style="background:#3c8dbc; color:white;">
                <?php
                  $gallery_name_icon = "";
                  if (!empty($this->controller->get->order_field) && $this->controller->get->order_field == "gallery_name"){
                      if (!empty($this->controller->get->order_type) && strtolower($this->controller->get->order_type) == "desc"){
                          $gallery_name_icon = "fa-sort-desc";
                      }else{
                          $gallery_name_icon = "fa-sort-asc";
                      }
                  }
        
                ?>
                <div style="cursor:pointer; position: relative;" id="order_gallery_name" onclick="Galleries_Oder(this,'gallery_name');">
                    <?php echo __("Gallery name","galleries"); ?> 
                    <i class="fa fa-sort" style="position:absolute; top:4px; right:0; color:#7fb1ce;"></i>
                    <i class="fa order-icon <?php echo $gallery_name_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($gallery_name_icon == ""){ ?> display:none;<?php } ?>"></i>
                </div>
            </th>
            <th style="background: #3c8dbc; color: white; width:508px">Shortcode</th>
            <th style="background:#3c8dbc; color:white; width:101px">
                <?php
                  $gallery_status_icon = "";
                  if (!empty($this->controller->get->order_field) && $this->controller->get->order_field == "gallery_status"){
                      if (!empty($this->controller->get->order_type) && strtolower($this->controller->get->order_type) == "desc"){
                          $gallery_status_icon = "fa-sort-desc";
                      }else{
                          $gallery_status_icon = "fa-sort-asc";
                      }
                  }
        
                ?>
                <div style="cursor:pointer; position: relative;" id="order_gallery_status" onclick="Galleries_Oder(this,'gallery_status');">
                    <?php echo __("Status","galleries"); ?> 
                    <i class="fa fa-sort" style="position:absolute; top:4px; right:0; color:#7fb1ce;"></i>
                    <i class="fa order-icon <?php echo $gallery_status_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($gallery_status_icon == ""){ ?> display:none;<?php } ?>"></i>
                </div>
            </th>
                            <th class="text-center" style="width: 100px; background:#3c8dbc; color:white;"><?php echo __('Actions','galleries'); ?></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php if (!empty($this->records) && is_array($this->records)){?>
                    <?php foreach ($this->records as $index => $record) {
                        $token = Pf_Plugin_CSRF::token($this->key.$record['id']);
                    ?>
                    <tr>
                            <td class="text-right"><?php echo (($this->page_index - 1) * NUM_PER_PAGE + $index +1); ?></td>
                            <td><input type="checkbox" name="id[]"
                                value="<?php echo (int)$record['id'];?>">
                                <input type="hidden"
                                name="<?php echo 'token[' . (int)$record['id'] . ']' ?>"
                                value="<?php echo $token; ?>"></td>
                                
                            
				            <td><?php echo $record["gallery_name"]; ?></td>
				            <td style="width: 188px;"><?php echo "{galleries:display id=".$record["id"]."}"; ?></td>
				            <td>
				            	<?php if($record['gallery_status'] == 1){ ?>
                                        <span id="status_<?php echo $record['id'];?>">
                                        	<a href="javascript:change_status(<?php echo $record['id'];?>,'unpublish');" class="label label-success"><?php echo __("Published","galleries"); ?></a>
                                        </span>
                                    <?php }else{?>
                                        <span id="status_<?php echo $record['id'];?>">
                                        	<a href="javascript:change_status(<?php echo $record['id'];?>,'publish')"class="label label-danger"><?php echo __("Unpublished","galleries"); ?></a>
                                        </span>
                                    <?php }?>
				            </td>
                            
                            <td class="text-center">
                                <button type="button"
                                    onclick="Galleries_Copy('<?php echo (int)$record['id'];?>','<?php echo $token; ?>');"
                                    class="btn btn-primary btn-xs">
                                    <i class="fa fa-files-o"></i>
                                </button>
                                <button type="button"
                                    onclick="Galleries_Edit('<?php echo (int)$record['id'];?>','<?php echo $token; ?>');"
                                    class="btn btn-info btn-xs">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button type="button"
                                    onclick="Galleries_Delete('<?php echo (int)$record['id'];?>','<?php echo $token; ?>');"
                                    class="btn btn-danger btn-xs">
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

<div class="modal fade" id="search_modal" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">
                    <span aria-hidden="true">&times;</span><span class="sr-only"><?php echo __('Close','galleries'); ?></span>
                </button>
                <h4 class="modal-title"><?php echo __('Galleries','galleries'); ?> <?php echo __('Search','galleries'); ?></h4>
            </div>
            <form name="frm_Galleries_Search" id="frm_Galleries_Search" class="form-horizontal" role="form" onsubmit="return false;">
            <?php echo form_hidden('admin-page'); ?>
            <?php if (isset($this->controller->get->sub_page)){ echo form_hidden('sub_page'); } ?>
            <div class="modal-body">
                
            <div class="form-group">
                <label for="subject" class="col-sm-2 control-label">
                    <?php echo __("Gallery name","galleries"); ?>
                </label>
                <div class="col-sm-10">
                    <?php echo form_input("search[gallery_name]",(isset($this->controller->get->search["gallery_name"]))?$this->controller->get->search["gallery_name"]:""); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="subject" class="col-sm-2 control-label">
                    <?php echo __("Status","galleries"); ?>
                </label>
                <div class="col-sm-10">
                    <div class="row"><?php foreach($this->controller->galleries_model->elements_value["gallery_status"] as $k => $v){ ?>    <label class="checkbox-inline"><?php echo form_checkbox("search[gallery_status][]",$k,(isset($this->controller->get->search["gallery_status"]) && in_array($k,$this->controller->get->search["gallery_status"]))?true:false);?> <?php echo __($v,'galleries'); ?></label><?php }?></div>
                </div>
            </div>
                <?php echo Pf::event()->trigger("filter","gallery-search-form"); ?>
            </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="Galleries_Search();"> <i class='fa fa-search'></i> <?php echo __('Search','galleries'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close','galleries'); ?></button>
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
		$("#main-content").mask("<?php echo __('Loading...','galleries'); ?>");
		$('#main-content').load($(this).attr('href'),function(){
			$("#main-content").unmask();
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		});
		event.preventDefault();
	});

	$('#pagingControl select').attr('onchange','return false;');
	
	$('#pagingControl select').change(function(event){
		$("#main-content").mask("<?php echo __('Loading...','galleries'); ?>");
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
	    	content:'<?php echo __('Are you sure with this action?','galleries'); ?>',
	    	animate:'fadeDown',
	    	buttons:[
	                 {
	                     text:'<i class="fa fa-times-circle"></i> <?php echo __('OK','galleries'); ?>',
	                     addClass:'btn-danger',
	                     click:function(m_id,data){
	                    	    $("#main-content").mask("<?php echo __('Loading...','galleries'); ?>");
	                    	    $.post($('#frm-bulk-action').attr('action')+'&type='+type,$('#frm-bulk-action').serialize(),function(obj){
    	              			    $('#main-content').load(obj.url,function(){
    	              				   $('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		                               $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
    	              				   $("#main-content").unmask();
    	              				   if (obj.error == 0){
    	              				        $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Gallery is deleted successfully','galleries'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
    	              				   }else{
    	              				        $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','galleries'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
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

function Galleries_Search_modal(){
    $('#search_modal').modal('show');
}

function Galleries_Search(){
	$("#main-content").mask("<?php echo __('Loading...','galleries'); ?>");
	$('#search_modal').modal('hide');
	$('#search_modal').on('hidden.bs.modal', function (e) {
		$.get('<?php echo admin_url($src_param,false); ?>',$('#frm_Galleries_Search').serialize(),function(html){
	 		$('#main-content').html(html);
	 		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	 		$("#main-content").unmask();
	 	},'html');
	});
}

function cancel_Galleries_Search(){
	$("#main-content").mask("<?php echo __('Loading...','galleries'); ?>");
	$('#main-content').load('<?php echo admin_url($src_param,false); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}

function Galleries_Add(){
	$("#main-content").mask("<?php echo __('Loading...','galleries'); ?>");
	$('#main-content').load('<?php echo admin_url($this->action.'=add'); ?>',function(){
		$("#main-content").unmask();
	});
}

function Galleries_Copy(id,token){
	$("#main-content").mask("<?php echo __('Loading...','galleries'); ?>");
	$.get('<?php echo admin_url($this->action.'=copy');?>&id='+id+'&token='+token,function(obj){
		$("#main-content").unmask();
		if (obj.error == 0){
			$('#main-content').html(obj.content);
		}else if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','galleries'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url);
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}

function Galleries_Edit(id,token){
	$("#main-content").mask("<?php echo __('Loading...','galleries'); ?>");
	$.get('<?php echo admin_url($this->action.'=edit');?>&id='+id+'&token='+token,function(obj){
		$("#main-content").unmask();
		if (obj.error == 0){
			$('#main-content').html(obj.content);
		}else if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','galleries'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url);
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}

function Galleries_Delete(id,token){
	$.sModal({
    	image:'<?php echo RELATIVE_PATH; ?>/app/plugins/default/theme/admin/images/confirm.png',
    	content:'<?php echo __('Are you sure to delete this item?','galleries'); ?>',
    	animate:'fadeDown',
    	buttons:[
                 {
                     text:'<i class="fa fa-times-circle"></i> <?php echo __('OK','galleries'); ?>',
                     addClass:'btn-danger',
                     click:function(m_id,data){
                    	 $("#main-content").mask("<?php echo __('Loading...','galleries'); ?>");
                    	 $.get('<?php echo admin_url($this->action.'=delete');?>&id='+id+'&token='+token,function(obj){
                    	     if (obj.error == 0){
                    		    $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Gallery is deleted successfully','galleries'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
                    		 }else{
                    		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','galleries'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
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

function Galleries_Oder(obj,field){
    $("#main-content").mask("<?php echo __('Loading...','galleries'); ?>");
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

//Change status
function change_status(id,status) {
    $("#main-content").mask("<?php echo __('Loading...','galleries'); ?>");
    $.post("<?php echo admin_url($this->action.'=change_status');?>", {action: "update-status",status: status,id: id},
    	function (data) {
    	$("#main-content").unmask();
        var result = "";
        if (status == "publish") {
            result = "<a href=\"javascript:change_status(" + id + ",'unpublish')\" class=\"label label-success\"><?php echo __("Published","galleries"); ?></a>";
        } else {
        	result = "<a href=\"javascript:change_status(" + id + ",'publish')\" class=\"label label-danger\"><?php echo __("Unpublished","galleries"); ?></a>";
        }
        $('#status_' + id).html(result);

    });
}

//Change all status
function view_allstatus(id,status) {
    $("#main-content").mask("<?php echo __('Loading...','galleries'); ?>");
    $.post("<?php echo admin_url($src_param,false); ?>", {action: "view-status",status: status,id: id},
    	function (html) {
    	$("#main-content").unmask();
    	$('#main-content').html(html);
    	if(id == 1){
    		$('#view-title').html('<?php echo __('Published','galleries');?>');
    	}else if(id == 2){
    		$('#view-title').html('<?php echo __('Unpublished','galleries');?>');
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
</script>
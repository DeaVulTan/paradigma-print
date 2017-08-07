<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<!-- index -->
<?php
$search_btn = '<div class="btn-group">';
$search_btn .= form_button ( "<i class='fa fa-search'></i> " . __ ( 'Search', 'contactform' ), array (
        'onclick' => 'contactform_Search_modal()',
        'style' => 'display:none',
        'class' => 'btn btn-default btn-index' 
) );
$src_param = 'admin-page=' . $this->controller->get->data ( 'admin-page' );
if (isset ( $this->controller->get->sub_page )) {
    $src_param .= 'sub_page=' . $this->controller->get->sub_page;
}
$search_btn .= form_button ( "<i class='fa fa-times'></i>", array (
        'onclick' => 'cancel_contactform_Search()',
        'style' => 'display:none',
        'class' => 'btn btn-default btn-index' 
) );
$search_btn .= '</div>';
add_toolbar_button ( $search_btn );
?>
<?php add_toolbar_button(form_button("<i class='fa fa-plus'></i> ".__('New','contactform'), array('onclick' => 'contactform_Add();','style' => 'display:none', 'class' => 'btn btn-primary btn-index'))); ?>
<!-- /index -->

<!-- add -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','contactform'), array('onclick' => 'contactform_New();','style' => 'display:none', 'class' => 'btn btn-primary btn-add'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','contactform'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-add'))); ?>
<!-- /add -->

<!-- edit -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','contactform'), array('onclick' => 'contactform_Edit();','style' => 'display:none', 'class' => 'btn btn-primary btn-edit'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Apply','contactform'), array('onclick' => 'contactform_Apply();','style' => 'display:none', 'class' => 'btn btn-info btn-edit'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','contactform'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-edit'))); ?>
<!-- /edit -->

<!-- copy -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','contactform'), array('onclick' => 'contactform_Copy();','style' => 'display:none', 'class' => 'btn btn-primary btn-copy'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','contactform'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-copy'))); ?>
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
            <?php echo __('Contact Forms','contactform'); ?>  <small><?php echo __('List','contactform'); ?></small>
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
                <?php echo __('Bulk Actions','contactform'); ?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li>
                    <a href="#" onclick="bulk_action('delete'); return false;"><?php echo __('Delete','contactform'); ?></a>
                </li>
                <li>
                    <a href="#" onclick="bulk_action('publish'); return false;"><?php echo __('Publish','contactform'); ?></a>
                </li>
                <li>
                    <a href="#" onclick="bulk_action('unpublish'); return false;"><?php echo __('Unpublish','contactform'); ?></a>
                </li>
            </ul>
        </div>
    </div>
    <div class="col-md-6">
        <div class="input-group" style="width:216;height:34px">
            
        </div>
    </div>
</div>
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-6">
        <div class="btn-group" >
            <button type="button"
                class="btn btn-default dropdown-toggle"
                data-toggle="dropdown">
                <span id="view-title"><?php echo __('All','contactform'); ?></span>  
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li>
                    <a href="<?php echo admin_url("admin-page=contactform", false); ?>"><?php echo __('All','contactform'); ?></a>
                </li>
                <li>
                    <a href="javascript:view_allstatus(1,'publish');" ><?php echo __('Publish','contactform'); ?></a>
                </li>
                <li>
                    <a href="javascript:view_allstatus(0,'unpublish');" ><?php echo __('Unpublish','contactform'); ?></a>
                </li>
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
                                <th style="background: #3c8dbc; color: white;width: 559px;">
                                    <?php
                                    $title_icon = "";
                                    if (! empty ( $this->controller->get->order_field ) && $this->controller->get->order_field == "title") {
                                        if (! empty ( $this->controller->get->order_type ) && strtolower ( $this->controller->get->order_type ) == "desc") {
                                            $title_icon = "fa-sort-desc";
                                        } else {
                                            $title_icon = "fa-sort-asc";
                                        }
                                    }
                                    
                                    ?>
                                    <div style="cursor: pointer; position: relative;" id="order_title" onclick="contactform_Oder(this,'title');">
                                        <?php echo __("Title","contactform"); ?> 
                                        <i class="fa fa-sort" style="position: absolute; top: 4px; right: 0; color: #7fb1ce;"></i>
                                        <i class="fa order-icon <?php echo $title_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($title_icon == ""){ ?> display:none;<?php } ?>"></i>
                                    </div>
                                </th>
                                <th style="background: #3c8dbc; color: white;width:158px">Shortcode</th>
                                <th style="background: #3c8dbc; color: white;width:58px;">
                                    <?php
                                    $status_icon = "";
                                    if (! empty ( $this->controller->get->order_field ) && $this->controller->get->order_field == "status") {
                                        if (! empty ( $this->controller->get->order_type ) && strtolower ( $this->controller->get->order_type ) == "desc") {
                                            $status_icon = "fa-sort-desc";
                                        } else {
                                            $status_icon = "fa-sort-asc";
                                        }
                                    }
                                    
                                    ?>
                                    <div style="cursor: pointer; position: relative;" id="order_status" onclick="contactform_Oder(this,'status');">
                                        <?php echo __("Status","contactform"); ?> 
                                        <i class="fa fa-sort" style="position: absolute; top: 4px; right: 0; color: #7fb1ce;"></i>
                                        <i class="fa order-icon <?php echo $status_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($status_icon == ""){ ?> display:none;<?php } ?>"></i>
                                    </div>
                                </th>
                                <th class="text-center" style="width: 80px; background: #3c8dbc; color: white;"><?php echo __('Actions','contactform'); ?></th>
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
                                <td><?php echo $record["title"]; ?></td>
                                <td><?php echo "{contactform:display id=".$index."}"; ?></td>
                                <td>
                                    <?php if($record['status'] == 1){ ?>
                                        <span id="status_<?php echo $index;?>"><a href="javascript:change_status('<?php echo $index;?>','unpublish');" class="label label-success"><?php echo __("Published","contactform"); ?></a></span>
                                    <?php }else{?>
                                        <span id="status_<?php echo $index;?>"><a href="javascript:change_status('<?php echo $index;?>','publish');" class="label label-danger"><?php echo __("Unpublished","contactform"); ?></a></span>
                                    <?php }?>
                                </td>

                                <td class="text-center">
                                    <button type="button"
                                        onclick="contactform_Copy('<?php echo $index;?>','<?php echo $token; ?>');"
                                        class="btn btn-primary btn-xs">
                                        <i class="fa fa-files-o"></i>
                                    </button>
                                    <button type="button"
                                        onclick="contactform_Edit('<?php echo $index;?>','<?php echo $token; ?>');"
                                        class="btn btn-info btn-xs">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button type="button"
                                        onclick="contactform_Delete('<?php echo $index;?>','<?php echo $token; ?>');"
                                        class="btn btn-danger btn-xs">
                                        <i class="fa fa-minus-circle"></i>
                                    </button>
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
                        class="sr-only"><?php echo __('Close','contactform'); ?></span>
                </button>
                <h4 class="modal-title"><?php echo __('Contact Forms','contactform'); ?> <?php echo __('Search','contactform'); ?> </h4>
            </div>
            <form name="frm_contactform_Search"
                id="frm_contactform_Search" class="form-horizontal"
                role="form" onsubmit="return false;">
            <?php echo form_hidden('admin-page'); ?>
            <?php if (isset($this->controller->get->sub_page)){ echo form_hidden('sub_page'); } ?>
            <div class="modal-body">
                    <div class="form-group">
                        <label for="subject"
                            class="col-sm-2 control-label">
                            <?php echo __("Title","contactform"); ?>
                        </label>
                                <div class="col-sm-10">
                            <?php echo form_input("search[title]",(isset($this->controller->get->search["title"]))?$this->controller->get->search["title"]:""); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject" class="col-sm-2 control-label">
                            <?php echo __("Status","contactform"); ?>
                        </label>
                        <div class="col-sm-10">
                            <div class="row"><?php foreach($this->controller->contactform_model->elements_value["status"] as $k => $v){ ?>    <label class="checkbox-inline"><?php echo form_checkbox("search[status][]",$k,(isset($this->controller->get->search["status"]) && in_array($k,$this->controller->get->search["status"]))?true:false);?> <?php echo __($v,'contactform'); ?></label><?php }?></div>
                        </div>
                    </div>
                <?php echo Pf::event()->trigger("filter","contactform-search-form"); ?>
            </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="contactform_Search();">
                    <i class='fa fa-search'></i> <?php echo __('Search','contactform'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close','contactform'); ?></button>
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
		$("#main-content").mask("<?php echo __('Loading...','contactform'); ?>");
		$('#main-content').load($(this).attr('href'),function(){
			$("#main-content").unmask();
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		});
		event.preventDefault();
	});

	$('#pagingControl select').attr('onchange','return false;');
	
	$('#pagingControl select').change(function(event){
		$("#main-content").mask("<?php echo __('Loading...','contactform'); ?>");
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
	    	content:'<?php echo __('Are you sure with this action?','contactform'); ?>',
	    	animate:'fadeDown',
	    	buttons:[
	                 {
	                     text:'<i class="fa fa-times-circle"></i> OK',
	                     addClass:'btn-danger',
	                     click:function(m_id,data){
	                    	    $("#main-content").mask("<?php echo __('Loading...','contactform'); ?>");
	                    	    $.post($('#frm-bulk-action').attr('action')+'&type='+type,$('#frm-bulk-action').serialize(),function(obj){
    	              			       $('#main-content').load(obj.url,function(){
    	              				   $('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		                               $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
    	              				   $("#main-content").unmask();
    	              				 if (obj.error == 0){
    	              				   switch(obj.action){
    	              				        case 'delete':
    	    	              				    $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Contact Forms is deleted successfully','contactform'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
    	              					    break;
    	              					    case 'publish':
  	    	              				        $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Contact Forms is published successfully','contactform'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
  	    	              				    break;
  	    	              				    case 'unpublish':
  	    	              				        $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Contact Forms is unpublished successfully','contactform'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
  											break;
    	              				   }
    	              				}else{
  	              				        $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','contactform'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
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

function contactform_Search_modal(){
    $('#search_modal').modal('show');
}

function contactform_Search(){
	$("#main-content").mask("<?php echo __('Loading...','contactform'); ?>");
	$('#search_modal').modal('hide');
	$('#search_modal').on('hidden.bs.modal', function (e) {
		$.get('<?php echo admin_url($src_param,false); ?>',$('#frm_contactform_Search').serialize(),function(html){
	 		$('#main-content').html(html);
	 		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	 		$("#main-content").unmask();
	 	},'html');
	});
}

function cancel_contactform_Search(){
	$("#main-content").mask("<?php echo __('Loading...','contactform'); ?>");
	$('#main-content').load('<?php echo admin_url($src_param,false); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}

function contactform_Add(){
	$("#main-content").mask("<?php echo __('Loading...','contactform'); ?>");
	$('#main-content').load('<?php echo admin_url($this->action.'=add'); ?>',function(){
		$("#main-content").unmask();
	});
}

function contactform_Copy(id,token){
	$("#main-content").mask("<?php echo __('Loading...','contactform'); ?>");
	$.get('<?php echo admin_url($this->action.'=copy');?>&id='+id+'&token='+token,function(obj){
		$("#main-content").unmask();
		if (obj.error == 0){
			$('#main-content').html(obj.content);
		}else if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','contactform'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url);
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}

function contactform_Edit(id,token){
	$("#main-content").mask("<?php echo __('Loading...','contactform'); ?>");
	$.get('<?php echo admin_url($this->action.'=edit');?>&id='+id+'&token='+token,function(obj){
		$("#main-content").unmask();
		if (obj.error == 0){
			$('#main-content').html(obj.content);
		}else if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','contactform'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url);
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}

function contactform_Delete(id,token){
	$.sModal({
    	image:'<?php echo RELATIVE_PATH; ?>/app/plugins/default/theme/admin/images/confirm.png',
    	content:'<?php echo __('Are you sure to delete this item?','contactform'); ?>',
    	animate:'fadeDown',
    	buttons:[
                 {
                     text:'<i class="fa fa-times-circle"></i> <?php echo __('Delete','contactform'); ?>',
                     addClass:'btn-danger',
                     click:function(m_id,data){
                    	 $("#main-content").mask("<?php echo __('Loading...','contactform'); ?>");
                    	 $.get('<?php echo admin_url($this->action.'=delete');?>&id='+id+'&token='+token,function(obj){
                    	     if (obj.error == 0){
                    		    $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('contactform is deleted successfully','contactform'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
                    		 }else{
                    		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','contactform'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
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

function contactform_Oder(obj,field){
    $("#main-content").mask("<?php echo __('Loading...','contactform'); ?>");
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
    $("#main-content").mask("<?php echo __('Loading...','contactform'); ?>");
    $.post("<?php echo admin_url($this->action.'=change_status');?>", {action: "update-status",status: status,id: id},
    	function (data) {
    	$("#main-content").unmask();
        var result = "";
        if (status == "publish") {
            result = "<a href=\"javascript:change_status('" + id + "','unpublish')\" class=\"label label-success\"><?php echo __("Published","contactform"); ?></a>";
        } else {
        	result = "<a href=\"javascript:change_status('" + id + "','publish')\" class=\"label label-danger\"><?php echo __("Unpublished","contactform"); ?></a>";
        }
        $('#status_' + id).html(result);

    });
}

//View all status
function view_allstatus(id,status) {
    $("#main-content").mask("<?php echo __('Loading...','contactform'); ?>");
    $.post("<?php echo admin_url($src_param,false); ?>", {action: "view-status",status: status,id: id},
    	function (html) {
    	$("#main-content").unmask();
    	$('#main-content').html(html);
    	if(id == 1){
    		$('#view-title').html('<?php echo __('Published','page');?>');
    	}else if(id == 0){
    		$('#view-title').html('<?php echo __('Unpublished','page');?>');
    	}
    	$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
 	    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
    });
}
</script>                      
<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<!-- index -->
<?php
$search_btn = '<div class="btn-group">';
$search_btn .= form_button ( "<i class='fa fa-search'></i> " . __ ( 'Search', 'comment' ), array (
        'onclick' => 'Comment_Search_modal()',
        'style' => 'display:none',
        'class' => 'btn btn-default btn-index' 
) );
$src_param = 'admin-page=' . $this->controller->get->data ( 'admin-page' );
if (isset ( $this->controller->get->sub_page )) {
    $src_param .= 'sub_page=' . $this->controller->get->sub_page;
}
$search_btn .= form_button ( "<i class='fa fa-times'></i>", array (
        'onclick' => 'cancel_Comment_Search()',
        'style' => 'display:none',
        'class' => 'btn btn-default btn-index' 
) );
$search_btn .= '</div>';
add_toolbar_button ( $search_btn );
?>
<!-- edit -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','comment'), array('onclick' => 'Comment_Edit();','style' => 'display:none', 'class' => 'btn btn-primary btn-edit'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Apply','comment'), array('onclick' => 'Comment_Apply();','style' => 'display:none', 'class' => 'btn btn-info btn-edit'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','comment'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-edit'))); ?>
<!-- /edit -->

<div class="row" style="margin-bottom: 20px; margin-top: -15px;">
    <div class="col-sm-12">
        <h3>
            <?php if (!empty($this->menu_settings[$this->controller->get->{"admin-page"}])){ ?>
                <?php echo '<i class="' . $this->menu_settings[$this->controller->get->{"admin-page"}]['icon'] . '" style="color:'.$this->menu_settings[$this->controller->get->{"admin-page"}]['icon_color'].'"></i>'; ?>
            <?php }else{ ?>
                <?php global $_admin_menu; ?>
                <?php echo (!empty($_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'])) ? '<i class="' . $_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'] . '"></i>' : ''; ?>
            <?php } ?>
            <?php echo __('Comment','comment'); ?> <small><?php echo __('List','comment'); ?></small>
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
                <?php echo __('Bulk Actions','comment'); ?> <span
                    class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#"
                    onclick="bulk_action('delete'); return false;"><?php echo __('Delete','comment'); ?></a></li>
                <li><a href="#"
                    onclick="bulk_action('publish'); return false;"><?php echo __('Publish','comment'); ?></a></li>
                <li><a href="#"
                    onclick="bulk_action('unpublish'); return false;"><?php echo __('Unpublish','comment'); ?></a></li>
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
                <span id="view-title"><?php echo __('All', 'comment'); ?></span>
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a
                    href="<?php echo admin_url("admin-page=comment", false); ?>"> <?php echo __('All', 'comment'); ?></a></li>
                <li><a href="javascript:view_allstatus(1,'published');"> <?php echo __('Published', 'comment'); ?></a></li>
                <li><a
                    href="javascript:view_allstatus(2,'unpublished');"> <?php echo __('Unpublished', 'comment'); ?></a></li>
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
                                <th class="text-right"
                                    style="width: 30px; background: #3c8dbc; color: white;">#</th>
                                <th class="text-right"
                                    style="width: 30px; background: #3c8dbc; color: white;"><input
                                    type="checkbox" id="check-all"></th>

                                <th
                                    style="background: #3c8dbc; color: white;">
                <?php
                $comment_content_icon = "";
                if (! empty ( $this->controller->get->order_field ) && $this->controller->get->order_field == "comment_content") {
                    if (! empty ( $this->controller->get->order_type ) && strtolower ( $this->controller->get->order_type ) == "desc") {
                        $comment_content_icon = "fa-sort-desc";
                    } else {
                        $comment_content_icon = "fa-sort-asc";
                    }
                }
                
                ?>
                <div style="cursor: pointer; position: relative;"
                                        id="order_comment_content"
                                        onclick="Comment_Oder(this,'comment_content');">
                    <?php echo __("Content","comment"); ?> 
                    <i class="fa fa-sort"
                                            style="position: absolute; top: 4px; right: 0; color: #7fb1ce;"></i>
                                        <i class="fa order-icon <?php echo $comment_content_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($comment_content_icon == ""){ ?> display:none;<?php } ?>"></i>
                                    </div>
                                </th>
                                <th
                                    style="background: #3c8dbc; color: white;width: 150px">
                <?php
                $comment_author_icon = "";
                if (! empty ( $this->controller->get->order_field ) && $this->controller->get->order_field == "comment_author") {
                    if (! empty ( $this->controller->get->order_type ) && strtolower ( $this->controller->get->order_type ) == "desc") {
                        $comment_author_icon = "fa-sort-desc";
                    } else {
                        $comment_author_icon = "fa-sort-asc";
                    }
                }
                
                ?>
                <div style="cursor: pointer; position: relative;"
                                        id="order_comment_author"
                                        onclick="Comment_Oder(this,'comment_author');">
                    <?php echo __("Author","comment"); ?> 
                    <i class="fa fa-sort"
                                            style="position: absolute; top: 4px; right: 0; color: #7fb1ce;"></i>
                                        <i class="fa order-icon <?php echo $comment_author_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($comment_author_icon == ""){ ?> display:none;<?php } ?>"></i>
                                    </div>
                                </th>
                                <th
                                    style="background: #3c8dbc; color: white;width: 140px;">
                <?php
                $comment_created_date_icon = "";
                if (! empty ( $this->controller->get->order_field ) && $this->controller->get->order_field == "comment_created_date") {
                    if (! empty ( $this->controller->get->order_type ) && strtolower ( $this->controller->get->order_type ) == "desc") {
                        $comment_created_date_icon = "fa-sort-desc";
                    } else {
                        $comment_created_date_icon = "fa-sort-asc";
                    }
                }
                
                ?>
                <div style="cursor: pointer; position: relative;"
                                        id="order_comment_created_date"
                                        onclick="Comment_Oder(this,'comment_created_date');">
                    <?php echo __("Comment date","comment"); ?> 
                    <i class="fa fa-sort"
                                            style="position: absolute; top: 4px; right: 0; color: #7fb1ce;"></i>
                                        <i class="fa order-icon <?php echo $comment_created_date_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($comment_created_date_icon == ""){ ?> display:none;<?php } ?>"></i>
                                    </div>
                                </th>
                                <th
                                    style="background: #3c8dbc; color: white;width:90px">
                <?php
                $comment_status_icon = "";
                if (! empty ( $this->controller->get->order_field ) && $this->controller->get->order_field == "comment_status") {
                    if (! empty ( $this->controller->get->order_type ) && strtolower ( $this->controller->get->order_type ) == "desc") {
                        $comment_status_icon = "fa-sort-desc";
                    } else {
                        $comment_status_icon = "fa-sort-asc";
                    }
                }
                
                ?>
                <div style="cursor: pointer; position: relative;"
                                        id="order_comment_status"
                                        onclick="Comment_Oder(this,'comment_status');">
                    <?php echo __("Status","comment"); ?> 
                    <i class="fa fa-sort"
                                            style="position: absolute; top: 4px; right: 0; color: #7fb1ce;"></i>
                                        <i class="fa order-icon <?php echo $comment_status_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($comment_status_icon == ""){ ?> display:none;<?php } ?>"></i>
                                    </div>
                                </th>
                                <th class="text-center"
                                    style="width: 100px; background: #3c8dbc; color: white;"><?php echo __('Actions','comment'); ?></th>
                            </tr>
                        </thead>
                        <tbody>
                    <?php if (!empty($this->records) && is_array($this->records)){?>
                    <?php
                        
                    foreach ( $this->records as $index => $record ) {
                            $token = Pf_Plugin_CSRF::token ( $this->key . $record ['id'] );
                            ?>
                    <tr>
                                <td class="text-right"><?php echo (($this->page_index - 1) * NUM_PER_PAGE + $index +1); ?></td>
                                <td><input type="checkbox" name="id[]"
                                    value="<?php echo (int)$record['id'];?>">
                                    <input type="hidden"
                                    name="<?php echo 'token[' . (int)$record['id'] . ']' ?>"
                                    value="<?php echo $token; ?>"></td>


                                <td><?php echo $record["comment_content"]; ?></td>
                                <td><?php echo $record["comment_author"]; ?></td>
                                <td><?php echo str_to_mysqldate($record["comment_created_date"],"Y-m-d",$this->controller->comment_model->elements_value["comment_created_date"]." g:i A"); ?></td>
                                <td>
                                    <?php if($record['comment_status'] == 1){ ?>
                                        <span id="status_<?php echo $record['id'];?>">
                                            <a href="javascript:change_status(<?php echo $record['id'];?>,'unpublish');" class="label label-success"><?php echo __("Published","comment"); ?></a>
                                        </span>
                                    <?php }else{?>
                                        <span id="status_<?php echo $record['id'];?>">
                                            <a href="javascript:change_status(<?php echo $record['id'];?>,'publish')" class="label label-danger"><?php echo __("Unpublished","comment"); ?></a>
                                        </span>
                                    <?php }?>
                                </td>

                                <td class="text-center">
                                    <button type="button" onclick="view_comment(<?php echo (int)$record['id'];?>);" class="btn btn-primary btn-xs" title="<?php echo __('View comment field', 'comment'); ?>">
                                        <i class="fa fa-search"></i>
                                    </button>
                                    <button type="button" onclick="Comment_Edit('<?php echo (int)$record['id'];?>','<?php echo $token; ?>');" class="btn btn-info btn-xs">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button type="button" onclick="Comment_Delete('<?php echo (int)$record['id'];?>','<?php echo $token; ?>');" class="btn btn-danger btn-xs">
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
                        class="sr-only"><?php echo __('Close','comment'); ?></span>
                </button>
                <h4 class="modal-title"><?php echo __('Comment','comment'); ?> <?php echo __('Search','comment'); ?> </h4>
            </div>
            <form name="frm_Comment_Search" id="frm_Comment_Search"
                class="form-horizontal" role="form"
                onsubmit="return false;">
            <?php echo form_hidden('admin-page'); ?>
            <?php if (isset($this->controller->get->sub_page)){ echo form_hidden('sub_page'); } ?>
            <div class="modal-body">

                    <div class="form-group">
                        <label for="subject" class="col-sm-2 control-label">
                            <?php echo __("Content","comment"); ?>
                        </label>
                        <div class="col-sm-10">
                            <?php echo form_input("search[comment_content]",(isset($this->controller->get->search["comment_content"]))?$this->controller->get->search["comment_content"]:""); ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="subject" class="col-sm-2 control-label">
                            <?php echo __("Author","comment"); ?>
                        </label>
                        <div class="col-sm-10">
                            <?php echo form_input("search[comment_author]",(isset($this->controller->get->search["comment_author"]))?$this->controller->get->search["comment_author"]:""); ?>
                        </div>
                    </div>
                <?php echo Pf::event()->trigger("filter","comment-search-form"); ?>
            </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="Comment_Search();">
                    <i class='fa fa-search'></i> <?php echo __('Search','comment'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close','comment'); ?>
                </button>
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

<div class="hidden" id="control-clone">
    <div class="alert alert-block">
        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
        <div class="bodyMessage">

        </div>
    </div>
</div>

<div id="modalComment">
    <div class="modal fade" id="detailComment" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form class="form-horizontal" role="form" method="post" accept-charset="utf-8">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel"><?php echo __('Detail comment', 'comment'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="custom-field-message" id="show-comment-detail">
                        </div>
                    </div>
                    <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close', 'comment'); ?></button>
                    </div>
                </form>
            </div>
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
		$("#main-content").mask("<?php echo __('Loading...','comment'); ?>");
		$('#main-content').load($(this).attr('href'),function(){
			$("#main-content").unmask();
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		});
		event.preventDefault();
	});

	$('#pagingControl select').attr('onchange','return false;');
	
	$('#pagingControl select').change(function(event){
		$("#main-content").mask("<?php echo __('Loading...','comment'); ?>");
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
	    	content:'<?php echo __('Are you sure to perform this action?','comment'); ?>',
	    	animate:'fadeDown',
	    	buttons:[
	                 {
	                     text:'<i class="fa fa-times-circle"></i> <?php echo __('OK','comment'); ?>',
	                     addClass:'btn-danger',
	                     click:function(m_id,data){
	                    	    $("#main-content").mask("<?php echo __('Loading...','comment'); ?>");
	                    	    $.post($('#frm-bulk-action').attr('action')+'&type='+type,$('#frm-bulk-action').serialize(),function(obj){
    	              			    $('#main-content').load(obj.url,function(){
    	              				   $('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		                               $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
    	              				   $("#main-content").unmask();
    	              				   if (obj.error == 0){
    	              				        $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Comment is deleted successfully','comment'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
    	              				   }else{
    	              				        $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','comment'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
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

function Comment_Search_modal(){
    $('#search_modal').modal('show');
}

function Comment_Search(){
	$("#main-content").mask("<?php echo __('Loading...','comment'); ?>");
	$('#search_modal').modal('hide');
	$('#search_modal').on('hidden.bs.modal', function (e) {
		$.get('<?php echo admin_url($src_param,false); ?>',$('#frm_Comment_Search').serialize(),function(html){
	 		$('#main-content').html(html);
	 		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	 		$("#main-content").unmask();
	 	},'html');
	});
}

function cancel_Comment_Search(){
	$("#main-content").mask("<?php echo __('Loading...','comment'); ?>");
	$('#main-content').load('<?php echo admin_url($src_param,false); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}

function Comment_Add(){
	$("#main-content").mask("<?php echo __('Loading...','comment'); ?>");
	$('#main-content').load('<?php echo admin_url($this->action.'=add'); ?>',function(){
		$("#main-content").unmask();
	});
}

function Comment_Copy(id,token){
	$("#main-content").mask("<?php echo __('Loading...','comment'); ?>");
	$.get('<?php echo admin_url($this->action.'=copy');?>&id='+id+'&token='+token,function(obj){
		$("#main-content").unmask();
		if (obj.error == 0){
			$('#main-content').html(obj.content);
		}else if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','comment'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url);
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}

function Comment_Edit(id,token){
	$("#main-content").mask("<?php echo __('Loading...','comment'); ?>");
	$.get('<?php echo admin_url($this->action.'=edit');?>&id='+id+'&token='+token,function(obj){
		$("#main-content").unmask();
		if (obj.error == 0){
			$('#main-content').html(obj.content);
		}else if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','comment'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url);
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}

function Comment_Delete(id,token){
	$.sModal({
    	image:'<?php echo RELATIVE_PATH; ?>/app/plugins/default/theme/admin/images/confirm.png',
    	content:'<?php echo __('Are you sure to delete this comment?','comment'); ?>',
    	animate:'fadeDown',
    	buttons:[
                 {
                     text:'<i class="fa fa-times-circle"></i> <?php echo __('Delete','comment'); ?>',
                     addClass:'btn-danger',
                     click:function(m_id,data){
                    	 $("#main-content").mask("<?php echo __('Loading...','comment'); ?>");
                    	 $.get('<?php echo admin_url($this->action.'=delete');?>&id='+id+'&token='+token,function(obj){
                    	     if (obj.error == 0){
                    		    $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Comment is deleted successfully','comment'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
                    		 }else{
                    		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','comment'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
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

function Comment_Oder(obj,field){
    $("#main-content").mask("<?php echo __('Loading...','comment'); ?>");
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
    $("#main-content").mask("<?php echo __('Loading...','comment'); ?>");
    $.post("<?php echo admin_url($this->action.'=change_status');?>", {action: "update-status",status: status,id: id},
    	function (data) {
    	$("#main-content").unmask();
        var result = "";
        if (status == "publish"){
            result = "<a href=\"javascript:change_status("+id+",'unpublish')\" class=\"label label-success\"><?php echo __("Published","comment"); ?></a>"; 
        }else {
            result = "<a href=\"javascript:change_status("+id+",'publish')\" class=\"label label-danger\"><?php echo __("Unpublished","comment"); ?></a>";
        }
        $('#status_' + id).html(result);

    });
}

//Change all status
function view_allstatus(id,status) {
    $("#main-content").mask("<?php echo __('Loading...','comment'); ?>");
    $.post("<?php echo admin_url($src_param,false); ?>", {action: "view-status",status: status,id: id},
    	function (html) {
    	$("#main-content").unmask();
    	$('#main-content').html(html);
    	if(id == 1){
    		$('#view-title').html('<?php echo __('Published','comment');?>');
    	}else if(id == 2){
    		$('#view-title').html('<?php echo __('Unpublished','comment');?>');
    	}
    	$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
 	    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
    });
}

//View comment
function view_comment(id){
	$.post("<?php echo admin_url($this->action.'=detail');?>", {id: id}, function(result) {
		if (result.length > 0) {
        	$("#show-comment-detail").html(result);
            $('#detailComment').modal('show');
        }
	});
}

</script>
<style type="text/css">
    table.table.table-bordered tr td {vertical-align: middle;}
    span#view-title {text-transform: capitalize;}
</style>
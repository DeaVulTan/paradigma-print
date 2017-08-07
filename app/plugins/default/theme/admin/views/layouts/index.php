<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<!-- index -->
<?php
$search_btn = '<div class="btn-group">';
$search_btn .= form_button ( "<i class='fa fa-search'></i> " . __ ( 'Search', 'theme' ), array (
        'onclick' => 'widgets_Search_modal()',
        'style' => 'display:none',
        'class' => 'btn btn-default btn-index' 
) );
$src_param = 'admin-page=' . $this->controller->get->data ( 'admin-page' );
if (isset ( $this->controller->get->sub_page )) {
    $src_param .= 'sub_page=' . $this->controller->get->sub_page;
}
$search_btn .= form_button ( "<i class='fa fa-times'></i>", array (
        'onclick' => 'cancel_widgets_Search()',
        'style' => 'display:none',
        'class' => 'btn btn-default btn-index' 
) );
$search_btn .= '</div>';
add_toolbar_button ( $search_btn );
?>
<?php add_toolbar_button(form_button("<i class='fa fa-plus'></i> ".__('New layout','theme'), array('onclick' => 'layout_Add();','style' => 'display:none', 'class' => 'btn btn-primary btn-index'))); ?>
<!-- /index -->

<!-- add -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Back','theme'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-add'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Next','theme'), array('onclick' => 'next_new_layout();','style' => 'display:none', 'class' => 'btn btn-primary btn-add'))); ?>

<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Back','theme'), array('onclick' => 'back_to_add();','style' => 'display:none', 'class' => 'btn btn-default back_to_add'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','theme'), array('onclick' => 'layout_new();','style' => 'display:none', 'class' => 'btn btn-primary btn_builder_layout'))); ?>
<!-- /add -->

<!-- edit -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','theme'), array('onclick' => 'layout_new();','style' => 'display:none', 'class' => 'btn btn-primary btn-edit'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Apply','theme'), array('onclick' => 'layout_Apply();','style' => 'display:none', 'class' => 'btn btn-info btn-edit'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','theme'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-edit'))); ?>
<!-- /edit -->

<!-- copy -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','theme'), array('onclick' => 'layout_Copy();','style' => 'display:none', 'class' => 'btn btn-primary btn-copy'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','theme'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-copy'))); ?>
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
            <?php echo __('Layouts','theme'); ?>
        </h3>
    </div>
</div>
<div class="row" style="padding: 10px 0px;">
    <div class="col-md-12">
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
                                <th style="background: #3c8dbc; color: white;width: 600px;">
                                    <?php
                                    $title_icon = "";
                                    if (! empty ( $this->controller->get->order_field ) && $this->controller->get->order_field == "layout_name") {
                                        if (! empty ( $this->controller->get->order_type ) && strtolower ( $this->controller->get->order_type ) == "desc") {
                                            $title_icon = "fa-sort-desc";
                                        } else {
                                            $title_icon = "fa-sort-asc";
                                        }
                                    }
                                    
                                    ?>
                                    <div style="cursor: pointer; position: relative;" id="order_title" onclick="widgets_Oder(this,'layout_name');">
                                        <?php echo __("Layout name","theme"); ?> 
                                        <i class="fa fa-sort" style="position: absolute; top: 4px; right: 0; color: #7fb1ce;"></i>
                                        <i class="fa order-icon <?php echo $title_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($title_icon == ""){ ?> display:none;<?php } ?>"></i>
                                    </div>
                                </th>
                                <th style="background: #3c8dbc; color: white;width:80px;" class="text-center"><?php echo __("Actions","theme"); ?> </th>
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
                                <td><?php echo $record['layout_name'];  ?></td>
                                <td class="text-center">
                                    <button type="button"
                                        onclick="layout_Copy('<?php echo $record['id'];?>','<?php echo $token; ?>');"
                                        class="btn btn-primary btn-xs">
                                        <i class="fa fa-files-o"></i>
                                    </button>
                                    <button type="button"
                                        onclick="layout_Edit('<?php echo $record['id'];?>','<?php echo $token; ?>');"
                                        class="btn btn-info btn-xs">
                                        <i class="fa fa-pencil"></i>
                                    </button>
                                    <button type="button"
                                        onclick="layout_Delete('<?php echo $index;?>','<?php echo $token; ?>');"
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
                        class="sr-only"><?php echo __('Close','theme'); ?></span>
                </button>
                <h4 class="modal-title">Widgets <?php echo __('Search','theme'); ?> </h4>
            </div>
            <form name="frm_widgets_Search"
                id="frm_widgets_Search" class="form-horizontal"
                role="form" onsubmit="return false;">
            <?php echo form_hidden('admin-page'); ?>
            <?php if (isset($this->controller->get->sub_page)){ echo form_hidden('sub_page'); } ?>
            <div class="modal-body">
                    <div class="form-group">
                        <label for="subject"
                            class="col-sm-2 control-label">
                            <?php echo __("Widgets","theme"); ?>
                        </label>
                                <div class="col-sm-10">
                            <?php echo form_input("search[name]",(isset($this->controller->get->search["name"]))?$this->controller->get->search["name"]:""); ?>
                        </div>
                    </div>
                <?php echo Pf::event()->trigger("filter","widgets-search-form"); ?>
            </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="widgets_Search();">
                    <i class='fa fa-search'></i> <?php echo __('Search','theme'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close','theme'); ?></button>
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
	$('.btn-add-2').css({display:'none'});
	$('.back_to_add').css({display:'none'});
	$('.btn_builder_layout').css({display:'none'});

	
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
		$("#main-content").mask("<?php echo __('Loading...','theme'); ?>");
		$('#main-content').load($(this).attr('href'),function(){
			$("#main-content").unmask();
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		});
		event.preventDefault();
	});

	$('#pagingControl select').attr('onchange','return false;');
	
	$('#pagingControl select').change(function(event){
		$("#main-content").mask("<?php echo __('Loading...','theme'); ?>");
		$('#main-content').load($(this).data('url')+$(this).val(),function(){
			$("#main-content").unmask();
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
			$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		});
	});
});

function widgets_Search_modal(){
    $('#search_modal').modal('show');
}
function widgets_Search(){
	$("#main-content").mask("<?php echo __('Loading...','theme'); ?>");
	$('#search_modal').modal('hide');
	$('#search_modal').on('hidden.bs.modal', function (e) {
		$.get('<?php echo admin_url($src_param,false); ?>',$('#frm_widgets_Search').serialize(),function(html){
	 		$('#main-content').html(html);
	 		$("#main-content").unmask();
	 		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
			$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	 	},'html');
	});
}

function cancel_widgets_Search(){
	$("#main-content").mask("<?php echo __('Loading...','theme'); ?>");
	$('#main-content').load('<?php echo admin_url('&sub_page=layouts&search='); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}
function layout_Add(){
	$("#main-content").mask("<?php echo __('Loading...','theme'); ?>");
	$('#main-content').load('<?php echo admin_url($this->action.'=get_pattern'); ?>',function(){
		$("#main-content").unmask();
	});
}


function layout_Edit(id,token){
	$("#main-content").mask("<?php echo __('Loading...','theme'); ?>");
	$.get('<?php echo admin_url($this->action.'=edit');?>&id='+id+'&token='+token,function(obj){
		$("#main-content").unmask();
		if (obj.error == 0){
			$('#main-content').html(obj.content);
		}else if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','theme'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url);
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}

function layout_Copy(id,token){
	$("#main-content").mask("<?php echo __('Loading...','theme'); ?>");
	$.get('<?php echo admin_url($this->action.'=copy');?>&id='+id+'&token='+token,function(obj){
		$("#main-content").unmask();
		if (obj.error == 0){
			$('#main-content').html(obj.content);
		}else if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','theme'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url);
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}

function layout_Delete(id,token){
	$.sModal({
    	image:'<?php echo RELATIVE_PATH; ?>/app/plugins/default/theme/admin/images/confirm.png',
    	content:'<?php echo __('Are you sure to delete this item?','theme'); ?>',
    	animate:'fadeDown',
    	buttons:[
                 {
                     text:'<i class="fa fa-times-circle"></i> <?php echo __('Delete','theme'); ?>',
                     addClass:'btn-danger',
                     click:function(m_id,data){
                    	 $("#main-content").mask("<?php echo __('Loading...','theme'); ?>");
                    	 $.get('<?php echo admin_url($this->action.'=delete');?>&id='+id+'&token='+token,function(obj){
                    		 //alert(obj.error);
                     	     if (obj.error == 0){
                    		    $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Layouts is deleted successfully','theme'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
                    		 }else{
                    		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','theme'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
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
//ober widgets
function widgets_Oder(obj,field){
    $("#main-content").mask("<?php echo __('Loading...','theme'); ?>");
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
</script>

<style type="text/css">
table.table.table-bordered tr td {
    vertical-align: middle;
}

span#view-title {
    text-transform: capitalize;
}
</style>
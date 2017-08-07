<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<!-- index -->
<?php 
    $search_btn = '<div class="btn-group">';
    $search_btn .= form_button("<i class='fa fa-search'></i> ".__('Search','portfolio'), array('onclick' => 'Portfolio_Search_modal()','style' => 'display:none', 'class' => 'btn btn-default btn-index'));
    $src_param = 'admin-page='.$this->controller->get->data('admin-page');
    if (isset($this->controller->get->sub_page)){ $src_param .= 'sub_page='.$this->controller->get->sub_page; }
    $search_btn .= form_button("<i class='fa fa-times'></i>", array('onclick' => 'cancel_Portfolio_Search()','style' => 'display:none', 'class' => 'btn btn-default btn-index'));
    $search_btn .= '</div>';
    add_toolbar_button($search_btn); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-plus'></i> ".__('New','portfolio'), array('onclick' => 'Portfolio_Add();','style' => 'display:none', 'class' => 'btn btn-primary btn-index'))); ?>
<!-- /index -->

<!-- add -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','portfolio'), array('onclick' => 'Portfolio_New();','style' => 'display:none', 'class' => 'btn btn-primary btn-add'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','portfolio'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-add'))); ?>
<!-- /add -->

<!-- edit -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','portfolio'), array('onclick' => 'Portfolio_Edit();','style' => 'display:none', 'class' => 'btn btn-primary btn-edit'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Apply','portfolio'), array('onclick' => 'Portfolio_Apply();','style' => 'display:none', 'class' => 'btn btn-info btn-edit'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','portfolio'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-edit'))); ?>
<!-- /edit -->

<!-- copy -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','portfolio'), array('onclick' => 'Portfolio_Copy();','style' => 'display:none', 'class' => 'btn btn-primary btn-copy'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','portfolio'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-copy'))); ?>
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
            <?php echo __('Portfolios','portfolio'); ?>  <small><?php echo __('List','portfolio'); ?></small>
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
                <?php echo __('Bulk Action','portfolio'); ?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li>
                <a href="#" onclick="bulk_action('delete'); return false;"><?php echo __('Delete','portfolio'); ?></a>
                </li>
                <li>
                    <a href="#" onclick="bulk_action('publish'); return false;"><?php echo __('Publish','portfolio'); ?></a>
                </li>
                <li>
                    <a href="#" onclick="bulk_action('unpublish'); return false;"><?php echo __('Unpublish','portfolio'); ?></a>
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
        <ul class="list-unstyled filer list-inline">
            <li class="filterByStatus">
                <div class="btn-group" >
                    <button type="button"
                        class="btn btn-default dropdown-toggle"
                        data-toggle="dropdown">
                        <span id="view-title"><?php if(isset($this->controller->post->{'title'})){ echo __($this->controller->post->{'title'},'portfolio');}else{ echo __('All','portfolio');} ?></span>  
                        <span class="caret"></span>
                    </button>
                    <ul  class="dropdown-menu" role="menu">
                        <li>
                            <a href="javascript:view_allstatus('','All');"><?php echo __('All','portfolio'); ?></a>
                        </li>
                        <li>
                            <a href="javascript:view_allstatus(1,'publish');" ><?php echo __('Publish','portfolio'); ?></a>
                        </li>
                        <li>
                            <a href="javascript:view_allstatus(0,'unpublish');" ><?php echo __('Unpublish','portfolio'); ?></a>
                        </li>
                    </ul>
                    <input type="hidden" value="<?php if(isset($this->controller->post->{'cid'})){echo $this->controller->post->{'cid'};}?>" class="change"></input>
                </div>
            </li>
            <li  class="filterByStatus">
                <div class="btn-group" >
                    <button type="button"
                        class="btn btn-default dropdown-toggle"
                        data-toggle="dropdown">
                        <span id="view-title-cate"><?php if(isset($this->controller->post->{'titlecat'})){ echo __($this->controller->post->{'titlecat'},'portfolio');}else{ echo __('All Portfolio','portfolio');} ?></span>  
                        <span class="caret"></span>
                    </button>
                    <ul class="dropdown-menu" role="menu" id ="status">
                        <li>
                            <a href="javascript:view_category('','All portfolio');"><?php echo __('All Portfolio','portfolio'); ?></a>
                        </li>
                        <?php   foreach ($this->controller->category_model->list_cate() as $cate){?>
                        <li>
                            <a href="javascript:view_category(<?php echo $cate['id'];?>,'<?php echo $cate['category_name']?>');" id = "<?php echo $cate['id'];?>"><?php echo __($cate['category_name'],'portfolio'); ?></a>
                        </li>
                        <?php }?>
                    </ul>
                    <input type='hidden' value='<?php if(isset($this->controller->post->{'id'})){echo $this->controller->post->{'id'};}?>' class="change-st"></input>
                </div>
            </li>
        </ul>
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
                            
            <th style="background:#3c8dbc; color:white; width:400px;">
                <?php
                  $portfolio_name_icon = "";
                  if (!empty($this->controller->get->order_field) && $this->controller->get->order_field == "portfolio_name"){
                      if (!empty($this->controller->get->order_type) && strtolower($this->controller->get->order_type) == "desc"){
                          $portfolio_name_icon = "fa-sort-desc";
                      }else{
                          $portfolio_name_icon = "fa-sort-asc";
                      }
                  }
        
                ?>
                <div style="cursor:pointer; position: relative;" id="order_portfolio_name" onclick="Portfolio_Oder(this,'portfolio_name');">
                    <?php echo __("Portfolio name","portfolio"); ?> 
                    <i class="fa fa-sort" style="position:absolute; top:4px; right:0; color:#7fb1ce;"></i>
                    <i class="fa order-icon <?php echo $portfolio_name_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($portfolio_name_icon == ""){ ?> display:none;<?php } ?>"></i>
                </div>
            </th>
            <th style="background:#3c8dbc; color:white;  width:200px;"">
                <div style="cursor:pointer; position: relative;" id="order_portfolio_name" onclick="Portfolio_Oder(this,'portfolio_name');">
                    <?php echo __("Shortcode","portfolio"); ?> 
                </div>
            </th>
            <th style="background:#3c8dbc; color:white;">
                <div style="cursor:pointer; position: relative;" id="order_portfolio_description" onclick="Portfolio_Oder(this,'portfolio_description');">
                    <?php echo __("Category","portfolio"); ?> 
                </div>
            </th>
            <th style="background:#3c8dbc; color:white;">
                <?php
                  $portfolio_status_icon = "";
                  if (!empty($this->controller->get->order_field) && $this->controller->get->order_field == "portfolio_status"){
                      if (!empty($this->controller->get->order_type) && strtolower($this->controller->get->order_type) == "desc"){
                          $portfolio_status_icon = "fa-sort-desc";
                      }else{
                          $portfolio_status_icon = "fa-sort-asc";
                      }
                  }
        
                ?>
                <div style="cursor:pointer; position: relative;" id="order_portfolio_status" onclick="Portfolio_Oder(this,'portfolio_status');">
                    <?php echo __("Status","portfolio"); ?> 
                    <i class="fa fa-sort" style="position:absolute; top:4px; right:0; color:#7fb1ce;"></i>
                    <i class="fa order-icon <?php echo $portfolio_status_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($portfolio_status_icon == ""){ ?> display:none;<?php } ?>"></i>
                </div>
            </th>
                            <th class="text-center" style="width: 100px; background:#3c8dbc; color:white;"><?php echo __('Actions','portfolio'); ?></th>
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
            <?php 
                foreach ($this->controller->category_model->list_cate() as $cate){
                    $item[$cate['id']] = $cate['category_name'];
                }
             ?>                     
            <td><?php echo $record["portfolio_name"]; ?></td>
            <td><?php echo '{portfolio:display portid='.$record["id"].'}'; ?></td> 
            <td><?php echo $item[$record["portfolio_category"]]; ?></td>
            <td>
                 <?php if($record["portfolio_status"] == 1){?>
                    <span id='change-<?php echo $record['id'];?>'><a href="javascript:change_status(<?php echo $record['id'];?>,'unpublish')" class="label label-success"><?php echo __('Published','portfolio');?></a></span>
                <?php }else{?>
                    <span id='change-<?php echo $record['id']?>'><a href="javascript:change_status(<?php echo $record['id'];?>,'publish')" class="label label-danger"><?php echo __('Unpublished','portfolio');?></a></span>
                <?php }?>
            </td>
                <td class="text-center">
                    <button type="button"
                        onclick="Portfolio_Copy('<?php echo (int)$record['id'];?>','<?php echo $token; ?>');"
                        class="btn btn-primary btn-xs">
                        <i class="fa fa-files-o"></i>
                    </button>
                    <button type="button"
                        onclick="Portfolio_Edit('<?php echo (int)$record['id'];?>','<?php echo $token; ?>');"
                        class="btn btn-info btn-xs">
                        <i class="fa fa-pencil"></i>
                    </button>
                    <button type="button"
                        onclick="Portfolio_Delete('<?php echo (int)$record['id'];?>','<?php echo $token; ?>');"
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
                    <span aria-hidden="true">&times;</span><span class="sr-only"><?php echo __('Close','portfolio'); ?></span>
                </button>
                <h4 class="modal-title"><?php echo __('Portfolios','portfolio'); ?> <?php echo __('Search','portfolio'); ?> </h4>
            </div>
            <form name="frm_Portfolio_Search" id="frm_Portfolio_Search" class="form-horizontal" role="form" onsubmit="return false;">
            <?php echo form_hidden('admin-page'); ?>
            <?php if (isset($this->controller->get->sub_page)){ echo form_hidden('sub_page'); } ?>
            <div class="modal-body">
                
            <div class="form-group">
                <label for="subject" class="col-sm-2 control-label">
                    <?php echo __("Portfolio name","portfolio"); ?>
                </label>
                <div class="col-sm-10">
                    <?php echo form_input("search[portfolio_name]",(isset($this->controller->get->search["portfolio_name"]))?$this->controller->get->search["portfolio_name"]:""); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="subject" class="col-sm-2 control-label">
                    <?php echo __("Description","portfolio"); ?>
                </label>
                <div class="col-sm-10">
                    <?php echo form_input("search[portfolio_description]",(isset($this->controller->get->search["portfolio_description"]))?$this->controller->get->search["portfolio_description"]:""); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="subject" class="col-sm-2 control-label">
                    <?php echo __("Status","portfolio"); ?>
                </label>
                <div class="col-sm-10">
                    <div class="row"><?php foreach($this->controller->portfolio_model->elements_value["portfolio_status"] as $k => $v){ ?>    <label class="checkbox-inline"><?php echo form_checkbox("search[portfolio_status][]",$k,(isset($this->controller->get->search["portfolio_status"]) && in_array($k,$this->controller->get->search["portfolio_status"]))?true:false);?> <?php echo __($v,'portfolio'); ?></label><?php }?></div>
                </div>
            </div>
                <?php echo Pf::event()->trigger("filter","portfolio-search-form"); ?>
            </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="Portfolio_Search();"> <i class='fa fa-search'></i> <?php echo __('Search','portfolio'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close','portfolio'); ?></button>
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
		$("#main-content").mask("<?php echo __('Loading...','portfolio'); ?>");
		$('#main-content').load($(this).attr('href'),function(){
			$("#main-content").unmask();
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		});
		event.preventDefault();
	});

	$('#pagingControl select').attr('onchange','return false;');
	
	$('#pagingControl select').change(function(event){
		$("#main-content").mask("<?php echo __('Loading...','portfolio'); ?>");
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
	    	content:'<?php echo __('Are you sure to perform this action?','portfolio'); ?>',
	    	animate:'fadeDown',
	    	buttons:[
	                 {
	                     text:'<i class="fa fa-times-circle"></i> OK',
	                     addClass:'btn-danger',
	                     click:function(m_id,data){
	                    	    $("#main-content").mask("<?php echo __('Loading...','portfolio'); ?>");
	                    	    $.post($('#frm-bulk-action').attr('action')+'&type='+type,$('#frm-bulk-action').serialize(),function(obj){
    	              			    $('#main-content').load(obj.url,function(){
    	              				   $('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		                               $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
    	              				   $("#main-content").unmask();
    	              				   if (obj.error == 0){
    	              					 switch(obj.action){
       	              				         case 'delete':
           	              				       $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Portfolio is deleted successfully','portfolio'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
       	              				    	 break;
       	              				         case 'publish':
       	              				        	
       	              				    	   $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Portfolio is published successfully','portfolio'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
       	              				    	 break;
       	              				         case 'unpublish':
       	              				    	   $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Portfolio is unpublished successfully','portfolio'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
       	              				    	 break;
       	              				      }
    	              				   }else{
    	              				        $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There was an error. Please try again!','portfolio'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
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

function Portfolio_Search_modal(){
    $('#search_modal').modal('show');
}

function Portfolio_Search(){
	$("#main-content").mask("<?php echo __('Loading...','portfolio'); ?>");
	$('#search_modal').modal('hide');
	$('#search_modal').on('hidden.bs.modal', function (e) {
		$.get('<?php echo admin_url($src_param,false); ?>',$('#frm_Portfolio_Search').serialize(),function(html){
	 		$('#main-content').html(html);
	 		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	 		$("#main-content").unmask();
	 	},'html');
	});
}

function cancel_Portfolio_Search(){
	$("#main-content").mask("<?php echo __('Loading...','portfolio'); ?>");
	$('#main-content').load('<?php echo admin_url('&sub_page=portfolio&search='); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}

function Portfolio_Add(){
	$("#main-content").mask("<?php echo __('Loading...','portfolio'); ?>");
	$('#main-content').load('<?php echo admin_url($this->action.'=add'); ?>',function(){
		$("#main-content").unmask();
	});
}
function change_status(id,status) {
	$("#main-content").mask("<?php echo __('Loading...','portfolio'); ?>");
    $.post("<?php echo admin_url($this->action.'=change_status');?>", {action: "update-status",status: status,id: id},
    	function (data) {
    	$("#main-content").unmask();
        var result = "";
        if (status == "publish") {
            result = "<a href=\"javascript:change_status(" + id + ",'unpublish')\" class=\"label label-success\"><?php echo __("Published","portfolio"); ?></a>";
        } else {
        	result = "<a href=\"javascript:change_status(" + id + ",'publish')\" class=\"label label-danger\"><?php echo __("Unpublished","portfolio"); ?></a>";
        }
        $('#change-' + id).html(result);
     });
}
function Portfolio_Copy(id,token){
	$("#main-content").mask("<?php echo __('Loading...','portfolio'); ?>");
	$.get('<?php echo admin_url($this->action.'=copy');?>&id='+id+'&token='+token,function(obj){
		$("#main-content").unmask();
		if (obj.error == 0){
			$('#main-content').html(obj.content);
		}else if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There was an error. Please try again!','portfolio'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url);
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}

function Portfolio_Edit(id,token){
	$("#main-content").mask("<?php echo __('Loading...','portfolio'); ?>");
	$.get('<?php echo admin_url($this->action.'=edit');?>&id='+id+'&token='+token,function(obj){
		$("#main-content").unmask();
		if (obj.error == 0){
			$('#main-content').html(obj.content);
		}else if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There was an error. Please try again!','portfolio'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url);
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}

function Portfolio_Delete(id,token){
	$.sModal({
    	image:'<?php echo RELATIVE_PATH; ?>/app/plugins/default/theme/admin/images/confirm.png',
    	content:'<?php echo __('Are you sure to delete this item?','portfolio'); ?>',
    	animate:'fadeDown',
    	buttons:[
                 {
                     text:'<i class="fa fa-times-circle"></i> <?php echo __('Delete','portfolio'); ?>',
                     addClass:'btn-danger',
                     click:function(m_id,data){
                    	 $("#main-content").mask("<?php echo __('Loading...','portfolio'); ?>");
                    	 $.get('<?php echo admin_url($this->action.'=delete');?>&id='+id+'&token='+token,function(obj){
                    	     if (obj.error == 0){
                    		    $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Portfolio is deleted successfully','portfolio'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
                    		 }else{
                    		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There was an error. Please try again!','portfolio'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
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

function Portfolio_Oder(obj,field){
    $("#main-content").mask("<?php echo __('Loading...','portfolio'); ?>");
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

function view_allstatus(id,status) {
    $("#main-content").mask("<?php echo __('Loading...','portfolio'); ?>");
    var cid = $('.change').attr('value');
    var titlecat = $('#view-title-cate').html();
    $.post("<?php echo admin_url('&sub_page=portfolio'); ?>", {action: "view-status",status: status,id: id,cid: cid,titlecat: titlecat},
    	function (html) {
    	$("#main-content").unmask();
    	$('#main-content').html(html);
    	if(id == 1){
    		$('#view-title').html('<?php echo __('Published','portfolio');?>');
    	}else if(id == 0){
    		$('#view-title').html('<?php echo __('Unpublished','portfolio');?>');
    	}
    	$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
 	    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
    });
}
$('#status li a').click(function(){
	var test = $(this).attr('id');
    $('.change').attr('value',test)
})
// change view category
function view_category(cid,status) {
    $("#main-content").mask("<?php echo __('Loading...','portfolio'); ?>");
    var id = $('.change-st').attr('value');
    var title = $('#view-title').html();
    $.post("<?php echo admin_url('&sub_page=portfolio'); ?>", {action1: "view-category",status: status,cid: cid,id: id,title: title},
    	function (html) {
    	$("#main-content").unmask();
    	$('#main-content').html(html);
    	$('#view-title-cate').html(status);
    	$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
 	    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
    });
}
</script>
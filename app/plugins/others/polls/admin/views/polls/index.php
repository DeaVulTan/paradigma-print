<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<!-- index -->
<?php 
    $search_btn = '<div class="btn-group">';
    $search_btn .= form_button("<i class='fa fa-search'></i> ".__('Search','polls'), array('onclick' => 'Polls_Search_modal()','style' => 'display:none', 'class' => 'btn btn-default btn-index'));
    $src_param = 'admin-page='.$this->controller->get->data('admin-page');
    if (isset($this->controller->get->sub_page)){ $src_param .= 'sub_page='.$this->controller->get->sub_page; }
    $search_btn .= form_button("<i class='fa fa-times'></i>", array('onclick' => 'cancel_Polls_Search()','style' => 'display:none', 'class' => 'btn btn-default btn-index'));
    $search_btn .= '</div>';
    add_toolbar_button($search_btn); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-plus'></i> ".__('New','polls'), array('onclick' => 'Polls_Add();','style' => 'display:none', 'class' => 'btn btn-primary btn-index'))); ?>
<!-- /index -->

<!-- add -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','polls'), array('onclick' => 'Polls_New();','style' => 'display:none', 'class' => 'btn btn-primary btn-add'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','polls'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-add'))); ?>
<!-- /add -->

<!-- edit -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','polls'), array('onclick' => 'Polls_Edit();','style' => 'display:none', 'class' => 'btn btn-primary btn-edit'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Apply','polls'), array('onclick' => 'Polls_Apply();','style' => 'display:none', 'class' => 'btn btn-info btn-edit'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','polls'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-edit'))); ?>
<!-- /edit -->

<!-- copy -->
<?php add_toolbar_button(form_button("<i class='fa fa-check-circle'></i> ".__('Save','polls'), array('onclick' => 'Polls_Copy();','style' => 'display:none', 'class' => 'btn btn-primary btn-copy'))); ?>
<?php add_toolbar_button(form_button("<i class='fa fa-times-circle'></i> ".__('Cancel','polls'), array('onclick' => 'back_to_list();','style' => 'display:none', 'class' => 'btn btn-default btn-copy'))); ?>
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
            <?php echo __('Polls','polls'); ?>  <small><?php echo __('List','polls'); ?></small>
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
                <?php echo __('Bulk Action','polls'); ?> <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li><a href="#" onclick="bulk_action('delete'); return false;"><?php echo __('Delete','polls'); ?></a></li>
                <li>
                    <a href="#" onclick="bulk_action('publish'); return false;"><?php echo __('Publish','polls'); ?></a>
                </li>
                <li>
                    <a href="#" onclick="bulk_action('unpublish'); return false;"><?php echo __('Unpublish','polls'); ?></a>
                </li>
            </ul>
        </div>
    </div>
</div>
<div class="row" style="margin-bottom: 20px;">
    <div class="col-md-6">
        <div class="btn-group" >
            <button type="button"
                class="btn btn-default dropdown-toggle"
                data-toggle="dropdown">
                <span id="view-title"><?php echo __('All','polls'); ?></span>  
                <span class="caret"></span>
            </button>
            <ul class="dropdown-menu" role="menu">
                <li>
                    <a href="<?php echo admin_url("admin-page=polls", false); ?>"><?php echo __('All','polls'); ?></a>
                </li>
                <li>
                    <a href="javascript:view_allstatus(1,'publish');" ><?php echo __('Publish','polls'); ?></a>
                </li>
                <li>
                    <a href="javascript:view_allstatus(0,'unpublish');" ><?php echo __('Unpublish','polls'); ?></a>
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
            <form method="post" role="form" action="<?php echo admin_url('&action=bulk_action'); ?>" id="frm-bulk-action">
                <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead>
                        <tr>
                            <th class="text-right" style="width: 30px; background:#3c8dbc; color:white;">#</th>
                            <th class="text-right" style="width: 30px; background:#3c8dbc; color:white;"><input type="checkbox" id="check-all"></th>
                            
            <th style="background:#3c8dbc; color:white; width: 300px;">
                <?php
                  $polls_question_icon = "";
                  if (!empty($this->controller->get->order_field) && $this->controller->get->order_field == "polls_question"){
                      if (!empty($this->controller->get->order_type) && strtolower($this->controller->get->order_type) == "desc"){
                          $polls_question_icon = "fa-sort-desc";
                      }else{
                          $polls_question_icon = "fa-sort-asc";
                      }
                  }
        
                ?>
                <div style="cursor:pointer; position: relative;" id="order_polls_question" onclick="Polls_Oder(this,'polls_question');">
                    <?php echo __("Question","polls"); ?> 
                    <i class="fa fa-sort" style="position:absolute; top:4px; right:0; color:#7fb1ce;"></i>
                    <i class="fa order-icon <?php echo $polls_question_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($polls_question_icon == ""){ ?> display:none;<?php } ?>"></i>
                </div>
            </th>
            <th style="background:#3c8dbc; color:white;  width:200px;"">
                <div style="cursor:pointer; position: relative;" id="order_portfolio_name" onclick="Portfolio_Oder(this,'portfolio_name');">
                    <?php echo __("Shortcode","portfolio"); ?> 
                </div>
            </th>
            <th style="background:#3c8dbc; color:white; width:150px">
                <?php
                  $polls_pubdate_icon = "";
                  if (!empty($this->controller->get->order_field) && $this->controller->get->order_field == "polls_pubdate"){
                      if (!empty($this->controller->get->order_type) && strtolower($this->controller->get->order_type) == "desc"){
                          $polls_pubdate_icon = "fa-sort-desc";
                      }else{
                          $polls_pubdate_icon = "fa-sort-asc";
                      }
                  }
        
                ?>
                <div style="cursor:pointer; position: relative;" id="order_polls_pubdate" onclick="Polls_Oder(this,'polls_pubdate');">
                    <?php echo __("Publish Date","polls"); ?> 
                    <i class="fa fa-sort" style="position:absolute; top:4px; right:0; color:#7fb1ce;"></i>
                    <i class="fa order-icon <?php echo $polls_pubdate_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($polls_pubdate_icon == ""){ ?> display:none;<?php } ?>"></i>
                </div>
            </th>
            <th style="background:#3c8dbc; color:white; width:150px">
                <?php
                  $polls_unpubdate_icon = "";
                  if (!empty($this->controller->get->order_field) && $this->controller->get->order_field == "polls_unpubdate"){
                      if (!empty($this->controller->get->order_type) && strtolower($this->controller->get->order_type) == "desc"){
                          $polls_unpubdate_icon = "fa-sort-desc";
                      }else{
                          $polls_unpubdate_icon = "fa-sort-asc";
                      }
                  }
        
                ?>
                <div style="cursor:pointer; position: relative;" id="order_polls_unpubdate" onclick="Polls_Oder(this,'polls_unpubdate');">
                    <?php echo __("Unpublish Date","polls"); ?> 
                    <i class="fa fa-sort" style="position:absolute; top:4px; right:0; color:#7fb1ce;"></i>
                    <i class="fa order-icon <?php echo $polls_unpubdate_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($polls_unpubdate_icon == ""){ ?> display:none;<?php } ?>"></i>
                </div>
            </th>
            <th style="background:#3c8dbc; color:white; width:100px;">
                <?php
                  $polls_status_icon = "";
                  if (!empty($this->controller->get->order_field) && $this->controller->get->order_field == "polls_status"){
                      if (!empty($this->controller->get->order_type) && strtolower($this->controller->get->order_type) == "desc"){
                          $polls_status_icon = "fa-sort-desc";
                      }else{
                          $polls_status_icon = "fa-sort-asc";
                      }
                  }
        
                ?>
                <div style="cursor:pointer; position: relative;" id="order_polls_status" onclick="Polls_Oder(this,'polls_status');">
                    <?php echo __("Status","polls"); ?> 
                    <i class="fa fa-sort" style="position:absolute; top:4px; right:0; color:#7fb1ce;"></i>
                    <i class="fa order-icon <?php echo $polls_status_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($polls_status_icon == ""){ ?> display:none;<?php } ?>"></i>
                </div>
            </th>
            <th style="background:#3c8dbc; color:white; width:100px">
                <?php
                  $polls_totalvote_icon = "";
                  if (!empty($this->controller->get->order_field) && $this->controller->get->order_field == "polls_totalvote"){
                      if (!empty($this->controller->get->order_type) && strtolower($this->controller->get->order_type) == "desc"){
                          $polls_totalvote_icon = "fa-sort-desc";
                      }else{
                          $polls_totalvote_icon = "fa-sort-asc";
                      }
                  }
        
                ?>
                <div style="cursor:pointer; position: relative;" id="order_polls_totalvote" onclick="Polls_Oder(this,'polls_totalvote');">
                    <?php echo __("Total Votes","polls"); ?> 
                    <i class="fa fa-sort" style="position:absolute; top:4px; right:0; color:#7fb1ce;"></i>
                    <i class="fa order-icon <?php echo $polls_totalvote_icon; ?>" style="position:absolute; top:4px; right:0; <?php if ($polls_totalvote_icon == ""){ ?> display:none;<?php } ?>"></i>
                </div>
            </th>
                            <th class="text-center" style="width: 100px; background:#3c8dbc; color:white;"><?php echo __('Actions','polls'); ?></th>
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
                                
                            
            <td><?php echo cut($record["polls_question"],50, $more=" ..."); ?></td>
            <td><?php echo '{polls:display id='.$record["id"].'}'; ?></td> 
            <td>
                <?php 
                    if($record["polls_pubdate"] == 0){
                        echo '';
                    }else{
                        echo $record["polls_pubdate"];
                    }
                ?>
            </td>
            <td>
                 <?php 
                    if($record["polls_unpubdate"] == 0){
                        echo '';
                    }else{
                        echo $record["polls_unpubdate"];
                    }
                ?>
            </td>
            <td>
                 <?php if($record["polls_status"] == 1){?>
                    <span id='change-<?php echo $record['id'];?>'><a href="javascript:change_status(<?php echo $record['id'];?>,'unpublish')" class="label label-success"><?php echo __('Published','polls');?></a></span>
                <?php }else{?>
                    <span id='change-<?php echo $record['id']?>'><a href="javascript:change_status(<?php echo $record['id'];?>,'publish')" class="label label-danger"><?php echo __('Unpublished','polls');?></a></span>
                <?php }?>
            </td>
            <td><?php echo $record["polls_totalvote"]; ?></td>
                            
                            <td class="text-center">
                                <button type="button"
                                    onclick="Polls_Copy('<?php echo (int)$record['id'];?>','<?php echo $token; ?>');"
                                    class="btn btn-primary btn-xs">
                                    <i class="fa fa-files-o"></i>
                                </button>
                                <button type="button"
                                    onclick="Polls_Edit('<?php echo (int)$record['id'];?>','<?php echo $token; ?>');"
                                    class="btn btn-info btn-xs">
                                    <i class="fa fa-pencil"></i>
                                </button>
                                <button type="button"
                                    onclick="Polls_Delete('<?php echo (int)$record['id'];?>','<?php echo $token; ?>');"
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
                    <span aria-hidden="true">&times;</span><span class="sr-only"><?php echo __('Close','polls'); ?></span>
                </button>
                <h4 class="modal-title">Polls <?php echo __('Search','polls'); ?> </h4>
            </div>
            <form name="frm_Polls_Search" id="frm_Polls_Search" class="form-horizontal" role="form" onsubmit="return false;">
            <?php echo form_hidden('admin-page'); ?>
            <?php if (isset($this->controller->get->sub_page)){ echo form_hidden('sub_page'); } ?>
            <div class="modal-body">
                
            <div class="form-group">
                <label for="subject" class="col-sm-2 control-label">
                    <?php echo __("Question","polls"); ?>
                </label>
                <div class="col-sm-10">
                    <?php echo form_input("search[polls_question]",(isset($this->controller->get->search["polls_question"]))?$this->controller->get->search["polls_question"]:""); ?>
                </div>
            </div>
            <div class="form-group">
                <label for="subject" class="col-sm-2 control-label">
                    <?php echo __("Status","polls"); ?>
                </label>
                <div class="col-sm-10">
                    <div class="row"><?php foreach($this->controller->polls_model->elements_value["polls_status"] as $k => $v){ ?>    <label class="checkbox-inline"><?php echo form_checkbox("search[polls_status][]",$k,(isset($this->controller->get->search["polls_status"]) && in_array($k,$this->controller->get->search["polls_status"]))?true:false);?> <?php echo $v; ?></label><?php }?></div>
                </div>
            </div>
                <?php echo Pf::event()->trigger("filter","polls-search-form"); ?>
            </div>
            </form>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="Polls_Search();"> <i class='fa fa-search'></i> <?php echo __('Search','polls'); ?></button>
                <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close','polls'); ?></button>
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
		$("#main-content").mask("<?php echo __('Loading...','polls'); ?>");
		$('#main-content').load($(this).attr('href'),function(){
			$("#main-content").unmask();
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		});
		event.preventDefault();
	});

	$('#pagingControl select').attr('onchange','return false;');
	
	$('#pagingControl select').change(function(event){
		$("#main-content").mask("<?php echo __('Loading...','polls'); ?>");
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
	    	content:'<?php echo __('Are you sure to perform this action?','polls'); ?>',
	    	animate:'fadeDown',
	    	buttons:[
	                 {
	                     text:'<i class="fa fa-times-circle"></i> OK',
	                     addClass:'btn-danger',
	                     click:function(m_id,data){
	                    	    $("#main-content").mask("<?php echo __('Loading...','polls'); ?>");
	                    	    $.post($('#frm-bulk-action').attr('action')+'&type='+type,$('#frm-bulk-action').serialize(),function(obj){
    	              			    $('#main-content').load(obj.url,function(){
    	              				   $('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		                               $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
    	              				   $("#main-content").unmask();
        	              				 if (obj.error == 0){
          	              				      switch(obj.action){
          	              				         case 'delete':
              	              				       $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Poll is deleted successfully','polls'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
          	              				    	 break;
          	              				         case 'publish':
          	              				        	
          	              				    	   $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Poll is published successfully','polls'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
          	              				    	 break;
          	              				         case 'unpublish':
          	              				    	   $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Poll is unpublished successfully','polls'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
          	              				    	 break;
          	              				      }
          	              				   }else{
          	              				        $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','polls'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
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

function Polls_Search_modal(){
    $('#search_modal').modal('show');
}

function Polls_Search(){
	$("#main-content").mask("<?php echo __('Loading...','polls'); ?>");
	$('#search_modal').modal('hide');
	$('#search_modal').on('hidden.bs.modal', function (e) {
		$.get('<?php echo admin_url($src_param,false); ?>',$('#frm_Polls_Search').serialize(),function(html){
	 		$('#main-content').html(html);
	 		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	 		$("#main-content").unmask();
	 	},'html');
	});
}

function cancel_Polls_Search(){
	$("#main-content").mask("<?php echo __('Loading...','polls'); ?>");
	$('#main-content').load('<?php echo admin_url($src_param,false); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}

function Polls_Add(){
	$("#main-content").mask("<?php echo __('Loading...','polls'); ?>");
	$('#main-content').load('<?php echo admin_url($this->action.'=add'); ?>',function(){
		$("#main-content").unmask();
	});
}

function Polls_Copy(id,token){
	$("#main-content").mask("<?php echo __('Loading...','polls'); ?>");
	$.get('<?php echo admin_url($this->action.'=copy');?>&id='+id+'&token='+token,function(obj){
		$("#main-content").unmask();
		if (obj.error == 0){
			$('#main-content').html(obj.content);
		}else if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','polls'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url);
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}

function Polls_Edit(id,token){
	$("#main-content").mask("<?php echo __('Loading...','polls'); ?>");
	$.get('<?php echo admin_url($this->action.'=edit');?>&id='+id+'&token='+token,function(obj){
		$("#main-content").unmask();
		if (obj.error == 0){
			$('#main-content').html(obj.content);
		}else if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','polls'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url);
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}

function Polls_Delete(id,token){
	$.sModal({
    	image:'<?php echo RELATIVE_PATH; ?>/app/plugins/default/theme/admin/images/confirm.png',
    	content:'<?php echo __('Are you sure to delete this item?','polls'); ?>',
    	animate:'fadeDown',
    	buttons:[
                 {
                     text:'<i class="fa fa-times-circle"></i> <?php echo __('Delete','polls'); ?>',
                     addClass:'btn-danger',
                     click:function(m_id,data){
                    	 $("#main-content").mask("<?php echo __('Loading...','polls'); ?>");
                    	 $.get('<?php echo admin_url($this->action.'=delete');?>&id='+id+'&token='+token,function(obj){
                    	     if (obj.error == 0){
                    		    $.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Poll is deleted successfully','polls'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
                    		 }else{
                    		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','polls'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
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

function Polls_Oder(obj,field){
    $("#main-content").mask("<?php echo __('Loading...','polls'); ?>");
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
function change_status(id,status) {
	$("#main-content").mask("<?php echo __('Loading...','polls'); ?>");
    $.post("<?php echo admin_url($this->action.'=change_status');?>", {action: "update-status",status: status,id: id},
    	function (data) {
    	$("#main-content").unmask();
        var result = "";
        if (status == "publish") {
            result = "<a href=\"javascript:change_status(" + id + ",'unpublish')\" class=\"label label-success\"><?php echo __("Published","announcement"); ?></a>";
        } else {
        	result = "<a href=\"javascript:change_status(" + id + ",'publish')\" class=\"label label-danger\"><?php echo __("Unpublished","announcement"); ?></a>";
        }
        $('#change-' + id).html(result);
     });
}
function view_allstatus(id,status) {
    $("#main-content").mask("<?php echo __('Loading...','polls'); ?>");
    $.post("<?php echo admin_url($src_param,false); ?>", {action: "view-status",status: status,id: id},
    	function (html) {
    	$("#main-content").unmask();
    	$('#main-content').html(html);
    	$('#view-title').html(status);
    	$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
 	    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
    });
}
</script>
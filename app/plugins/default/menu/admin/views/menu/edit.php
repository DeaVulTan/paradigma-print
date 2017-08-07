<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<div class="row" style="margin-bottom: 20px;margin-top:-15px;">
    <div class="col-sm-12">
        <h3>
            <?php if (!empty($this->menu_settings[$this->controller->get->{"admin-page"}])){ ?>
                <?php echo '<i class="' . $this->menu_settings[$this->controller->get->{"admin-page"}]['icon'] . '" style="color:'.$this->menu_settings[$this->controller->get->{"admin-page"}]['icon_color'].'"></i>'; ?>
            <?php }else{ ?>
                <?php global $_admin_menu; ?>
                <?php echo (!empty($_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'])) ? '<i class="' . $_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'] . '"></i>' : ''; ?>
            <?php } ?>
            <?php echo __('Menu','menu'); ?>  <small><?php echo __('Edit','menu'); ?></small>
        </h3>
    </div>
    <?php
    //debug($this->controller->post->data_menu[0]); 
    ?>
</div>
<div class="row">
    <div class="col-sm-12">
        <form name="frm_Menu_Edit" id="frm_Menu_Edit" class="form-horizontal" role="form" action="<?php echo admin_url(); ?>" method="post" onsubmit="return false;">
            <div class="panel-body">
                <!-- <form method="post" action="" class="form-horizontal" role="form" id="form"> -->
                
                    <div class="row col-lg-12 box-body" style="margin: auto">
                            <center>
                                <table class="table table-bordered table-configuration">
                                    <colgroup><col class="col-md-2">
                                    </colgroup><tbody>
                                        <tr>
                                            <th class='backgroundf9'><label class="control-label"><?php echo __("Menu Name", "menu"); ?><span class='requiredfield'>*</span></label></th>
                                            <td id="ename">
                                                <div class="help-block" id='err_title'></div>
                                                <input type="text" name="title" class="form-control" value="<?php echo $this->controller->post->value;?>" required="yes" id="menu_title">
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </center>
                    </div>
                
                    <div class="col-lg-12">
                        <a class="btn btn-primary btn-xs" data-toggle="modal" data-target="#new-page" onclick="dismiss_edit()"><?php echo __("Add Page", "menu"); ?></a>
                        <a class="btn btn-primary btn-xs" data-toggle="modal" data-target="#new-url" onclick="dismiss_edit()"><?php echo __("Add Link", "menu"); ?></a>
                        <h4><?php echo __("Preview", "menu"); ?> <small>(<?php echo __('Notice: You can order menu by dragging them', 'menu') ?>)</small></h4>
                        <div class="cf nestable-lists">
                            <div class="dd" id="nestable">
                                <ol class="dd-list" id="parent">
                                <?php
                                $this->controller->menu_model->menu_($this->controller->post->data_menu[0],$this->controller->post->data_menu[1]);
                                ?>
                                </ol>
                                
                            </div>
                            <div class="col-lg-12">
                                <textarea id="nestable-output" name="json-menu" class='hidden'></textarea>
                                <textarea id="nestable-output-data" name="json-data" class="form-control hidden" ><?php echo json_encode($this->controller->post->menu_all[$this->controller->post->k]['data'][1]);?></textarea>
                            </div>
                        </div>
                    </div>
                <!-- /form> -->
            </div>

            <?php echo Pf::event()->trigger("filter","menu-form"); ?>
            <?php echo Pf::event()->trigger("filter","menu-adding-form"); ?>
        </form>
        <div id="new-page" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
        
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel"><?php echo __("Add Page to menu", "menu"); ?></h4>
                    </div>
                    <input type="hidden" name='item-type' value="page" id="item-type"/>
                    <input type="hidden" class="item-id" name='item-id' value="" />
                    <div class="modal-body">
                            <div class="form-horizontal" role="form">
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"><?php echo __("Name", "menu"); ?><span class="requiredfield">*</span></label>
                                    <div class="col-sm-9 itemname">
                                        <input type="text" name="item-name" class="form-control item-name" value="" id="item-name">
                                        <div id="page-name" class="help-block"></div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"><?php echo __("Choose Page", "menu"); ?><span class='requiredfield'>*</span></label>
                                    <div class="col-sm-9">
                                        <select class="form-control item-call" name="item-call" id="pagepicker">
                                            <?php
                                                $page_list = $this->controller->menu_model->get_page();
                                                if(!empty($page_list)){
                                                    foreach($page_list as $page){
                                                        echo "<option value='".$page['id']."'>".$page['page_title']."</option>";
                                                    }
                                                }
                                            ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"><?php echo __("Options", "menu"); ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="item-options" class="form-control item-options" placeholder="" value="">
                                        <h6><?php echo __('Example: ','menu'); ?> #example | ?example | &axample ...</h6>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"><?php echo __("Icon", "menu"); ?></label>
                                    <div class="col-sm-9">
                                        <div class="col-md-12 no-padding">
                                            <label> <?php echo __("CSS class","menu"); ?> <input type="radio" name="icon" class="-css">  </label>&nbsp; 
                                            <label> <?php echo __("Images","menu"); ?> <input type="radio" name="icon" class="-img"></label>
                                        </div>
                                        <div class="input-group" style="width:100%;" id="css-input">
                                            <input type="text" name="item-icon" class="form-control item-icon" value="" placeholder="Example: fa fa-user" style="width: 248px;">
                                        	<span class="input-group-addon" datacolor="#F00" style="background-color: #fff;border: none;"></span>
                                  			<div class="input-group input-max-width color-pic">
                                  				<input class="form-control inline color-picker item-color" type="text" name="item-color" id="item-color" />
                                  				<span class="input-group-addon" id="color-icon"><i></i></span>
                                  			</div>
        
        									<script>
        									    $(document).ready(function(){
        									        $('.color-pic').colorpicker();
        									    });
        									</script>
        									<style>.colorpicker.dropdown-menu.colorpicker-visible {z-index: 9999 !important;}</style>
        
        
                                        </div>
                                        <div class="input-group" style="display:none;" id="img-input">
                                            <input type="text" name="item-icon" class="form-control item-icon" value="">
                                            <span class="input-group-addon">
                                                <a class="boxGetFile" type="button" href="<?php echo site_url(false) . RELATIVE_PATH . '/app/plugins/default/media/filemanager/dialog.php?type=1&field_id=icon-image'; ?>"><?php echo __("Select Image","menu"); ?></a>
                                            </span>
                                        </div>
                                        
                                        <script>
                                    $(document).ready(function(){
                                        $('#new-page input[type="radio"]').on('ifClicked', function() {
                                            if($(this).attr('class')=='-img'){
                                                $('#new-page #css-input').hide();
                                                $('#new-page #img-input').show();
                                            }
                                            else if ($(this).attr('class')=='-css'){
                                                $('#new-page #css-input').show();
                                                $('#new-page #img-input').hide();
                                            }
                                        });
                                    });
                                </script>
                                        
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label"><?php echo __("Description", "menu"); ?></label>
                                    <div class="col-sm-9">
                                        <input type="text" name="item-desc" class="form-control item-desc" value="">
                                    </div>
                                </div>
                            </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __("Close", "menu"); ?></button>
                        <a name="submit-add"  class="btn btn-primary apply-click" onclick="add_item('page');"><?php echo __("Apply", "menu"); ?></a>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <div id="new-url" class="modal fade" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">×</button>
                        <h4 class="modal-title" id="myModalLabel"><?php echo __("Add URL to menu", "menu"); ?></h4>
                    </div>
                    <input type="hidden" class="" name='item-type' value="url" />
                    <input type="hidden" class="item-id" name='item-id' value="" />
                    <input type="hidden" class="act" name='act' value="" />
                    <div class="modal-body">
                        <div class="form-horizontal" role="form">
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo __("Name", "menu"); ?><span class='requiredfield'>*</span></label>
                                <div class="col-sm-9 itemname">
                                    <input type="text" name="item-name" class="form-control item-name" value="" id="item-name">
                                    <div id="url-name" class="help-block"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo __("Enter Link", "menu"); ?><span class="requiredfield">*</span></label>
                                <div class="col-sm-9 urlcall">
                                    <?php echo form_input(array("name" => "item-call", "class" => 'item-call form-control', "id" => "item-link")); ?>
                                    <div id="url-call" class="help-block"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                    <label class="col-sm-3 control-label"><?php echo __("Icon", "menu"); ?></label>
                                    <div class="col-sm-9">
                                        <div class="col-md-12 no-padding">
                                            <label> <?php echo __("CSS class","menu"); ?> <input type="radio" name="icon" class="-css">  </label>&nbsp; 
                                            <label> <?php echo __("Images","menu"); ?> <input type="radio" name="icon" class="-img"></label>
                                        </div>
                                        <div class="input-group" style="width:100%;" id="css-input">
                                            <input type="text" name="item-icon" class="form-control item-icon" value="" placeholder="Example: fa fa-user" style="width: 248px;">
                                        	<span class="input-group-addon" datacolor="#F00" style="background-color: #fff;border: none;"></span>
                                  			<div class="input-group input-max-width color-pic">
                                  				<input class="form-control inline color-picker item-color" type="text" name="item-color" id="item-color" />
                                  				<span class="input-group-addon" id="color-icon"><i></i></span>
                                  			</div>
        
        									<script>
        									    $(function(){
        									        $('.color-pic').colorpicker();
        									    });
        									</script>
        									<style>.colorpicker.dropdown-menu.colorpicker-visible {z-index: 9999 !important;}</style>
                                        </div>
                                        <div class="input-group" style="display:none;" id="img-input">
                                            <input type="text" name="item-icon" class="form-control item-icon" value="">
                                            <span class="input-group-addon">
                                                <a class="boxGetFile" type="button" href="<?php echo site_url(false) . RELATIVE_PATH . '/app/plugins/default/media/filemanager/dialog.php?type=1&field_id=icon-image'; ?>"><?php echo __("Select Image","menu"); ?></a>
                                            </span>
                                        </div>
                                        <script>
                                            $(document).ready(function(){
                                                $('#new-url input[type="radio"]').on('ifClicked', function() {
                                                    if($(this).attr('class')=='-img'){
                                                        $('#new-url #css-input').hide();
                                                        $('#new-url #img-input').show();
                                                    }
                                                    else if ($(this).attr('class')=='-css'){
                                                        $('#new-url #css-input').show();
                                                        $('#new-url #img-input').hide();
                                                    }
                                                });
                                            });
                                        </script>
                                    </div>
                                </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label"><?php echo __("Description", "menu"); ?></label>
                                <div class="col-sm-9">
                                    <input type="text" name="item-desc" class="form-control item-desc" value="">
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __("Close", "menu"); ?></button>
                        <a name="submit-add"  class="btn btn-primary apply-click" onclick="add_item('url')"><?php echo __("Apply", "menu"); ?></a>
                    </div>
                    </div>
                </div><!-- /.modal-content -->
            </div><!-- /.modal-dialog -->
        </div>
        <div class="hidden"><input type="text" id="icon-image" value=""></div>
        <div class="controlClone hidden">
            <input type="text" class="form-control input-sm" id="tempInput" />
        </div>
    </div>
</div>

<script>
$(document).ready(function(){
	$('.btn-index').css({display:'none'});
	$('.btn-edit').css({display:'inline-block'});
	$('.btn-copy').css({display:'none'});
	$('.btn-add').css({display:'none'});
	//$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
	//$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
});
function back_to_list(){
	$("#main-content").mask("<?php echo __('Loading...','menu'); ?>");
	try{
	   tinymce.remove();
	}catch(e){}
	$('#main-content').load('<?php echo admin_url($this->action.'=index&token='); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}
function Menu_Edit(){
	$("#main-content").mask("<?php echo __('Loading...','menu'); ?>");
	try{
	   tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Menu_Edit').attr('action'),$('#frm_Menu_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','menu'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		    $("#ename").addClass("has-error");
		    $("#err_title").html(obj.warning);
			//$('#main-content').html(obj.content);
			//$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    //$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if(obj.error == 2){
			$.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('Menu name can not more than 255 characters','menu'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$("#err_title").html(obj.warning);
			//$('#main-content').html(obj.content);
			//$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    //$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Menu is created successfully','menu'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
	},'json');
}
function Menu_Apply(){
	$("#main-content").mask("<?php echo __('Loading...','menu'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Menu_Edit').attr('action'),$('#frm_Menu_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		$('#main-content').html(obj.content);
		if (obj.error == 0){
			$("#err_title").html('');
			$("#ename").removeClass("has-error");
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Menu is updated successfully','menu'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		}else{
			$("#ename").addClass("has-error");
		    $("#err_title").html(obj.warning);
	        $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','menu'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		}
		//$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		//$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}

</script>
<script type="text/javascript" src="<?php echo RELATIVE_PATH;?>/media/assets/jquery-nestable/jquery.nestable.js"></script>
<script type="text/javascript" src="<?php echo RELATIVE_PATH;?>/app/plugins/default/menu/admin/assets/js.js"></script>
<?php
require_once abs_plugin_path(__FILE__) . '/menu/admin/assets/js.php';
//$this->js('media/assets/jquery-nestable/jquery.nestable.js');
?>
<style type="text/css">
    section#main-content {position: initial !important;}
</style>
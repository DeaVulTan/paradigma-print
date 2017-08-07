<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<div class="row" style="margin-bottom: 20px; margin-top: -15px;">
    <div class="col-sm-12">
        <h3>
            <?php if (!empty($this->menu_settings[$this->controller->get->{"admin-page"}])){ ?>
                <?php echo '<i class="' . $this->menu_settings[$this->controller->get->{"admin-page"}]['icon'] . '" style="color:'.$this->menu_settings[$this->controller->get->{"admin-page"}]['icon_color'].'"></i>'; ?>
            <?php }else{ ?>
                <?php global $_admin_menu; ?>
                <?php echo (!empty($_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'])) ? '<i class="' . $_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'] . '"></i>' : ''; ?>
            <?php } ?>
            <?php echo __('Pages','page'); ?>  <small><?php echo __('Edit','page'); ?></small>
        </h3>
    </div>
    <div class="hidden" id="messageErrorJS">
        <ul>
            <li class="errorLengthNewURL"><?php echo __('Please enter at least 1 character', 'page'); ?></li>
            <li class="existsURL"><?php echo __('URL already exists. Please use other url', 'page'); ?></li>
            <li class="urlNotEmpty"><?php echo __('URL should not be empty', 'page'); ?></li>
        </ul>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <form name="frm_Page_Edit" id="frm_Page_Edit" class="form-horizontal" role="form" action="<?php echo admin_url('token='.$this->token); ?>" method="post" onsubmit="return false;">

            <div class="panel-body">
                <div class="nav-tabs-custom">
                    <ul class="nav nav-tabs">
                        <li class="active"><a data-toggle="tab" href="#content-page"><?php echo __('Content', 'page') ?></a></li>
                        <li class=""><a data-toggle="tab" href="#page-visible"><?php echo __('Permission', 'page') ?></a></li>
                        <li class=""><a data-toggle="tab" href="#settings"><?php echo __('Background settings', 'page') ?></a></li>
                    </ul>
                    <div class="tab-content">
                        <div id="content-page" class="tab-pane active">
                            <div class="box-body">
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group titleWrap <?php $this->error_class("page_title");?>">
                                            <label for="title" class="col-sm-1 control-label"><?php echo __('Title', 'page'); ?> <span>*</span></label>
                                            <div class="col-sm-11">
                                                <?php
                                                    echo form_input("page_title");
                                                    echo form_input(array('name' => 'page_id', 'type' => 'hidden', 'value' => $this->controller->post->id));
                                                    $page_visible = json_decode($this->controller->post->page_visible, true);
                                                    echo form_input(array('type' => 'hidden', 'id' => 'visible_users_data', 'value' => !empty($page_visible['users']) ? json_encode($page_visible['users']) : '', 'name' => 'visible_users_data'));
                                                    $this->error_message("page_title");
                                                ?>
                                            </div>                        
                                        </div>
                                        <div class="form-group permalinkWrap" data-method="">
                                            <label for="permalink" class="col-sm-1 control-label"></label>
                                            <div class="col-md-11 col-md-offset-1">
                                                <div class="defaultURL pull-left">
                                                    <strong><?php echo __('URL', 'page'); ?>:</strong> 
                                                    <span id="url_default"><?php echo site_url() . RELATIVE_PATH . '/'; ?></span>
                                                </div>
                                                <div class="customSlug pull-left">
                                                    <div class=" pull-left editPageName" id="editPageName">
                                                    </div>
                                                    <div class="groupAction pull-left">
                                                        <button class="btn btn-xs btn-warning" type="button" name="edit"><?php echo __('Edit URL', 'page'); ?></button>
                                                        <button class="btn btn-xs btn-primary" type="button" name="save"><?php echo __('Save', 'page'); ?></button>
                                                        <button class="btn btn-xs btn-default" type="button" name="cancel"><?php echo __('Cancel', 'page'); ?></button>
                                                        <a id="a_target" class="btn btn-xs btn-info" href="" style="color:#FFF;" target="_blank"><?php echo __('Go to URL', 'page'); ?></a>
                                                    </div>
                                                </div>
                                            </div>
                                            <?php echo form_input(array('name' => 'url', 'type' => 'hidden', 'id' => 'slug', 'value' => $this->controller->post->page_url)); ?>
                                        </div>
                                    </div>
                                </div><!--end title-->
                                <div class="row">
                                    <div class="col-md-6">
                                        <div class="form-group <?php $this->error_class("page_layout");?>">
                                            <label for="layout" class="col-sm-2 control-label"><?php echo __('Layout', 'page'); ?> <span>*</span></label>
                                            <div class="col-sm-10">
                                                <?php
                                                    $page_layout_select = array();
                                                    $page_layout_select = $this->controller->page_model->get_layout_pages();
                                                    echo form_dropdown("page_layout",$page_layout_select,array());
                                                ?>
                                                <?php $this->error_message("page_layout")?>
                                            </div>
                                        </div>
                                        <div class="form-group">
                                            <label for="status" class="col-sm-2 control-label"><?php echo __('Status', 'page'); ?></label>
                                            <div class="col-sm-10">
                                                <?php
                                                    $page_status_select = array();
                                                    $page_status_select = $this->controller->page_model->get_status_pages();
                                                    echo form_dropdown('page_status', $page_status_select, array());
                                                ?>
                                            </div>
                                        </div>
    
                                        <div class="form-group <?php $this->error_class("page_type");?>">
                                            <label for="type" class="col-sm-2 control-label"><?php echo __('Page Type', 'page'); ?></label>
                                            <div class="col-sm-10">
                                                <?php
                                                    $page_type_select = array();
                                                    $page_type_select = $this->controller->page_model->get_page_type();
                                                    echo form_dropdown("page_type",$page_type_select, array());
                                                ?>
                                                <?php $this->error_message("page_type")?>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="form-group <?php $this->error_class("page_meta_title");?>">
                                            <label for="meta_title" class="col-sm-2 control-label"><?php echo __('Meta title', 'page'); ?></label>
                                            <div class="col-sm-10">
                                                <?php echo form_input("page_meta_title");?>
                                                <?php $this->error_message("page_meta_title")?>
                                            </div>
                                        </div>
                                        <div class="form-group <?php $this->error_class("page_meta_keywords");?>">
                                            <label for="meta_keywords" class="col-sm-2 control-label"><?php echo __('Meta keywords', 'page'); ?></label>
                                            <div class="col-sm-10">
                                                <?php echo form_input("page_meta_keywords");?>
                                                <?php $this->error_message("page_meta_keywords")?>
                                            </div>
                                        </div>
                                        <div class="form-group <?php $this->error_class("page_meta_description");?>">
                                            <label for="meta_description" class="col-sm-2 control-label"><?php echo __('Meta description', 'page'); ?></label>
                                            <div class="col-sm-10">
                                                <?php echo form_textarea("page_meta_description");?>
                                                <?php $this->error_message("page_meta_description")?>
                                            </div>
                                        </div>
                                    </div>
                                </div><!--end control-->
                                <div class="row">
                                    <div class="col-md-12">
                                        <div class="form-group <?php $this->error_class("page_content");?>">
                                            <label for="Content" class="col-sm-1 control-label"><?php echo __('Content', 'page'); ?> <span>*</span></label>
                                            <div class="col-sm-11">
                                                <?php echo form_editor("page_content");?>
                                                <?php $this->error_message("page_content")?>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div id="page-visible" class="tab-pane tab-content">
                            <div class="form-group">
                                <label for="type" class="col-sm-2 control-label"><?php echo __('Groups', 'page'); ?></label>
                                <div class="col-sm-10 visible-group">
                                    <?php
                                        $visible_group_selected = $page_visible['groups'];
                                        $check = !empty($visible_group_selected) && in_array(0, $visible_group_selected) ? 'checked' : '';
                                        echo form_checkbox('visible_group[]', 0, null, $check . ' class="all"') . "<span class='span'>" . __('All', 'page') . "</span>";
                                        
                                        $roles = $this->controller->page_model->get_list_role();
                                        foreach ($roles as $role) {
                                            $check = !empty($visible_group_selected) && in_array($role['id'], $visible_group_selected) ? 'checked' : '';
                                            echo form_checkbox('visible_group[]', $role['id'], null, $check . ' class="one"') . "<span class='span'>" . $role['role_name'] . "</span>";
                                        }
                                    ?>
                                </div>
                            </div>
    
                            <div class="form-group">
                                <label for="type" class="col-sm-2 control-label"><?php echo __('Users', 'page'); ?></label>
                                <div class="col-sm-10">
                                    <div id="visibleUsers">
                                    </div>
                                    
                                    <script type="text/javascript">
                                        var ms = $('#visibleUsers').magicSuggest({
                                          valueField: 'name',
                                          data: [<?php echo $this->controller->page_model->get_list_user();?>],
                                         });
                                         $(ms).on(
                                          'selectionchange', function(e, cb, s){
                                              $("#visible_users_data").val(cb.getValue());
                                          }
                                        );
                                    </script>
                                </div>
                            </div>
                        </div>
                        <div id="settings" class="tab-pane tab-content">
                            <?php
                                $background_data = json_decode($this->controller->post->page_theme_options, true);
                            ?>
                            <table class="table table-bordered table-configuration">
                                <col class="col-md-2"/>
                                <tr>
                                    <?php
                                    echo theme_background('background', get_value_input('background', isset($background_data['data']['type'])) ? $background_data['data']['type'] : '');
                                    ?>
                                </tr>
                                <tbody class="tr-background-color">
                                    <tr class="tr-background-color">
                                        <th>
                                            <label class="control-label" for="background-color"><?php echo __('Background color', 'page'); ?></label>
                                        </th>
                                        <td>
                                            <?php
                                            echo theme_input_color('background-color', get_value_input('background-color', isset($background_data['data']['color'])) ? $background_data['data']['color'] : '');
                                            ?>
                                        </td>
                                    </tr>
                                    <tr class="tr-background-color">
                                        <th>
                                            <label class="control-label" for="wrapper-background-color"><?php echo __('Wrapper background color', 'page'); ?></label>
                                        </th>
                                        <td>
                                            <?php
                                            echo theme_input_color('wrapper-background-color', get_value_input('wrapper-background-color', isset($background_data['wrapper_background'])) ? $background_data['wrapper_background'] : '');
                                            ?>
                                        </td>
                                    </tr>
                                </tbody>
                                <tfoot class="tr-background-image">
                                    <tr class="tr-background-image">
                                        <th>
                                            <label class="control-label" for="background-image"><?php echo __('Background images', 'page'); ?></label>
                                        </th>
                                        <td>
                                            <?php echo theme_input_get_image('background-image', get_value_input('background-image', isset($background_data['data']['image'])) ? $background_data['data']['image'] : ''); ?>
                                        </td>
                                    </tr>
                                    <tr class="tr-background-image">
                                        <?php echo theme_background_position('background-position', get_value_input('background-position', isset($background_data['data']['position'])) ? $background_data['data']['position'] : ''); ?>
                                    </tr>
                                    <tr class="tr-background-image">
                                        <?php echo theme_background_repeat('background-repeat', get_value_input('background-repeat', isset($background_data['data']['repeat'])) ? $background_data['data']['repeat'] : ''); ?>
                                    </tr>
                                    <tr class="tr-background-image">
                                        <?php echo theme_background_attachment('background-attachment', get_value_input('background-attachment', isset($background_data['data']['attachment'])) ? $background_data['data']['attachment'] : ''); ?>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <?php echo Pf::event()->trigger("filter","page-form"); ?>
            <?php echo Pf::event()->trigger("filter","page-editing-form"); ?>
        </form>
        
        <div class="controlClone hidden">
            <input type="text" class="form-control input-sm" id="tempInput" />
        </div>
    </div>
</div>

<script type="text/javascript" src="<?php echo RELATIVE_PATH;?>/media/assets/magicsuggest/js/magicsuggest-min.js"></script>
<script type="text/javascript" src="<?php echo RELATIVE_PATH;?>/lib/common/plugin/assets/base.js?t=95"></script>
<script type="text/javascript" src="<?php echo RELATIVE_PATH;?>/app/plugins/default/page/admin/assets/theme-option.js"></script>
<script type="text/javascript" src="<?php echo RELATIVE_PATH;?>/app/plugins/default/page/admin/assets/page.js"></script>
<script>
$(document).ready(function(){
	$('.btn-index').css({display:'none'});
	$('.btn-edit').css({display:'inline-block'});
	$('.btn-copy').css({display:'none'});
	$('.btn-add').css({display:'none'});
	$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
	$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	$('#main-content').removeClass('masked');
	$('#main-content').removeClass('masked-relative');
});

function back_to_list(){
	$("#main-content").mask("<?php echo __('Loading...','page'); ?>");
	try{
       tinymce.remove();
    }catch(e){}
	$('#main-content').load('<?php echo admin_url($this->action.'=index&token='); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}
function Page_Edit(){
	$("#main-content").mask("<?php echo __('Loading...','page'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Page_Edit').attr('action'),$('#frm_Page_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','page'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if (obj.error_url > 0){
			base.notification('error', base.getMessage('existsURL'));
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		    return false;
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Page is updated successfully','page'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
	},'json');
}

function Page_Apply(){
	$("#main-content").mask("<?php echo __('Loading...','page'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Page_Edit').attr('action'),$('#frm_Page_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		
		if (obj.error == 1){
			$.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','page'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
		}else if (obj.error_url > 0){
			base.notification('error', base.getMessage('existsURL'));
			$('#main-content').html(obj.content);
		    //return false;
		}else{
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Page is updated successfully','page'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}

//show_hidden_logo();

show_hidden_background();
show_hidden_color();

$('input.theme-bg-option').on('ifToggled', function () {
    show_hidden_background();
});
$('input[name="color"]').on('ifToggled', function () {
    show_hidden_color();
});

</script>
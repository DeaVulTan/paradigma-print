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
            <?php echo __('User','user'); ?>  <small><?php echo __('Edit','user'); ?></small>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <form name="frm_User_Edit" id="frm_User_Edit" class="form-horizontal" role="form" action="<?php echo admin_url('token='.$this->token); ?>" method="post" onsubmit="return false;">
            
            <div class="form-group <?php $this->error_class("user_email");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Email","user"); ?>
                     <span style="color: red;">*</span>
                </label>
                <div class="col-sm-10">
                    <?php echo form_input("user_email");?>
                    <?php $this->error_message("user_email")?>
                </div>
            </div>

            <div class="form-group <?php $this->error_class("user_name");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Username","user"); ?>
                     <span style="color: red;">*</span>
                </label>
                <div class="col-sm-10">
                    <?php echo form_input(array("name" =>"user_name","readonly"=>"readonly"));?>
                    <?php $this->error_message("user_name")?>
                </div>
            </div>
             <div class="form-group ">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Last Login","user"); ?>
                </label>
                <div class="col-sm-10">
                    <?php echo form_input(array("name"=>"user_login_time","readonly"=>"readonly"));?>
                </div>
            </div>
             <div class="form-group">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Last login IP","user"); ?>
                </label>
                <div class="col-sm-10">
                    <?php echo form_input(array("name"=>"user_login_ip","readonly"=>"readonly"));?>
                </div>
            </div>
            <div class="form-group <?php $this->error_class("user_password");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Password","user"); ?>
                     <span style="color: red;">*</span>
                </label>
                <div class="col-sm-10">
                    <?php echo form_password('user_password');?>
                    <?php $this->error_message("user_password")?>
                </div>
            </div>
             <div class="form-group <?php $this->error_class("repassword");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Re-enter password","user"); ?>
                     <span style="color: red;">*</span>
                </label>
                <div class="col-sm-10">
                    <?php echo form_password('repassword');?>
                    <?php $this->error_message("repassword");?>
                </div>
            </div>
            <div class="form-group <?php $this->error_class("user_firstname");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("First Name","user"); ?>
                </label>
                <div class="col-sm-10">
                    <?php echo form_input("user_firstname");?>
                    <?php $this->error_message("user_firstname")?>
                </div>
            </div>
            <div class="form-group <?php $this->error_class("user_lastname");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Last Name","user"); ?>
                     
                </label>
                <div class="col-sm-10">
                    <?php echo form_input("user_lastname");?>
                    <?php $this->error_message("user_lastname")?>
                </div>
            </div>
            <?php
               $list_fields = get_option('user_custom_fields');
               if(!empty($list_fields)){
               foreach($list_fields as $fields){
            ?>
             <div class="form-group <?php $this->error_class($fields['name']);?>">
                <label for="subject" class="col-sm-2 control-label">
                  <?php echo $fields['label'];echo  $fields['require']==1?"<span style='color:red'>*</span>":"";?>
                </label>
                <div class="col-sm-10">
                    <?php 
                        $custom_fields = unserialize($this->controller->post->user_custom_fields);
                    ?>
                    <input type="<?php echo $fields['type']; ?>" name="custom[<?php echo $fields['name']; ?>]" value="<?php if(!empty($_POST['custom'][$fields['name']])) echo $_POST['custom'][$fields['name']]; else echo isset($custom_fields[$fields['name']])? $custom_fields[$fields['name']]:''; ?>" class="<?php echo $fields['require']==1?"field_require":"";?> form-control">
                    <?php $this->error_message($fields['name'])?>
                </div>
             </div>
            <?php }}?>
            <div class="form-group <?php $this->error_class("user_role");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Role","user"); ?>
                </label>
                <div class="col-sm-10">
                     <select class="form-control" name="role" >
                        <?php foreach ($this->controller->role_model->list_role() as $item){?>
                            <option value="<?php echo $item['id']?>" <?php if($item['id'] == $this->controller->post->user_role){echo 'selected = ""';}?>><?php echo $item['role_name'];?></option>
                        <?php }?>
                    </select>
                    <?php $this->error_message("user_role")?>
                </div>
            </div>

            <div class="form-group <?php $this->error_class("user_activation");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Active","user"); ?>
                </label>
                <div class="col-sm-10">
                   <?php
                        $checked    =   isset($checked)?$checked:true;
                    ?>
                    <label class="checkbox-inline" style="padding-left:0px;">
                        <?php echo form_checkbox('user_activation','1',$checked); ?>
                    </label>
                    <?php $this->error_message("user_activation")?>
                </div>
            </div>

            <div class="form-group <?php $this->error_class("public_profile");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Public Profile","user"); ?>
                </label>
                <div class="col-sm-10">
                    <label class="checkbox-inline" style="padding-left:0px;">
                        <?php echo form_checkbox('public_profile','1',$checked); ?>
                    </label>
                    <?php $this->error_message("public_profile")?>
                </div>
            </div>

            <?php echo Pf::event()->trigger("filter","user-form"); ?>
            <?php echo Pf::event()->trigger("filter","user-editing-form"); ?>
        </form>
    </div>
</div>
<script>
$(document).ready(function(){
	$('.btn-index').css({display:'none'});
	$('.btn-edit').css({display:'inline-block'});
	$('.btn-copy').css({display:'none'});
	$('.btn-add').css({display:'none'});
	$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
});

function back_to_list(){
	$("#main-content").mask("<?php echo __('Loading...','user'); ?>");
	try{
       tinymce.remove();
    }catch(e){}
	$('#main-content').load('<?php echo admin_url($this->action.'=index&token='); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}
function User_Edit(){
	$("#main-content").mask("<?php echo __('Loading...','user'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_User_Edit').attr('action'),$('#frm_User_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','user'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('User is updated successfully','usernews'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
	},'json');
}

function User_Apply(){
	$("#main-content").mask("<?php echo __('Loading...','user'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_User_Edit').attr('action'),$('#frm_User_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		$('#main-content').html(obj.content);
		if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('User is updated successfully','user'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		}else{
	        $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','user'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}
</script>
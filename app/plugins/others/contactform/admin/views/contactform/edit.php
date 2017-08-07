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
            <?php echo __('Contact Forms','contactform'); ?>  <small><?php echo __('Edit','contactform'); ?></small>
		</h3>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<form name="frm_contactform" id="frm_contactform" class="form-horizontal" role="form" action="<?php echo admin_url(); ?>" method="post"onsubmit="return false;">
			<div class="form-group <?php $this->error_class("title");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Title","contactform"); ?>
                     <span style="color: red;">*</span>
                </label>
                <div class="col-sm-10">
                    <?php echo form_input("title");?>
                    <?php $this->error_message("title")?>
                </div>
            </div>
            <div class="form-group <?php $this->error_class("form");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Form","contactform"); ?>
                     <span style="color: red;">*</span>
                </label>
                <div class="col-sm-10">
                   <ul class="list-unstyled list-inline btnAddLists">
                        <li title="Input text" data-toggle="tooltip">
                            <button class="btn btn-custom-control btn-xs" type="button" data-group="Input" data-type="text" data-toggle="modal" data-target=".addItemControl">
                                <i class="fa fa-text-width"></i>
                            </button>
                        </li>
                        <li title="Email" data-toggle="tooltip">
                            <button class="btn btn-custom-control btn-xs" type="button" data-group="Input" data-type="email" data-toggle="modal" data-target=".addItemControl">
                                <i class="fa fa-envelope-o"></i>
                            </button>
                        </li>
                        <li title="URL" data-toggle="tooltip">
                            <button class="btn btn-custom-control btn-xs" type="button" data-group="Input" data-type="url" data-toggle="modal" data-target=".addItemControl">
                                <i class="fa fa-link"></i>
                            </button>
                        </li>
                        <li title="Number" data-toggle="tooltip">
                            <button class="btn btn-custom-control btn-xs" type="button" data-group="Input" data-type="number" data-toggle="modal" data-target=".addItemControl">
                                <i class="fa fa-sort-numeric-asc"></i>
                            </button>
                        </li>
                        <li title="Calendar" data-toggle="tooltip">
                            <button class="btn btn-custom-control btn-xs" type="button" data-group="Input" data-type="date" data-toggle="modal" data-target=".addItemControl">
                                <i class="fa fa-calendar"></i>
                            </button>
                        </li>
                        <li title="Textarea" data-toggle="tooltip">
                            <button class="btn btn-custom-control btn-xs" type="button" data-group="Textarea" data-type="textarea" data-toggle="modal" data-target=".addItemControl">
                                <i class="fa fa-file-text-o"></i>
                            </button>
                        </li>
                        <li title="Checkbox" data-toggle="tooltip">
                            <button class="btn btn-custom-control btn-xs" type="button" data-group="Select" data-type="checkbox" data-toggle="modal" data-target=".addItemControl">
                                <i class="fa fa-check-square-o"></i>
                            </button>
                        </li>
                        <li title="Radio" data-toggle="tooltip">
                            <button class="btn btn-custom-control btn-xs" type="button" data-group="Select" data-type="radio" data-toggle="modal" data-target=".addItemControl">
                                <i class="fa fa-check-circle-o"></i>
                            </button>
                        </li>
                        <li title="Dropdown" data-toggle="tooltip">
                            <button class="btn btn-custom-control btn-xs" type="button" data-group="Select" data-type="dropdown" data-toggle="modal" data-target=".addItemControl">
                                <i class="fa fa-list"></i>
                            </button>
                        </li>
                        <li title="Acceptance" data-toggle="tooltip">
                            <button class="btn btn-custom-control btn-xs" type="button" data-group="Acceptance" data-type="apceectance" data-toggle="modal" data-target=".addItemControl">
                                <i class="fa fa-legal"></i>
                            </button>
                        </li>
                        <li title="Captcha" data-toggle="tooltip">
                            <button class="btn btn-custom-control btn-xs" type="button" data-group="Captcha" data-type="captcha" data-toggle="modal" data-target=".addItemControl">
                                <i class="fa fa-shield"></i>
                            </button>
                        </li>
                        <li title="Button" data-toggle="tooltip">
                            <button class="btn btn-custom-control btn-xs" type="button" data-group="Button" data-type="submit" data-toggle="modal" data-target=".addItemControl">
                                <?php echo __('Button', 'contactform') ?>
                            </button>
                        </li>
                    </ul>
                     <?php
                        echo form_textarea(array('name' => 'form', 'class' => 'form-control', 'id' => 'form', 'tabindex' => 3));
                        echo $this->error_message("form");
                     ?>
                </div>
            </div>
            <div class="form-group" >
                <div class="col-sm-6" >
                    <div class="form-group">
                        <label for="subject" class="col-sm-4 control-label">
                        </label>
                        <div class="col-sm-8">
                           <label><input type="radio" name="recipient" value="1" id="recipient" <?php if(!isset($this->controller->post->recipient) || $this->controller->post->recipient== 1){echo 'checked="checked"';} ?> /> <?php echo __('Single Recipient', 'contactform'); ?></label>  &nbsp; 
                           <label><input type="radio" name="recipient" value="0" id="recipient" <?php if(isset($this->controller->post->recipient) && $this->controller->post->recipient == 0){echo 'checked="checked"';} ?> /> <?php echo __('List of Recipient', 'contactform'); ?></label>
                        </div>
                    </div>
                     <div class="form-group <?php $this->error_class("to");?>" id="single_recipient_page">
    	                <label for="subject" class="col-sm-4 control-label">
    	                    <?php echo __("To","contactform"); ?>
    	                     <span style="color: red;">*</span>
    	                </label>
    	                <div class="col-sm-8" >
    	                    <?php
    	                        echo form_input(array('name' => 'to', 'class' => 'form-control',  'placeholder' => __('Receiver Email', 'contactform')));
    	                    ?>
    	                    <?php $this->error_message("to")?>
    	                </div>
    	            </div>
    	            <div class="form-group" id="list_of_recipient_page" <?php $this->error_class("to_list");?>>
    	                 <label for="subject" class="col-sm-4 control-label">
    	                    <?php echo __("To","contactform"); ?>
    	                     <span style="color: red;">*</span>
    	                </label>
    	                <div class="col-sm-8">
    	                     <?php
                               echo form_textarea(array('name' => 'to_list', 'class' => 'form-control', 'rows' => 8, 'tabindex' => 11, 'placeholder' => 'Enter Email addresses here, one Email per line.'), (''));
                            ?>
                            <?php $this->error_message("to_list")?>
    	                </div>
    	            </div>
    	            <div class="form-group <?php $this->error_class("from");?>">
    	                <label for="subject" class="col-sm-4 control-label">
    	                    <?php echo __("From","contactform"); ?>
    	                     <span style="color: red;">*</span>
    	                </label>
    	                <div class="col-sm-8" >
    	                     <?php
    	                         echo form_input(array('name' => 'from', 'class' => 'form-control', 'tabindex' => 7, 'placeholder' => __('Sender Email', 'contactform')));
    	                     ?>
    	                    <?php $this->error_message("from")?>
    	                </div>
    	            </div>
    	            <div class="form-group <?php $this->error_class("name");?>">
    	                <label for="subject" class="col-sm-4 control-label">
    	                    <?php echo __("Sender Name","contactform"); ?>
    	                </label>
    	                <div class="col-sm-8">
    	                     <?php
    	                          echo form_input(array('name' => 'name', 'class' => 'form-control', 'tabindex' => 8));
    	                     ?>
    	                    <?php $this->error_message("name")?>
    	                </div>
    	            </div>
    	            <div class="form-group <?php $this->error_class("subject");?>">
    	                <label for="subject" class="col-sm-4 control-label">
    	                    <?php echo __("Subject","contactform"); ?>
    	                </label>
    	                <div class="col-sm-8"  >
    	                     <?php
    	                         echo form_input(array('name' => 'subject', 'class' => 'form-control', 'tabindex' => 9));
    	                     ?>
    	                    <?php $this->error_message("subject")?>
    	                </div>
    	            </div>
    	            <div class="form-group">
    	               <label for="subject" class="col-sm-4 control-label"></label>
    	                <div class="col-sm-8" >
    	                      <?php
                                 echo form_checkbox(array('name' => 'use_as_html', 'value' => 1, 'tabindex' => 10));
                                 echo __(' Use HTML content type', 'contactform')
                              ?>
    	                </div>
    	            </div>
                </div>
                <div class="col-sm-6 form-group <?php $this->error_class("message");?>" >
                     <label for="subject" class="col-sm-4 control-label">
    	                  <?php echo __("Message","contactform"); ?>
    	                  <span style="color: red;">*</span>
    	             </label>
    	             <div class="col-sm-8" style="padding-right:0px">
    	                  <?php
                              echo form_textarea(array('name' => 'message', 'class' => 'form-control', 'rows' => 10, 'tabindex' => 11));
                              echo $this->error_message("name");
                          ?>
    	             </div>
                </div>
            </div>
            <div class="form-group" >
    	        <label for="subject" class="col-sm-2 control-label">
    	             <?php echo __("Sending successfully notification","contactform"); ?>
    	             <span style="color: red;">*</span>
    	        </label>
    	        <div class="col-sm-10" >
    	             <?php
                         echo form_textarea(array('name' => 'notify', 'class' => 'form-control', 'rows' => 3, 'tabindex' => 12));
                         echo  $this->error_message("notify");
                     ?>
    	         </div>
    	    </div>
    	    <div class="form-group >">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Status","contactform"); ?>
                </label>
                <div class="col-sm-10">
                    <?php
                        $checked    =   isset($checked)?$checked:true;
                    ?>
                    <label class="checkbox-inline" style="padding-left:0px;">
                        <?php echo form_checkbox('status','1',$checked); ?>
                        <?php echo __('Published','contactform'); ?>
                    </label>
                </div>
            </div>
		</form>
	</div>
</div>
<div class="modal fade addItemControl" tabindex="-1" role="dialog" aria-labelledby="myItemControl" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-body">
                <form class="form-horizontal showItem" role="form">

                </form>
                <div class="modal-footer">
                    <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close', 'contactform'); ?></button>
                    <button type="button" class="btn btn-primary" id="btnAddItem"><?php echo __('Add', 'contactform'); ?></button>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="hidden" id="messageErrorJS">
    <ul>
        <li class="errorLengthName"><?php echo __('Name must be between 1 and 32 characters', 'contactform'); ?></li>
    </ul>
</div>
<script type="text/javascript" src="<?php echo site_url().RELATIVE_PATH."/app/plugins/others/contactform/assets/contactform.js"?>"></script>
<script id="controlGroupInput" type="text/x-handlebars-template">
    <div class="controlItem" data-type="{{type}}">
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Required', 'contactform'); ?></label>
    <div class="col-sm-10">
    <label class="checkbox-inline">
    <input type="radio" value="0" name="required" checked="checked"> <?php echo __('No', 'contactform'); ?> 
    </label>
    <label class="checkbox-inline">
    <input type="radio" value="1" name="required"> <?php echo __('Yes', 'contactform'); ?>
    </label>
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Name', 'contactform'); ?> <span>*</span></label>
    <div class="col-sm-10">
    <input type="text" name="name" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Default value', 'contactform'); ?></label>
    <div class="col-sm-10">
    <input type="text" name="value" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Place holder', 'contactform'); ?></label>
    <div class="col-sm-10">
    <input type="text" name="placeholder" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Size', 'contactform'); ?></label>
    <div class="col-sm-10">
    <input type="text" name="size" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Max length', 'contactform'); ?></label>
    <div class="col-sm-10">
    <input type="text" name="maxlength" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Class', 'contactform'); ?></label>
    <div class="col-sm-10">
    <input type="text" name="class" class="form-control">
    </div>
    </div>
    </div>
</script>


<script id="controlGroupTextarea" type="text/x-handlebars-template">
    <div class="controlItem" data-type="textarea">
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Required','contactform'); ?></label>
    <div class="col-sm-10">
    <label class="checkbox-inline">
    <input type="radio" value="0" name="required" checked="checked"> <?php echo __('No','contactform'); ?> 
    </label>
    <label class="checkbox-inline">
    <input type="radio" value="1" name="required"> <?php echo __('Yes','contactform'); ?>
    </label>
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Name','contactform') ?> <span>*</span></label>
    <div class="col-sm-10">
    <input type="text" name="name" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Default value','contactform') ?></label>
    <div class="col-sm-10">
    <input type="text" name="value" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Place holder','contactform') ?></label>
    <div class="col-sm-10">
    <input type="text" name="placeholder" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Rows','contactform') ?></label>
    <div class="col-sm-10">
    <input type="text" name="rows" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Columns','contactform') ?></label>
    <div class="col-sm-10">
    <input type="text" name="columns" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Size','contactform') ?></label>
    <div class="col-sm-10">
    <input type="text" name="size" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Max length','contactform') ?></label>
    <div class="col-sm-10">
    <input type="text" name="maxlength" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label">ID</label>
    <div class="col-sm-10">
    <input type="text" name="id" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label">Class</label>
    <div class="col-sm-10">
    <input type="text" name="class" class="form-control">
    </div>
    </div>
    </div>
</script>

<script id="controlGroupSelect" type="text/x-handlebars-template">
    <div class="controlItem" data-type="{{type}}">
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Required','contactform') ?></label>
    <div class="col-sm-10">
    <label class="checkbox-inline">
    <input type="radio" value="0" name="required" checked="checked"> <?php echo __('No','contactform') ?>
    </label>
    <label class="checkbox-inline">
    <input type="radio" value="1" name="required"> <?php echo __('Yes','contactform') ?>
    </label>
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Name','contactform') ?> <span>*</span></label>
    <div class="col-sm-10">
    <input type="text" name="name" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Choice','contactform') ?></label>
    <div class="col-sm-10">
    <textarea type="text" name="items" class="form-control"></textarea>
    <p class="help-block choice"><?php echo __('One value per line','contactform') ?></p>
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label">ID</label>
    <div class="col-sm-10">
    <input type="text" name="id" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label">Class</label>
    <div class="col-sm-10">
    <input type="text" name="class" class="form-control">
    </div>
    </div>
    </div>
</script>


<script id="controlGroupButton" type="text/x-handlebars-template">
    <div class="controlItem" data-type="button">
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Name','contactform') ?> <span>*</span></label>
    <div class="col-sm-10">
    <input type="text" name="name" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Value','contactform') ?></label>
    <div class="col-sm-10">
    <input type="text" name="value" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label"><?php echo __('Type','contactform') ?></label>
    <div class="col-sm-10">
    <select name="type" class="form-control">
    <option value="submit">Submit</option>
    <option value="reset">Reset</option>
    <option value="button">Button</option>
    </select>
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label">ID</label>
    <div class="col-sm-10">
    <input type="text" name="id" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label">Class</label>
    <div class="col-sm-10">
    <input type="text" name="class" class="form-control">
    </div>
    </div>
    </div>
</script>

<script id="controlGroupCaptcha" type="text/x-handlebars-template">
    <div class="controlItem" data-type="captcha">
    <div class="form-group">
    <label for="" class="col-sm-2 control-label">ID</label>
    <div class="col-sm-10">
    <input type="text" name="id" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label">Class</label>
    <div class="col-sm-10">
    <input type="text" name="class" class="form-control">
    </div>
    </div>
    </div>
</script>

<script id="controlGroupAcceptance" type="text/x-handlebars-template">
    <div class="controlItem" data-type="acceptance">
    <div class="form-group">
    <label for="" class="col-sm-2 control-label">Label</label>
    <div class="col-sm-10">
    <input type="text" name="label" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label">ID</label>
    <div class="col-sm-10">
    <input type="text" name="id" class="form-control">
    </div>
    </div>
    <div class="form-group">
    <label for="" class="col-sm-2 control-label">Class</label>
    <div class="col-sm-10">
    <input type="text" name="class" class="form-control">
    </div>
    </div>
    </div>
</script>
<script>
$(function () {
    $('[data-toggle="tooltip"]').tooltip()
});
$(document).ready(function(){
	$('.btn-index').css({display:'none'});
	$('.btn-edit').css({display:'inline-block'});
	$('.btn-copy').css({display:'none'});
	$('.btn-add').css({display:'none'});
	//$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
	//$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
});

function back_to_list(){
	$("#main-content").mask("<?php echo __('Loading...','contactform'); ?>");
	try{
       tinymce.remove();
    }catch(e){}
	$('#main-content').load('<?php echo admin_url($this->action.'=index&token='); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}
function contactform_Edit(){
	$("#main-content").mask("<?php echo __('Loading...','contactform'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_contactform').attr('action'),$('#frm_contactform').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','contactform'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Contact Forms is updated successfully','contactform'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
	},'json');
}

function contactform_Apply(){
	$("#main-content").mask("<?php echo __('Loading...','contactform'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_contactform').attr('action'),$('#frm_contactform').serialize(),function(obj){
		$("#main-content").unmask();
		$('#main-content').html(obj.content);
		if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Contact Forms is updated successfully','contactform'); ?>", html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		}else{
	        $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','contactform'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}
</script>
<script type="text/javascript">
    $(document).ready(function () {
        $('input[name="recipient"]').on('ifClicked', function () {
            if (+$(this).val() === 1) {
                $('#single_recipient_page').show();
                $('#list_of_recipient_page').hide();
            } else {
                $('#single_recipient_page').hide();
                $('#list_of_recipient_page').show();
            }
        });
        if (+$('input[name="recipient"]:checked').val() === 1) {
            $('#single_recipient_page').show();
            $('#list_of_recipient_page').hide();
        } else {
        	
            $('#single_recipient_page').hide();
            $('#list_of_recipient_page').show();
        }
    });
</script>
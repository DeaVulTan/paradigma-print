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
            Polls  <small><?php echo __('Add','polls'); ?></small>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <form name="frm_Polls_New" id="frm_Polls_New" class="form-horizontal" role="form" action="<?php echo admin_url(); ?>" method="post" onsubmit="return false;">
            
            <div class="form-group <?php $this->error_class("polls_question");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Question","polls"); ?>
                     <span style="color: red;">*</span>
                </label>
                <div class="col-sm-10">
                    <?php echo form_input("polls_question");?>
                    <?php $this->error_message("polls_question")?>
                </div>
            </div>

            <div class="form-group <?php $this->error_class("polls_pubdate");?> " >
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Publish Date","polls"); ?>
                     
                </label>
               <div class="col-sm-10" >
                    <div class="input-group date" id="publishedDate" data-date-format="YYYY-MM-DD HH:mm:ss">
                        <?php echo form_input("polls_pubdate");?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    <?php $this->error_message("polls_pubdate")?>
                </div>
                <script>
                    $(document).ready(function(){
                        $("#publishedDate").datetimepicker({pickTime: false});
                    });   
                </script>
            </div>
            
            <div class="form-group <?php $this->error_class("polls_unpubdate");?> " >
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Unpublish Date","polls"); ?>
                     
                </label>
               <div class="col-sm-10" >
                    <div class="input-group date" id="unpublishedDate" data-date-format="YYYY-MM-DD HH:mm:ss">
                        <?php echo form_input("polls_unpubdate");?>
                        <span class="input-group-addon">
                            <span class="glyphicon glyphicon-calendar"></span>
                        </span>
                    </div>
                    <?php $this->error_message("polls_unpubdate")?>
                </div>
                <script>
                    $(document).ready(function(){
                        $("#unpublishedDate").datetimepicker({pickTime: false});
                    });   
                </script>
            </div>

            <div id="append">
                 <?php
                    $answer = isset($this->controller->post->answer) ? $this->controller->post->answer : '';
                    $stt = 1;
                    if(is_array($answer)):
                        foreach($answer as $key => $val):
                  ?>
                    <div class="form-group custom-cols">
                        <label for="question" class="col-sm-2 control-label"><?php echo __('Answer ', 'polls'); ?></label>
                        <div class="col-sm-10" style="padding:0px">
                            <div class="col-sm-11"><input type="text" name='answer[]' value="<?php echo $val; ?>" class="form-control"  /></div>
                            <div class="col-sm-1"><a href="#" class="glyphicon glyphicon-remove btn-remove"></a></div>
                        </div>
                    </div>
                    <?php
                        endforeach;
                    endif;
                    ?>
            </div>
             <div class="form-group" id="addMore">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    <div class="form-inline">
                        <button type="button" id='btnAddField' class="btn btn-info" onclick="add_poll_answer_add();"><?php echo __('Add Answer', 'polls'); ?></button>
                    </div>
                </div>
            </div>
            <div class="form-group" >
                <label class="col-sm-2 control-label" style="padding: 0px;"><?php echo __('Multiple Answer?','polls');?></label>
                <div class="col-sm-10" id="check_pollq_multiple">
                    <input type="radio" name="pollq_multiple_yes" class="pollq_multiple_yes" value="1" /> <?php echo __('Yes','polls')?>
                    <input type="radio" name="pollq_multiple_yes" class="pollq_multiple_yes" value="0" checked="checked" /> <?php echo __('No','polls')?>
                </div>
            </div>
            <div class="form-group>">
                <label class="col-sm-2 control-label" style="padding: 0px;"><?php echo __('Number of allowed answer','polls');?>:</label>
                <div class="col-sm-10" style="padding-left: 5px;">
                       <input style="width:50px;" name="pollq_multiple" id="pollq_multiple" disabled="disabled"  class="form-control"></input>
                </div>
            </div>
            <div class="form-group <?php $this->error_class("polls_status");?>">
                <label for="subject" class="col-sm-2 control-label">
                   <?php echo __("Status","polls"); ?>
                     
                </label>
               <div class="col-sm-10">
                    <?php
                        $checked    =   isset($checked)?$checked:true;
                    ?>
                    <label class="checkbox-inline" style="padding-left:0px;">
                        <?php echo form_checkbox('polls_status','1',$checked); ?>
                        <?php echo __('Published','polls'); ?>
                    </label>
                    <?php $this->error_message("polls_status")?>
                </div>
            </div>
            
            <?php echo Pf::event()->trigger("filter","polls-form"); ?>
            <?php echo Pf::event()->trigger("filter","polls-adding-form"); ?>
        </form>
    </div>
</div>
<script>

function add_poll_answer_add(){
    var source   = $("#field-template").html();
    var append = $('#append').append('<div class="form-group custom-cols"> <label for="question" class="col-sm-2 control-label"><?php echo __('Answer ', 'polls'); ?></label><div class="col-sm-10" style="padding:0px">  <div class="col-sm-11"> <?php echo form_input(array('name' => 'answer[]', 'class' => 'form-control'));?></div> <div class="col-sm-1"><a href="#" class="glyphicon glyphicon-remove btn-remove"></a></div></div></div>');
}
$('#append').on('click', '.btn-remove', function(){
    $(this).closest('.custom-cols').remove();
    return false;
});
$(document).ready(function(){
	$('.btn-index').css({display:'none'});
	$('.btn-edit').css({display:'none'});
	$('.btn-copy').css({display:'none'});
	$('.btn-add').css({display:'inline-block'});
	$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
	$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});

	//Check Poll Whether It is Multiple Poll Answer
	$('.pollq_multiple_yes').on('ifChecked', function(event){
		if(($(this).val()) == 1) {
			$('#pollq_multiple').attr('disabled', false);
		} else {
			$('#pollq_multiple').val();
			$('#pollq_multiple').attr('disabled', true);
		}
	});
});
function back_to_list(){
	$("#main-content").mask("<?php echo __('Loading...','polls'); ?>");
	try{
	   tinymce.remove();
	}catch(e){}
	$('#main-content').load('<?php echo admin_url($this->action.'=index&token='); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}
function Polls_New(){
	$("#main-content").mask("<?php echo __('Loading...','polls'); ?>");
	try{
	   tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Polls_New').attr('action'),$('#frm_Polls_New').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','polls'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		  //Check Poll Whether It is Multiple Poll Answer
		    $('.pollq_multiple_yes').on('ifChecked', function(event){
		    	if(($(this).val()) == 1) {
		    		$('#pollq_multiple').attr('disabled', false);
		    	} else {
		    		$('#pollq_multiple').val();
		    		$('#pollq_multiple').attr('disabled', true);
		    	}
		    });
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Poll is added successfully','polls'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
	},'json');
}


</script>

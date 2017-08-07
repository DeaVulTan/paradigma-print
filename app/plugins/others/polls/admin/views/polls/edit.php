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
            Polls  <small><?php echo __('Edit','polls'); ?></small>
        </h3>
    </div>
</div>
<div class="row">
    <div class="col-sm-12">
        <form name="frm_Polls_Edit" id="frm_Polls_Edit" class="form-horizontal" role="form" action="<?php echo admin_url('token='.$this->token); ?>" method="post" onsubmit="return false;">
            
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
            <div id="ansewer" >
               <?php if (!empty($this->controller->post->get_answers) && is_array($this->controller->post->get_answers)) {
                    foreach ($this->controller->post->get_answers as $field) {
                        if(!(empty($field['pollsa_answers']))) {
                            ?>
                           <div class="form-group custom-cols-<?php echo $field['id']?>">
                                <label for="answer" class="col-sm-2 control-label"><?php echo __('Answer ', 'polls'); ?></label>
                                <div class="col-sm-10" style="padding:0px">
                                    <div class="col-sm-9"><input type="text" name='polla_aid_<?php echo $field['id']?>' value="<?php echo $field['pollsa_answers']; ?>" class="form-control"  /></div>
                                    <label class="col-sm-1"><?php echo __('Voted', 'polls'); ?>:</label>
                                    <div class="col-sm-1"><input type="text" readonly = "readonly" value="<?php echo $field['pollsa_vote']; ?>" class="form-control"  /></div>
                                    <div class="col-sm-1"><a href="#" class="glyphicon glyphicon-remove btn-remove" onclick="delete_ansewer(<?php echo $field['id'];?>)"></a></div>
                                </div>
                            </div>
                        <?php
                        }
                    }
                }
                ?>
            </div>
            <div id="append">
            </div>
            <div class="form-group" id="addMore">
                <label class="col-sm-2 control-label"></label>
                <div class="col-sm-10">
                    <div class="form-inline">
                        <button type="button" id='btnAddField' class="btn btn-info" onclick="add_poll_answer_add();"><?php echo __('Add Answer', 'polls'); ?></button>
                    </div>
                </div>
            </div>
             <div class="form-group" id="addMore">
                <label class="col-sm-2 control-label" style="padding: 0px;"><?php echo __('Multiple Answer?','polls');?></label>
                <div class="col-sm-10">
                    <input type="radio" name="pollq_multiple_yes" class="pollq_multiple_yes" value="1" <?php if($this->controller->post->{'polls_multiple'} > 1){echo 'checked="checked"';}?> /> <?php echo __('Yes','polls')?>
                    <input type="radio" name="pollq_multiple_yes" class="pollq_multiple_yes" value="0" <?php if($this->controller->post->{'polls_multiple'} == 1){echo 'checked="checked"';}?>" /> <?php echo __('No','polls')?>
                </div>
            </div>
            <div class="form-group" id="addMore">
                <label class="col-sm-2 control-label" style="padding: 0px;"><?php echo __('Number of allowed answer','polls');?>:</label>
                <div class="col-sm-10">
                       <input style="width:50px;"  name="pollq_multiple" id="pollq_multiple" <?php if($this->controller->post->{'polls_multiple'} == 1){echo 'disabled="disabled"';}?> value= "<?php echo $this->controller->post->{'polls_multiple'}?>" class="form-control"></input>
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
            <?php echo Pf::event()->trigger("filter","polls-editing-form"); ?>
        </form>
    </div>
</div>
<script>
function add_poll_answer_add(){
    var source   = $("#field-template").html();
    $('#ansewer').append('<div class="form-group custom-cols"> <label for="question" class="col-sm-2 control-label"><?php echo __('Answer ', 'polls'); ?></label><div class="col-sm-10" style="padding:0px">  <div class="col-sm-9"> <?php echo form_input(array('name' => 'answer[]', 'class' => 'form-control'));?></div><label class="col-sm-1">No. Of Votes</label><div class="col-sm-1"><input type="text" readonly = "readonly" value="<?php echo ''; ?>" class="form-control"  /></div> <div class="col-sm-1"><a href="#" class="glyphicon glyphicon-remove btn-remove"></a></div></div></div>');
}
$('#ansewer').on('click', '.btn-remove', function(){
    $(this).closest('.custom-cols').remove();
    return false;
});

$(document).ready(function(){
	$('.btn-index').css({display:'none'});
	$('.btn-edit').css({display:'inline-block'});
	$('.btn-copy').css({display:'none'});
	$('.btn-add').css({display:'none'});
	//$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
	//$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});

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
function Polls_Edit(){
	$("#main-content").mask("<?php echo __('Loading...','polls'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Polls_Edit').attr('action'),$('#frm_Polls_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){
		    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','polls'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Poll is updated successfully','polls'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
		//Check Poll Whether It is Multiple Poll Answer
		$('.pollq_multiple_yes').on('ifChecked', function(event){
			if(($(this).val()) == 1) {
				$('#pollq_multiple').attr('disabled', false);
			} else {
				$('#pollq_multiple').val();
				$('#pollq_multiple').attr('disabled', true);
			}
		});
	},'json');
}

function Polls_Apply(){
	$("#main-content").mask("<?php echo __('Loading...','polls'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_Polls_Edit').attr('action'),$('#frm_Polls_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		$('#main-content').html(obj.content);
		if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Poll is updated successfully','polls'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		}else{
	        $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','polls'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		}
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
	},'json');
}

function delete_ansewer(aid){
	var global_poll_aid = aid;
	$("#main-content").mask("<?php echo __('Loading...','polls'); ?>");
    $('#ansewer').on('click', '.btn-remove', function(){
        $(this).closest('.custom-cols-'+global_poll_aid).remove();
        return false;
    });
    $.post("<?php echo admin_url($this->action.'=delete_ansewer');?>", {action: "del-asewer",id: aid},
          function (data) {
            $("#main-content").unmask();
     });
};

</script>
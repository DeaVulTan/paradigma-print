<?php defined ( 'PF_VERSION' ) or header ( 'Location:404.html' ); ?>
<div class="hidden" id="messageErrorJS">
	<ul>
		<li class="errorNumberAddMore"><?php echo __('You can add maximum 50 questions at a time', 'faq'); ?></li>
		<li class="confirmDelete"><?php echo __('Are you sure to delete this item?', 'faq'); ?></li>
		<li class="validatorTitle"><?php echo __('Title must be in the range of 2 to 255 characters', 'faq'); ?></li>
		<li class="validatorDescription"><?php echo __('Description must be in the range of 1 to 500 characters', 'faq'); ?></li>
	</ul>
</div>
<div class="row" style="margin-bottom: 20px; margin-top: -15px;">
	<div class="col-sm-12">
		<h3>
            <?php if (!empty($this->menu_settings[$this->controller->get->{"admin-page"}])){ ?>
                <?php echo '<i class="' . $this->menu_settings[$this->controller->get->{"admin-page"}]['icon'] . '" style="color:'.$this->menu_settings[$this->controller->get->{"admin-page"}]['icon_color'].'"></i>'; ?>
            <?php }else{ ?>
                <?php global $_admin_menu; ?>
                <?php echo (!empty($_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'])) ? '<i class="' . $_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'] . '"></i>' : ''; ?>
            <?php } ?>
            FAQS  <small><?php echo __('Edit','faq'); ?></small>
		</h3>
	</div>
</div>
<div class="row">
	<div class="col-sm-12">
		<form name="frm_faq_Edit" id="frm_faq_Edit" class="form-horizontal"
			role="form" action="<?php echo admin_url(); ?>" method="post"
			onsubmit="return false;">
			<div class="row pad">
				<div class="col-md-12" id="col-md-12">
					<div
						class="row mar0 pad10-0 form-group <?php $this->error_class("title");?>">
						<div class="col-sm-2 control-label">
							<label for="subject">
                                   <?php echo __("Title","faq"); ?>
                                     <span style="color: red;">*</span>
							</label>
						</div>
						<div class="col-sm-10">
                            <?php
                            echo form_input ("title");
                            echo form_input (array('name' => 'id','type' => 'hidden'));
                            echo form_input ( array (
                                    'name' => 'number_qa',
                                    'type' => 'hidden' 
                            ) );
                            ?>
                            <?php $this->error_message("title")?>
                            <div id="show_error"></div>
                        </div>
					</div>
					<div
						class="row mar0 pad10-0 form-group <?php $this->error_class("status");?>">
						<div class="col-sm-2 control-label">
							<label for="subject">
                               <?php echo __("Status","faq"); ?>
                            </label>
						</div>
						<div class="col-sm-2">
                            <?php
                            $faq_status_select = array ();
                            $faq_status_select = $this->controller->faq_model->get_status_faq ();
                            echo form_dropdown ( 'status', $faq_status_select, array () );
                            ?>
                        </div>
					</div>
					<div
						class="row mar0 pad10-0 form-group <?php $this->error_class("description");?>">
						<div class="col-sm-2 control-label ">
							<label for="subject">
                               <?php echo __("Description","faq"); ?>
							</label>
						</div>
						<div class="col-sm-10">
                            <?php echo form_textarea("description");?>
                            <?php $this->error_message("description")?>
                        </div>
					</div>
					<hr />
					<div id="listQuestion">
                        <?php
                        for($i = 0; $i < $this->controller->post->number_qa; $i ++) :
                            ?>
                         <div class="qaItem">
							<span class="glyphicon glyphicon-remove btnRemoveQA"></span>
							<div class="form-group">
								<label for="question" class="col-sm-2 control-label"><?php echo __('Question', 'faq'); ?></label>
								<div class="col-sm-10">
                                          	<?php
                            echo form_input(array(
                                    'name' => 'question[]',
                                    'class' => 'form-control',
                                    'value' => isset ($this->controller->post->list['question'][$i] ) ? $this->controller->post->list['question'][$i] : '' 
                            ) );
                            ?>
                                       	</div>
							</div>
							<div class="form-group">
								<label for="answer" class="col-sm-2 control-label"><?php echo __('Answer', 'faq'); ?></label>
								<div class="col-sm-10">
                                                <?php
                            echo form_textarea ( array (
                                    'name' => 'answer[]',
                                    'class' => 'form-control',
                                    'rows' => 3 
                            ), isset ($this->controller->post->list['answer'][$i] ) ? $this->controller->post->list['answer'][$i]  : '' );
                            ?>
                                            </div>
							</div>
						</div>
                         <?php endfor; ?>
                     </div>
					<div class="form-group" id="addMore">
						<label class="col-sm-2 control-label"></label>
						<div class="col-sm-10">
							<div class="callout callout-info">
								<h4><?php echo __('Please input number of questions and click Add', 'faq'); ?></h4>
								<p><?php echo __('You can add maximum 50 questions at a time. You can order questions by dragging them', 'faq'); ?></p>
							</div>
							<div class="form-inline">
								<input type="text" class="form-control" id="num_add" value="1">
								<button id="btn-add_number" type="button" class="btn btn-info"><?php echo __('Add', 'faq'); ?></button>
							</div>
						</div>
					</div>
                    <?php echo Pf::event()->trigger("filter","faq-form"); ?>
                    <?php echo Pf::event()->trigger("filter","faq-adding-form"); ?>
                </div>
			</div>
		</form>
	</div>
</div>
<div class="controlClone hidden">
	<div id="qa" class="qaItem qaAppend">
		<span class="glyphicon glyphicon-remove btnRemoveQA"></span>
		<div class="form-group">
			<label for="question" class="col-sm-2 control-label"><?php echo __('Question', 'faq'); ?></label>
			<div class="col-sm-10">
                <?php
                echo form_input ( array (
                        'name' => 'question[]',
                        'class' => 'form-control' 
                ) );
                ?>
            </div>
		</div>
		<div class="form-group">
			<label for="answer" class="col-sm-2 control-label"><?php echo __('Answer', 'faq'); ?></label>
			<div class="col-sm-10">
                <?php
                echo form_textarea ( array (
                        'name' => 'answer[]',
                        'class' => 'form-control',
                        'rows' => 3 
                ) );
                ?>
            </div>
		</div>
	</div>
</div>
<script>
$(document).ready(function(){
    var listQuestion = $("#listQuestion");
    var controlHidden = $('.controlClone');
    var form = $('form');
    var FAQ = function() {
        var addQA = function(input) {
            var number = +input.val();
            if (number > 0 && number <= 50) {
                for (var i = 0; i < number; i++) {
                    var append = controlHidden.find('#qa').clone().removeAttr('id');
                    listQuestion.append(append);
                    append.slideDown(1000);
                }
            } else {
                base.notification('error', base.getMessage('errorNumberAddMore'));
            }
        };

        var removeQA = function(obj) {
            bootbox.confirm(base.getMessage('confirmDelete'), function(result) {
                if (result) {
                    obj.remove();
                }
            });
        };
        
        var createFAQ = function() {
            var number = listQuestion.find('.qaItem').size();
            console.log(number);
            var num_add = $('#num_add').val();
            total = parseInt(number) + parseInt(num_add);
            form.find('#number_qa').val(total);
            form.submit();
        };

        return {
            addQA: addQA,
            removeQA: removeQA,
            createFAQ: createFAQ
        };
    }();

    $('#addMore').on('click', 'button', function() {
        var input = $(this).prev('input[type=text]');
        FAQ.addQA(input);
    });

    listQuestion.on('click', '.btnRemoveQA', function() {
        var item = $(this).closest('.qaItem');
        FAQ.removeQA(item);
        var number = listQuestion.find('.qaItem').size();
        var num_add = $('#num_add').val();
        total = parseInt(number) - parseInt(num_add);
        form.find('#number_qa').val(total);
        form.submit();
    });

    $('#btn-add_number').on('click', function(e) {
        e.preventDefault();
        FAQ.createFAQ();
    });
    listQuestion.sortable();
});
$(document).ready(function(){
	$('.btn-index').css({display:'none'});
	$('.btn-edit').css({display:'inline-block'});
	$('.btn-copy').css({display:'none'});
	$('.btn-add').css({display:'none'});
	$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
	$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
});

function back_to_list(){
	$("#main-content").mask("<?php echo __('Loading...','faq'); ?>");
	try{
       tinymce.remove();
    }catch(e){}
	$('#main-content').load('<?php echo admin_url($this->action.'=index&token='); ?>',function(){
		$("#main-content").unmask();
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	});
}
function faq_Edit(){
	title = $("#title").val();
	if(title == ''){
	    $("#title").parent().parent().addClass("has-error");
	    $("#show_error").html('<span class="help-block"><?php echo __('The field is required','faq') ?></span>');
	    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','faq'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
	    return false;
	}
	$("#main-content").mask("<?php echo __('Loading...','faq'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_faq_Edit').attr('action'),$('#frm_faq_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		if (obj.error == 1){
		    //$.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','faq'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').html(obj.content);
			$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		    $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
		}else if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('FAQ is updated successfully','faq'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
			$('#main-content').load(obj.url,function(){
				$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		        $('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
			});
		}
	},'json');
}

function faq_Apply(){
	title = $("#title").val();
	if(title == ''){
	    $("#title").parent().parent().addClass("has-error");
	    $("#show_error").html('<span class="help-block"><?php echo __('The field is required','faq') ?></span>');
	    $.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','faq'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});	   
	    return false;
	}
	$("#main-content").mask("<?php echo __('Loading...','faq'); ?>");
	try{
       tinymce.triggerSave(); 
        tinymce.remove();
    }catch(e){}
	$.post($('#frm_faq_Edit').attr('action'),$('#frm_faq_Edit').serialize(),function(obj){
		$("#main-content").unmask();
		$('#main-content').html(obj.content);
		if (obj.error == 0){
			$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('FAQ is updated successfully','faq'); ?>", html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
		}else{
		}
		$('#main-content input[type="checkbox"]').iCheck({checkboxClass: 'icheckbox_minimal'});
		$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
	},'json');
}
</script>
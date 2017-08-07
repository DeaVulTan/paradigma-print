<?php defined('PF_VERSION') OR header('Location:404.html');?>
<div class="row" style="margin-bottom: 20px; margin-top: -15px;">
    <div class="col-sm-12">
        <h3>
            <?php if (!empty($this->menu_settings[$this->controller->get->{"admin-page"}])){ ?>
                <?php echo '<i class="' . $this->menu_settings[$this->controller->get->{"admin-page"}]['icon'] . '" style="color:'.$this->menu_settings[$this->controller->get->{"admin-page"}]['icon_color'].'"></i>'; ?>
            <?php }else{ ?>
                <?php global $_admin_menu; ?>
                <?php echo (!empty($_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'])) ? '<i class="' . $_admin_menu[$this->controller->get->{"admin-page"}]['icon_class'] . '"></i>' : ''; ?>
            <?php } ?>
            User
        </h3>
    </div>
</div>
<div class="panel panel-default" style="border:none;">
    <div class="panel-body">
        <div class="col-lg-3 overflow-hidden" style="color:#3c8dbc;">
            <?php
            echo user_avatar(current_user('user-id'), '180px');
            echo "<br><h6><a href='' data-toggle='modal' data-target='#changeavt'>" . __('Change Avatar', 'user') . "</h6></a>";
            ?>

        </div>
        <div class="col-lg-6">
            <?php if (!empty($this->controller->post->notif)) { ?>
                <div class="alert alert-success fade in">
                    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                    <?php echo $this->controller->post->notif; ?>
                </div>
            <?php }?>
            <form id="save-change" class="form-horizontal" role="form" method="post" action="<?php echo admin_url();?>">
                <div class="form-group" >
                    <label  class="col-sm-4 control-label"><?php echo __('First Name', 'user'); ?></label>
                    <div class="col-sm-8 <?php $this->error_class("firstname");?> ">
                        <input id="firstname" type="text" name="firstname" class="form-control" value="<?php echo current_user('user-firstname'); ?>" />
                        <?php $this->error_message("firstname")?>
                    </div>
                </div>
                <div class="form-group">
                    <label  class="col-sm-4 control-label"><?php echo __('Last Name', 'user'); ?></label>
                    <div class="col-sm-8 <?php $this->error_class("lastname");?>">
                        <input id="lastname" type="text" name="lastname" class="form-control" value="<?php echo current_user('user-lastname'); ?>" />
                        <?php $this->error_message("lastname")?>
                    </div>
                </div>
                <div class="form-group">
                    <label  class="col-sm-4 control-label"><?php echo __('Email', 'user'); ?></label>
                    <div class="col-sm-8 <?php $this->error_class("email");?>">
                        <input type="text" name="email" class="form-control" value="<?php echo isset($_POST['email'])?$_POST['email']:current_user('user_email'); ?>" />
                        <?php $this->error_message("email")?>
                    </div>
                </div>
                <?php
                $list_fields    = get_option('user_custom_fields');
                $userid    =  current_user('user-id');
                $data = $this->controller->user_model->get_info($userid);
                $custom_fields = unserialize($data[0]['user_custom_fields']);
                if(isset($_POST['public_profile'])){
                	if($_POST['public_profile']==1){
                		$check[1] =   "checked='checked'";
                	}else{
                		$check[2] =   "checked='checked'";
                	}
                
                }else{
                	$check[$data[0]['public_profile']] =   "checked='checked'";
                }
                if (!empty($list_fields)) {
                    foreach ($list_fields as $item) {
                        ?>
                        <div class="form-group">
                            <label  class="col-sm-4 control-label"><?php echo $item['label'];echo $item['require']==1?"<span style='color:red'>*</span>":"";?></label>
                            <div class="col-sm-8 <?php $this->error_class($item['label']); ?>">
                                <input type="<?php echo $item['type']; ?>" name="custom[<?php echo $item['name']; ?>]" value="<?php if (isset($_POST['custom'][$item['name']])) echo $_POST['custom'][$item['name']];
                else echo isset($custom_fields[$item['name']]) ? $custom_fields[$item['name']] : ''; ?>" class="form-control">
                                <?php  $this->error_message($item['label']); ?>
                            </div>
                        </div>
                    <?php
                    }
                }
                ?>
                <div class="form-group">
                    <label  class="col-sm-4 control-label"><?php echo __('Public your profile', 'user'); ?></label>
                    <div class="col-sm-8">
                        <input type="radio" name="public_profile" value="1" <?php echo !empty($check[1])?$check[1]:''; ?> />    <?php echo __("Yes","user");?>
                        <input type="radio" name="public_profile" value="2" <?php echo !empty($check[2])?$check[2]:''; ?> />    <?php echo __("No","user");?>
                    </div>
                </div>
                <div class="form-group">
                    <label  class="col-sm-4 control-label"></label>
                    <div class="col-sm-8">
                        <h6><a href="#" data-toggle="modal" data-target="#myModal" ><?php echo __('Change password', 'user'); ?></h6></a>
                    </div>
                </div>
            </form>
             <div class="form-group">
                    <div class="col-sm-offset-2 col-sm-10">
                        <button type="submit" class="btn btn-default" onclick="Save_changes()" name='submit'><?php echo __('Save changes', 'user'); ?></button>
                    </div>
                </div>
        </div>
        <div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel"><?php echo __('Change password', 'user'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <form class="form-horizontal" role="form" id='changepass' method="post" action=''>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label"><?php echo __('Current password', 'user'); ?></label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="oldpass" id="oldpass" >
                                    <div id="oldpasstxt"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label"><?php echo __('New Password', 'user'); ?></label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="newpass" id="newpass" >
                                    <div id="passtxt"></div>
                                </div>
                            </div>
                            <div class="form-group">
                                <label  class="col-sm-2 control-label"><?php echo __('Confirm', 'user'); ?></label>
                                <div class="col-sm-10">
                                    <input type="password" class="form-control" name="confirm" id="confirm" >
                                    <div id="confirmtxt"></div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close', 'user'); ?></button>
                        <a id='change' type="button" class="btn btn-primary" name='changepass' onclick='submitpass();'><?php echo __('Save changes', 'user'); ?></a>
                    </div>
                </div>
            </div>
        </div>
        <div class="modal fade" id="changeavt" tabindex="-1" role="dialog" aria-labelledby="myModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                        <h4 class="modal-title" id="myModalLabel"><?php echo __('Change Avatar', 'user'); ?></h4>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <form method="post" id="avt">
                                <div class="col-sm-10">
                                    <div class="input-group">
                                        <input class="form-control" id="getImage" type="text" name="avatar" value="">
                                        <span class="input-group-btn">
                                            <a class="btn btn-default boxGetFile" type="button" href="<?php echo RELATIVE_PATH . '/app/plugins/default/media/filemanager/dialog.php?type=1&field_id=getImage'; ?>"><?php echo __('select image', 'user'); ?></a>
                                            <br/><div id="note" style="display:none"><font color='red' size=2 ><?php echo __('Please choose an avatar', 'user'); ?></font></div>
                                        </span>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo __('Close', 'user'); ?></button>
                        <a id='change' type="button" class="btn btn-primary" name='changepass' onclick='submitavt();'><?php echo __('Save changes', 'user'); ?></a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
<?php if(!empty($this->controller->post->sucsess)){ ?>
	$.notification({type:"success",img:"",width:"400",content:"<i class='fa fa-check fa-2x'></i> <?php echo __('Profile is changed successfully!','user'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
<?php }elseif (!empty($this->controller->post->error)){?>
	$.notification({type:"error",img:"",width:"400",content:"<i class='fa fa-times-circle fa-2x'></i> <?php echo __('There is an error, please check again!','user'); ?>",html:true,autoClose:true,timeOut:"1500",delay:"0",position:"topRight",effect:"fade",animate:"fadeUp",easing:"jswing",onStart:function(id){ console.log(' onStart : notification id = '+id); },onShow:function(id){ console.log(' onShow : notification id = '+id); },onClose:function(id){ console.log(' onClose : notification id = '+id); },duration:"300"});
<?php }?>
                    $('.boxGetFile').fancybox({
                        'width': '75%',
                        'height': '90%',
                        'autoScale': false,
                        'transitionIn': 'none',
                        'transitionOut': 'none',
                        'type': 'iframe'
                    });
                    function submitavt() {
                        $('#avt').submit();
                    }
                    function submitpass() {
                        <?php $min_pass = Pf::setting()->get_element_value('pf_user', 'pass_length');?>
                        var min_pass = '<?php echo $min_pass;?>';
                        $('#confirmtxt').html('');
                        $('#passtxt').html('');
                        $('#oldpass').attr('style', '');
                        $('#newpass').attr('style', '');
                        $('#confirm').attr('style', '');
                        var oldpass = $('#oldpass').val();
                        var newpass = $('#newpass').val();
                        var confirm = $('#confirm').val();
                        var s = 0;
                        if (oldpass == '') {
                            $('#oldpass').attr('style', 'border-color: red;');
                            s++;
                        }
                        if (newpass.length < min_pass) {
                            var note = "<font color='red'><?php printf(__('Your password must from %s-20 characters!', 'user'),$min_pass); ?></font>";
                            $('#passtxt').append(note);
                            $('#newpass').attr('style', 'border-color: red;');
                            s++;
                        }
                        if (confirm == '') {
                            $('#confirm').attr('style', 'border-color: red;');
                            s++;
                        }
                        if (confirm != newpass) {
                            var conf = "<font color='red'><?php echo __('Passwords do not match!', 'user'); ?></font>";
                            $('#confirmtxt').append(conf);
                            $('#confirm').attr('style', 'border-color: red;');
                            s++;
                        }
                        if (s == 0) {
                        	 $.post("<?php echo admin_url(); ?>", {action: "getpass",oldpass: oldpass},
                        	        function (data) {
                        		 	return_data(data);
                        	  });
                        }
                    }
                    function return_data(data) {
                        $('#oldpasstxt').html('');
                        if (data == 'wrong') {
                            var conf = "<font color='red'><?php echo __('Wrong current password', 'user'); ?></font>";
                            $('#oldpasstxt').append(conf);
                        }
                        else {
                            $('#changepass').submit();
                        }
                    }
                    function Save_changes(){
                    	 $("#main-content").mask("<?php echo __('Loading...','user'); ?>");
                    	 $.post($('#save-change').attr('action'),$('#save-change').serialize(),
                     	        function (html) {
                    		 	$("#main-content").unmask();
		                       	$('#main-content').html(html);
		                       	$('#main-content input[type="radio"]').iCheck({radioClass: 'iradio_minimal'});
                     	  });
                    }
</script>

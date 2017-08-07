<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php 
    public_js(RELATIVE_PATH. "/media/assets/fancybox/jquery.fancybox-1.3.6.pack.js",true);
    public_css(RELATIVE_PATH."/media/assets/fancybox/jquery.fancybox-1.3.6.css", true); 
?>
<?php 
    $dateformat= get_configuration('long_date');
    $shortformat= get_configuration('short_date');
?>
<div class="row">
  <div class="col-sm-3">
    <div class="user-avatar shadow-effect text-center" id="user_avatar_frame">
      <?php echo user_avatar($this->info['uid'],'100%'); ?>
      <?php echo $this->info['user_firstname']; ?> <?php echo $this->info['user_lastname']; ?>
      <p class="text-muted"><?php echo $this->user_role[$this->info['user_role']]; ?></p>
    </div>
    <?php require dirname(__FILE__).'/menu.php';?>
  </div>
  <div class="col-sm-9">
    <h3 class="second-child"><?php echo $this->info['user_firstname']; ?> <?php echo $this->info['user_lastname']; ?>
        <small>Edit profile</small>
    </h3>
    <?php if (!empty($this->message)){?>
        <div class="alert alert-<?php echo $this->message_type?>" role="alert">
            <?php foreach ($this->message as $message){?>
                <div><?php echo (is_array($message)?implode('<br>', $message):$message);?> </div>
            <?php } ?>
        </div>
    <?php } ?>
    <form method="post">
    <input type="hidden" name="form_type" value="profile_edit">
    <div class="row">
      <div class="col-sm-6">
        <ul class="user-info">
          <li>
            <div class="row">
                <div class="col-sm-4"><?php echo __('Username','user'); ?></div>
                <div class="col-sm-8"><span class="text-muted"><?php echo $this->info['user_name']; ?></span></div>
            </div>
           </li>
           <li>
            <div class="row">
                <div class="col-sm-4"><?php echo __('First Name','user'); ?><span style="color: red;">*</span></div>
                <div class="col-sm-8"><?php echo form_input($this->form_firstname); ?></div>
            </div>
           </li>
           <li>
            <div class="row">
                <div class="col-sm-4"><?php echo __('Last Name','user'); ?><span style="color: red;">*</span></div>
                <div class="col-sm-8"><?php echo form_input($this->form_lastname); ?></div>
            </div>
           </li>
           
           <li>
            <div class="row">
                <div class="col-sm-4"><?php echo __('Role','user'); ?></div>
                <div class="col-sm-8"><?php echo $this->user_role[$this->info['user_role']]; ?></div>
            </div>
           </li>
           <li>
            <div class="row">
                <div class="col-sm-4"><?php echo __('Avatar','user'); ?></div>
                <div class="col-sm-8"> 
                    <?php echo form_hidden('user_avatar',$this->info['user_avatar']); ?>
                    <a class="btn btn-default" id="choose_user_avatar" href="<?php echo RELATIVE_PATH . '/app/plugins/default/media/filemanager/dialog.php?type=1&field_id=user_avatar'; ?>" ><i class="fa fa-file-image-o"></i> Choose image ...</a>
                </div>
            </div>
           </li>
        </ul>
      </div>
      <div class="col-sm-6">
        <ul class="user-info">
          <li>
            <div class="row">
                <div class="col-sm-4"><?php echo __('Email','user'); ?><span style="color: red;">*</span></div>
                <div class="col-sm-8"><?php echo form_input($this->form_email); ?></div>
            </div>
           </li>
           <li>
                <div class="row">
                    <div class="col-sm-4"><?php echo __('Public Profile','user'); ?></div>
                    <div class="col-sm-8">
                        <label class="radio-inline">
                            <input type="radio" name="public_profile" value="1" <?php echo!empty($this->check[1]) ? $this->check[1] : ''; ?> />  <?php echo __('Yes', 'user'); ?>
                        </label>
                        <label class="radio-inline">
                            <input type="radio" name="public_profile" value="2" <?php echo!empty($this->check[2]) ? $this->check[2] : ''; ?> />    <?php echo __('No', 'user'); ?>
                        </label>
                    </div>
                </div>
            </li>
           <li>
            <div class="row">
                <div class="col-sm-4"><?php echo __('Joined','user'); ?></div>
                <div class="col-sm-8"><span class="text-muted"><?php echo date($shortformat,strtotime($this->info['user_registered_date'])); ?></span></div>
            </div>
           </li>
           
           <li>
            <div class="row">
                <div class="col-sm-4"><?php echo __('Last login','user'); ?></div>
                <div class="col-sm-8"><span class="text-muted"><?php echo date($dateformat,strtotime($this->info['user_login_time'])); ?></span></div>
            </div>
           </li>
        </ul>
      </div>
    </div>
    <?php if(!empty($this->list_fields)){ ?>
        <h4><?php echo __('Others','user'); ?></h4>
        <div class="row" style="margin-top: 20px;">
<?php 
            $custom_fields = unserialize($this->info['user_custom_fields']); 
            foreach($this->list_fields as $item){ 
?>
            <div class="col-sm-6">
                <div class="row">
                    <div class="col-sm-4"><?php echo $item['label'];?><?php if ($item['require']==1) {?><span style="color: red;">*</span><?php } ?></div>
                    <div class="col-sm-8">
                        <input type="<?php echo $item['type']; ?>" name="custom[<?php echo $item['name']; ?>]" value="<?php if(isset($_POST['custom'][$item['name']])) echo $_POST['custom'][$item['name']]; else echo isset($custom_fields[$item['name']])? $custom_fields[$item['name']]:''; ?>" class="form-control">
                    </div>
                </div>
            </div>
<?php 
            } 
?>
        </div>
    <?php } ?>
    <br>
      <button class="btn btn-primary"><i class="fa fa-check"></i> <?php echo __('Apply','user'); ?></button>
      </form>
  </div>
</div> <!-- / .row -->
<script>
$(document).ready(function(){
	$('#choose_user_avatar').fancybox({
        'width': '75%',
        'height': '90%',
        'autoScale': false,
        'transitionIn': 'none',
        'transitionOut': 'none',
        'type': 'iframe',
         onClosed: function() {
                var img_url = $('#user_avatar').val();
                $('#user_avatar_frame > i').remove();
                
                if ($('#user_avatar_frame > img').length > 0){
                    $('#user_avatar_frame > img').attr('src','<?php echo RELATIVE_PATH; ?>/'+img_url);
                }else{
                	$('#user_avatar_frame').prepend($('<img>').css('width','100%').attr('src','<?php echo RELATIVE_PATH; ?>/'+img_url));
                }
            }
    });
});
</script>
<div class="row">
  <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="sign-form">
      <div class="sign-inner">
        <h3 class="first-child"><?php echo __('Sign in your account','user'); ?></h3>
        <hr>
        <?php if (trim($this->error) != ''){ ?>
            <div class="alert alert-danger" role="alert"><?php echo $this->error; ?></div>
        <?php } ?>
        <form id="login-form" role="form" method="post" action="<?php echo public_url('user/signin')."?ref=".urlencode($this->referer); ?>">
            <input type="hidden" name="type" value="login" />
            <span id="errorun"></span>
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-user"></i></span>
            <?php echo form_input(array('name' => 'username','placeholder' => __('Please enter your username','user'))); ?>
          </div>
          <br>
          <div class="input-group">
            <span class="input-group-addon"><i class="fa fa-lock"></i></span>
            <input type="password" class="form-control" id="login-password" name="password" placeholder="<?php echo __('Password','user'); ?>">
          </div>
          <div class="checkbox">
            <label>
              <input id="login-remember" type="checkbox" name="remember" value="1" <?php if ($this->controller->post->remember == 1){?> checked="checked" <?php } ?>> <?php echo __('Remember me', 'user'); ?>
            </label>
          </div>
          <button type="submit" class="btn btn-theme-primary"><?php echo __('Sign In', 'user'); ?></button>
          <hr>
        </form>
        <p>Not registered? <a href="<?php echo public_url('user/signup'); ?>"><?php echo __('Create an account','user'); ?></a></p>
        <div class="pwd-lost">
          <div class="pwd-lost-q show"><?php echo __('Lost your password?', 'user'); ?> <a href="#"><?php echo __('Please click here', 'user'); ?></a></div>
          <div class="pwd-lost-f hidden">
            <p class="text-muted" id="recover_note"><?php echo __('Please enter your email and we will send you a link to reset password.','user'); ?></p>
            <form class="form-inline" role="form" id="recover_form">
              <div class="form-group">
                <label class="sr-only" for="recover_email"><?php echo __('Email', 'user'); ?></label>
                <input type="email" class="form-control" id="recover_email" name="recover_email" placeholder="Enter email">
              </div>
              <button type="button" class="btn btn-theme-secondary" id="recover_button"><?php echo __('Send', 'user'); ?></button>
            </form>
          </div>
        </div> <!-- / .pwd-lost -->
      </div>
    </div>
  </div>
</div> <!-- / .row -->
<script>
$(document).ready(function(){
    $('#recover_button').click(function(){
        $('.recover-alert').remove();
        $(this).attr('disabled',true);
        var recover_button_label = $(this).html();
        $(this).html('<i class="fa fa-spinner fa-spin"></i>');
        $.post('<?php echo public_url('user_code:recover'); ?>',{recover_email:$('#recover_email').val()},function(obj){
            var alert = 'alert-success';
            if (obj.error == 1){
                alert = 'alert-danger';
            }
            $('#recover_form').before('<div class="alert '+alert+' recover-alert" role="alert">'+obj.message+'</div>');
            if (obj.error == 0){
                $('#recover_form').remove();
                $('#recover_note').remove();
            }
            $('#recover_button').attr('disabled',false);
            $('#recover_button').html(recover_button_label);;
        },'json');
    });
	
});
</script>
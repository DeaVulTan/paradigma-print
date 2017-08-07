<?php defined('PF_VERSION') OR header('Location:404.html');?>
<div class="row">
  <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="sign-form">
      <div class="sign-inner">
        <h3 class="first-child"><?php echo __('Create an account', 'user'); ?></h3>
        <hr>
        <?php if ($this->allow == 1) { ?>
        <?php if(!isset($this->register_success)){ ?>
        <form id="signup-form"  role="form" method="post">
          <input type="hidden" name="type" value="register" />
          <div class="form-group <?php $this->error_class('firstname');?>">
            <?php echo form_input($this->form_firstname); ?>
            <?php $this->error_message('firstname')?>            
          </div>
          <div class="form-group <?php $this->error_class('lastname');?>">
            <?php echo form_input($this->form_lastname); ?>
            <?php $this->error_message('lastname')?>            
          </div>
          <div class="form-group <?php $this->error_class('username');?>">
            <?php echo form_input($this->form_username); ?>
            <?php $this->error_message('username')?>
          </div>
          <div class="form-group <?php $this->error_class('email');?>">
            <?php echo form_input($this->form_email); ?>
            <?php $this->error_message('email')?>
          </div>
          <div class="form-group <?php $this->error_class('password');?> <?php $this->error_class('repassword');?> <?php $this->error_class('password_do_not_match');?>">
            <div class="row">
              <div class="col-sm-6">
                <input type="password" value="<?php if(!empty($_POST['password'])) echo $_POST['password']; ?>" class="form-control" id="password2" name="password" placeholder="<?php echo __('Password','user'); ?>">
                <?php $this->error_message('password')?>
              </div>
              <div class="col-sm-6">
                <input type="password" value="<?php if(!empty($_POST['repassword'])) echo $_POST['repassword']; ?>" class="form-control" id="password3" name="repassword" placeholder="<?php echo __('Repeat Password','user'); ?>">
                <?php $this->error_message('repassword')?>
              </div>
            </div>
            <?php $this->error_message('password_do_not_match')?>
          </div>
<?php       if(!empty($this->custom_field)){
                foreach($this->custom_field as $item){
                    if($item['register']==1){
?>
                <div class="form-group <?php $this->error_class($item['name']);?>">
                    <input type="<?php echo $item['type']; ?>" placeholder="<?php echo $item['label']; ?>" name="custom[<?php echo $item['name']; ?>]" value="<?php if(!empty($_POST['custom'][$item['name']])) echo $_POST['custom'][$item['name']]; ?>" class="form-control">
                    <?php $this->error_message($item['name'])?>
                </div>
<?php               }
                }
            }
?>
          <?php if($this->captcha != false){ ?>
          <div class="form-group <?php $this->error_class('captcha_code');?>">
                <?php echo recaptcha_get_html($this->captcha['publickey']); ?>
                <?php $this->error_message('captcha_code')?>
          </div>
          <?php } ?>
          <div class="form-group <?php $this->error_class('igree');?>">
              <div class="checkbox">
                <label>
                  <input type="checkbox" name="igree" value="1" <?php if (isset($_POST['igree']) && $_POST['igree'] == 1){ ?> checked="checked" <?php }?>> <?php echo __('I agree to the Terms of Service and Privacy Policy','user'); ?>
                </label>
              </div>
              <?php $this->error_message('igree')?>
          </div>
          <button type="submit" id="btn-signup" class="btn btn-theme-primary">Create Account</button>
          <hr>
        </form>
        <p><?php echo __('You have already signed up','user'); ?> <a href="<?php echo public_url('user/signin'); ?>"><?php echo __('Please click here','user'); ?></a></p>
        <?php } ?>
        <?php }else{ ?>
            <?php echo __('Registration is disable.','user'); ?>
        <?php } ?>
      </div>
    </div>
  </div>
</div> <!-- / .row --> 
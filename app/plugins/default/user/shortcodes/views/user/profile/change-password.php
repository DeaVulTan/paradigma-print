<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php 
    $dateformat= get_configuration('long_date');
    $shortformat= get_configuration('short_date');
?>
<div class="row">
  <div class="col-sm-3">
    <div class="user-avatar shadow-effect text-center">
      <?php echo user_avatar($this->info['uid'],'100%'); ?>
      <?php echo $this->info['user_firstname']; ?> <?php echo $this->info['user_lastname']; ?>
      <p class="text-muted"><?php echo $this->user_role[$this->info['user_role']]; ?></p>
    </div>
    <?php require dirname(__FILE__).'/menu.php';?>
  </div>
  <div class="col-sm-9">
    <h3 class="second-child">
        <?php echo $this->info['user_firstname']; ?> <?php echo $this->info['user_lastname']; ?>
        <small>Change password</small>
    </h3>
    <br>
    <?php if ($this->message != ''){?>
    <div class="alert alert-<?php echo $this->message_type; ?>" role="alert"><?php echo $this->message; ?></div>
    <?php }?>
    <form method="post" role="form">
        <input type="hidden" name="form_type" value="profile_change_password">
        <div class="form-group">
            <label for="old_password"><span class="text-muted"><?php echo __('Current password','user');?></span></label>
            <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Old password">
        </div>
        <div class="form-group">
            <label for="new_password"><span class="text-muted"><?php echo __('New Password','user');?></span></label>
            <input type="password" class="form-control" id="new_password" name="new_password" placeholder="New password">
        </div>
        <div class="form-group">
            <label for="repeat_new_password"><span class="text-muted"><?php echo __('Repeat Password','user');?></span></label>
            <input type="password" class="form-control" id="repeat_new_password" name="repeat_new_password" placeholder="Repeat new password">
        </div>
        <button type="submit" class="btn btn-primary"><i class="fa fa-check"></i> <?php echo __('Apply','user');?></button>
    </form>
  </div>
</div> <!-- / .row -->
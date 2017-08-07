<?php if (is_login()) {?>
<div class="panel panel-default">
  <div class="panel-heading">
    <?php echo __('User Menu','user'); ?>
  </div>
  <div class="panel-body">
    <ul>
      <li><a href="<?php echo public_url("user/profile"); ?>"><i class="fa fa-user"></i> <?php echo __('My Profile','user');?></a></li>
      <li><a href="<?php echo public_url("user/profile/action:edit"); ?>"><i class="fa fa-edit"></i> <?php echo __('Edit Profile','user'); ?></a></li>
      <li><a href="<?php echo public_url("user/profile/action:change-password"); ?>"><i class="fa fa-key"></i> <?php echo __('Change password','user'); ?></a></li>
      <li><a href="<?php echo public_url("user/profile/user_code:signout/ajax:1?ref=" . $this->ref, false); ?>"><i class="fa fa-sign-out"></i> <?php echo __('Sign out','user'); ?></a></li>
    </ul>
  </div>
</div>
<?php } ?>
<div class="row">
  <div class="col-md-6 col-md-offset-3 col-sm-8 col-sm-offset-2">
    <div class="sign-form">
      <div class="sign-inner">
        <h3 class="first-child"><?php echo __('Change password','user'); ?></h3>
        <hr>
        <?php if (!empty($this->message)){?>
            <div class="alert alert-<?php if ($this->error == false){?>success<?php }else{ ?>danger<?php }?>" role="alert">
               <?php echo $this->message; ?>
            </div>
        <?php }?>
        <?php if ($this->error == true){?>
        <form id="login-form" role="form" method="post">
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                <input type="password" class="form-control" id="password" name="password" placeholder="Password">
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-key"></i></span>
                <input type="password" class="form-control" id="confirm" name="confirm" placeholder="Repeat password">
              </div>
            </div>
            <button type="submit" id="btn-signup" class="btn btn-theme-primary"><?php echo __('Reset password','user');?></button>
        </form>
        <?php } ?>
      </div>
    </div>
  </div>
</div> <!-- / .row --> 
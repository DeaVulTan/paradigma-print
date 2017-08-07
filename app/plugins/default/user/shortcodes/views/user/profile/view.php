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
    <h3 class="second-child"><?php echo $this->info['user_firstname']; ?> <?php echo $this->info['user_lastname']; ?></h3>
    
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
                    <div class="col-sm-4"><?php echo __('First Name','user'); ?></div>
                    <div class="col-sm-8"><span class="text-muted"><?php echo $this->info['user_firstname']; ?></span></div>
                </div>
            </li>
            <li>
                <div class="row">
                    <div class="col-sm-4"><?php echo __('Last Name','user'); ?></div>
                    <div class="col-sm-8"><span class="text-muted"><?php echo $this->info['user_lastname']; ?></span></div>
                </div>
            </li>
            <li>
                <div class="row">
                    <div class="col-sm-4"><?php echo __('Role','user'); ?></div>
                    <div class="col-sm-8"><span class="text-muted"><?php echo $this->user_role[$this->info['user_role']]; ?></span></div>
                </div>
            </li>
        </ul>
      </div>
      <div class="col-sm-6">
        <ul class="user-info">
            <li>
                <div class="row">
                    <div class="col-sm-4"><?php echo __('Email','user'); ?></div>
                    <div class="col-sm-8"><span class="text-muted"><?php echo $this->info['user_email']; ?></span></div>
                </div>
            </li>
            <li>
                <div class="row">
                    <div class="col-sm-4"><?php echo __('Public Profile','user'); ?></div>
                    <div class="col-sm-8"><span class="text-muted">
                        <?php if ($this->info['public_profile'] == 1) {?>Yes <?php }else{ ?>No <?php } ?>
                        </span></div>
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
                    <div class="col-sm-4"><?php echo $item['label'];?></div>
                    <div class="col-sm-8"><span class="text-muted"><?php echo isset($custom_fields[$item['name']])? $custom_fields[$item['name']]:''; ?></span></div>
                </div>
            </div>
<?php 
            } 
?>
        </div>
    <?php } ?>
  </div>
</div> <!-- / .row -->
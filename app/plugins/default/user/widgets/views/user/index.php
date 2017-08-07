<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<div class="panel panel-default">
    <div class="panel-heading">
        <?php echo (isset($this->controller->setting['widget-title']) && trim($this->controller->setting['widget-title']) != '') ? $this->controller->setting['widget-title'] : ''; ?>
    </div>
    <div class="panel-body" style="background: <?php echo $this->controller->setting['background_color']; ?>;padding: 10px 0px;">
        
        <div class="widget-content">
            <?php
                $ref = $_GET['pf_page_url'] == $this->userpage ? '#' : urlencode($_SERVER['REQUEST_URI']);
                if (is_login() == true):
                    ?>
                    <div class='col-sm-6 avatar-div' >
                        <?php
                            echo user_avatar(current_user('user-id'), '');
                        ?>
                    </div>
                    <div class='col-sm-6 info-div'>
                        <ul class='listinfo'>
                            <li>Hello, <b><?php echo current_user('user-name'); ?></b></li>
                            <?php
                                if(current_user('user-group') == 1){
                                    ?>
                                    <li><a
                                            href="<?php echo public_url(ADMIN_FOLDER); ?>"><?php echo __('Admin Panel', 'user'); ?></a>
                                    </li><?php } ?>
                            <li>
                                <a href="<?php echo public_url($this->userpage . "/profile"); ?>"><?php echo __('My Profile', 'user'); ?></a>
                            </li>
                            <li>
                                <a href="<?php echo public_url($this->userpage . "/profile/user_code:signout/ajax:1?ref=" . $ref); ?>"><?php echo __('Sign out', 'user'); ?></a>
                            </li>
                        </ul>
                    </div>
                <?php
                else:
                    ?>
                    <div class="text-center widget-user-color">
                        <a href="<?php if(!empty($_GET['user-action']) && $_GET['user-action'] == 'login')
                            echo '#';
                        else{
                            echo public_url($this->userpage . "/signin/?ref=" . $ref);
                        } ?>" class="btn btn-color btn-sm"><?php echo __('Sign In', 'user'); ?></a>


                        <a href="<?php echo public_url($this->userpage . "/signup"); ?>"
                           class="btn btn-color btn-sm"><?php echo __('Sign up', 'user'); ?></a>
                    </div>
                <?php
                endif;
            ?>
        </div>
    </div>
</div>

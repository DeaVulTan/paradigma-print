<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php if (isset($rs['page_type']) && $rs['page_type'] == 0){ ?>
<?php 
if (!isset($theme_options))
$theme_options = get_option('theme_options');
?>
<style>
.navbar-brand {
    line-height: 55px;
    padding: 5px 15px;
    vertical-align: middle;
}
</style>
<!-- Navigation -->
<div class="navbar navbar-default navbar-fixed-top" role="navigation">
  <div class="container">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-collapse">
        <span class="sr-only">Toggle navigation</span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
      </button>
      <a class="navbar-brand" href="<?php echo site_url() . RELATIVE_PATH ?>"><?php echo theme_logo($theme_options); ?></a>
    </div>
    <div class="collapse navbar-collapse">
<?php  $ref = "/" . $_GET['pf_page_url'] == 'user' ? '#' : urlencode($_SERVER['REQUEST_URI']); ?>
<?php if(get_configuration('sign_in_option','pf_user')){?>
<?php if (!is_login()){ ?>
      <a class="navbar-btn btn btn-theme-primary pull-right" href="<?php echo public_url("user/signin/?ref=" . $ref); ?>">
        <?php echo __('Sign In', 'jupiter-theme'); ?>
      </a>
<?php }else{ ?>
    <ul class="nav navbar-nav navbar-right">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?php echo current_user('user-name'); ?> <b class="caret"></b></a>
          <ul class="dropdown-menu">
            <li>
                <div style="height:128px; padding-top:10px;" class="text-center">
                        <?php echo user_avatar(current_user('user-id'), '90px', 'img-circle border-color'); ?>
                        <p><?php echo current_user('user-firstname'); ?> <?php echo current_user('user-lastname'); ?></p>
                </div>
            </li>
            <li><a href="<?php echo public_url("user/profile"); ?>"><i class="fa fa-user"></i> <?php echo __('My Profile', 'jupiter-theme'); ?></a></li>
            <?php if (current_user('user-group') < 5) { ?><li><a href="<?php echo public_url("admin"); ?>"><i class="fa fa-shield"></i> <?php echo __('Admin Panel', 'jupiter-theme'); ?></a></li><?php } ?>
            <li><a href="<?php echo public_url("user/profile/user_code:signout/ajax:1?ref=" . $ref, false); ?>"><i class="fa fa-sign-out"></i> <?php echo __('Sign Out', 'jupiter-theme'); ?></a></li>
          </ul>
        </li>
    </ul>
<?php } }?>
      <ul class="nav navbar-nav navbar-right">
        <?php echo Pf::shortcode()->exec("{menu:navigation}"); ?>
        <!-- Navbar Search -->
<?php
        $post_list = get_page_url_by_id(get_configuration('page_lists', 'pf_post'));
        if (get_configuration('enable_search') == 1 && !empty($post_list)){
?>
        <li class="hidden-xs" id="navbar-search">
          <a href="#"><i class="fa fa-search"></i></a>
          <div class="hidden" id="navbar-search-box">
            <form action="<?php echo site_url() . RELATIVE_PATH . '/' . $post_list ?>" method="get">
                <div class="input-group">
                  <input type="text" class="form-control" placeholder="<?php echo __('Search', 'jupiter-theme') ?>" name="title">
                  <span class="input-group-btn">
                    <button class="btn btn-default" type="submit"><?php echo __('Go', 'jupiter-theme') ?></button>
                  </span>
                </div>
            </form>
          </div>
        </li>
<?php 
        }
?>
      </ul>
<?php
        $post_list = get_page_url_by_id(get_configuration('page_lists', 'pf_post'));
        if (get_configuration('enable_search') == 1 && !empty($post_list)){
?>
      <!-- Mobile Search -->
      <form class="navbar-form navbar-right visible-xs" role="search" method="get" action="<?php echo site_url() . RELATIVE_PATH . '/' . $post_list ?>">
        <div class="input-group">
          <input type="text" class="form-control" placeholder="<?php echo __('Search', 'jupiter-theme') ?>" name="title">
          <span class="input-group-btn">
            <button type="submit" class="btn btn-theme-primary" type="button">Search!</button>
          </span>
        </div>
      </form>
<?php 
        }
?>      
    </div><!--/.nav-collapse -->
  </div>
</div> <!-- / .navigation -->
<script>
    $(document).ready(function() {
        $(".navbar-collapse > ul.navbar-nav > li").each(function() {
            var obj = $(this).find('ul > .active');
            if (obj.length > 0) {
                $(this).addClass('active');
                obj.removeClass('active');
                return false;
            }
        });
    });
</script>
<?php } else {?>
    <?php if (isset($rs['page_type']) && $rs['page_type'] == 1){ ?>
    <script>$(document).ready(function(){$(".wrapper").css({"padding":"0px"})});</script>
<?php } }?>
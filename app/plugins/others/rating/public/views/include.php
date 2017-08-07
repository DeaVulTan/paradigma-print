<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<div class="hidden" id="messageErrorJS">
    <ul>
        <li class="confirmDelete"><?php echo __('Are you sure to delete this rating?', 'rating'); ?></li>
    </ul>
</div>
<?php 
 public_css(get_path_file('app/plugins/others/rating/public/assets/jquery.raty.css'),true);
 public_js( get_path_file('app/plugins/others/rating/public/assets/jquery.raty.js'), true ); 
 public_js( get_path_file('app/plugins/others/rating/public/assets/rating.js'), true ); 
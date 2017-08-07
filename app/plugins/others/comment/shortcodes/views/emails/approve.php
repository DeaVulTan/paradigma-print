<?php
defined('PF_VERSION') OR header('Location:404.html');
?>
<p>
    <strong>Shortcodes: </strong>
</p>
<p><?php echo __('Site name', 'comment'); ?>       :   <code>{sitename}</code></p>
<p><?php echo __('Username', 'comment'); ?>        :   <code>{username}</code></p>
<p><?php echo __('Comment', 'comment'); ?>         :   <code>{comment}</code></p>

<?php
defined('PF_VERSION') OR header('Location:404.html');
?>
<div class="hidden" id="messageErrorJS">
    <ul>
        <li class="confirmDelete"><?php echo __('Are you sure to delete this comment?', 'comment'); ?></li>
        <li class="error_messagelength"><?php echo __('Comment can not be empty and less than ' . $this->atts['maximum_characters'] . ' characters', 'comment'); ?></li>
        <li class="error_token"><?php echo __('You do not have permission or the session has ended. Please visit again!', 'comment'); ?></li>
        <li class="error_cud"><?php echo __('There was an error Please try again', 'comment'); ?></li>
        <li class="approve"><?php echo __('Thank you for comment. We will moderate and approve it maximum 8 hours.', 'comment'); ?></li>
        <li class="error_input"><?php echo __('The message field is required.', 'comment'); ?></li>
    </ul>
</div>
<?php
public_css(public_base_url().('app/plugins/others/comment/shortcodes/assets/comment.css'), true);
public_js(public_base_url().('media/assets/bootbox/js/bootbox.min.js'), true);
public_js(public_base_url().('media/assets/handlebars/js/handlebars.min.js'), true);
public_js(public_base_url().('app/plugins/others/comment/shortcodes/assets/jquery.elastic.source.js'), true);
public_js(public_base_url().('app/plugins/others/comment/shortcodes/assets/jquery.autosize.min.js'), true);
public_js(public_base_url().('app/plugins/others/comment/shortcodes/assets/comment.js'), true);

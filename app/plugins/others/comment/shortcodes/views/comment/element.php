<?php
defined('PF_VERSION') OR header('Location:404.html');
?>
<script id="formReplyComment" type="text/x-handlebars-template">
    <div class="formComment">
    <form action="#" data-id="{{parent}}" data-type="reply">
    <div class="media">
    <div class="avatar media-object pull-left">
    <?php echo user_avatar(current_user('user-id'), '60px', 'author'); ?>
    </div>
    <div class="media-body">
    <div class="custom-field-form">
        <?php echo !empty($custom_field['form_comment']) ? $custom_field['form_comment'] : '' ?>
    </div>
    <textarea class="form-control" rows="1" name="message"></textarea>
    <input type="hidden" value="{{parent}}" name="parent"/>
    <button class="btnPostReply btn btn-theme-primary  btn-sm pull-right" type="button"><?php echo __('Reply', 'comment'); ?></button>
    </div>
    </div>
    </form>
    </div>
</script>

<script id="itemComment" type="text/x-handlebars-template">
    <div class="media">
    <div class="avatar media-object pull-left">
    {{{avatar}}}
    </div>
    <div class="media-body" data-id="{{id}}" data-token="{{token}}">
    <h5 class="media-heading cmt-block">
        <strong>{{author}}</strong>
        <span class="text-muted pull-right"> <?php echo __('a few seconds ago', 'comment'); ?></span>
    </h5>
    <div class="custom-field-message">
    <?php echo !empty($custom_field['item_js']) ? $custom_field['item_js'] : '' ?>
    </div>
    <div class="message" id="comment_id_{{id}}">
    <p>{{breaklines message}}</p>
    </div>
    <ul class="toolBar">
    <li>
    <i class="fa fa-pencil"></i>
    <a href="#" class="btnEdit" data-id="{{id}}"><?php echo __('Edit', 'comment'); ?></a>
    </li>
    <li>
    <i class="fa fa-share"></i>
    <a href="#" class="btnReply"><?php echo __('Reply', 'comment'); ?></a>
    </li>
    <li>
    <i class="fa fa-trash-o"></i>
    <a href="#" class="btnRemove"><?php echo __('Remove', 'comment'); ?></a>
    </li>
    </ul>
    </div>
    </div>    
</script>

<script id="formEditComment" type="text/x-handlebars-template">
    <div class="formComment edit">
    <form action="#" data-id="{{id}}" data-type="edit">
    <div class="media">
    <div class="media-body">
    <div class="custom-field-form">
        {{{form}}}
    </div>
    <textarea class="form-control" rows="1" name="message">{{message}}</textarea>
    <input type="hidden" value="{{id}}" name="id"/>
    <input type="hidden" value="{{token}}" name="token"/>
    <div class="actions pull-right">
    <i class="btnCancelComment glyphicon glyphicon-remove" title="<?php echo __('Close', 'comment') ?>"></i>
    <button class="btnEditComment btn btn-theme-primary  btn-sm" type="button"><?php echo __('Save', 'comment'); ?></button>
    </div>
    </div>
    </div>
    </form>
    </div>
</script>

<!--script id="itemEditFormCustomField" type="text/x-handlebars-template">
    <?php echo !empty($custom_field['item_js']) ? $custom_field['item_js'] : '' ?>
</script-->
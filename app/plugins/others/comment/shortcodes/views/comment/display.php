<?php
defined('PF_VERSION') OR header('Location:404.html');
if (isset($this->atts['key']) && $this->atts['key'] != ''):
?>
    <div class="row">
        <div class="col-md-12">
            <div id="id_<?php echo $this->atts['key']; ?>" class="commentWrap" data-key="<?php echo $this->atts['key']; ?>" data-url="<?php echo public_url('', true); ?>" data-token="<?php echo $this->atts['token']; ?>" data-order="<?php echo $this->atts['ordering']; ?>" data-approve="<?php echo $this->atts['approve_flag']; ?>" data-maximum="<?php echo $this->atts['maximum_characters']; ?>">
                <?php if (is_login()): ?>
                    <div class="formComment cmt-block">
                        <form action="#" data-type="comment">
                            <div class="media">
                                <div class="avatar media-object pull-left">
                                <?php echo user_avatar(current_user('user-id'), '60px', 'author', current_user('user-name')) ?>
                                </div>
                                <div class="media-body">
                                    <strong class="author"><?php echo current_user('user-name'); ?></strong>
                                    <div class="custom-field-form">
                                        <?php echo !empty($custom_field['form_comment']) ? $custom_field['form_comment'] : '' ?>
                                    </div>
                                    <textarea class="form-control" rows="1" name="message"></textarea>
                                    <input type="hidden" value="comment" name="type"/>
                                    <button class="btn btn-theme-primary  pull-right btnPost" type="button"><?php echo __('Comments', 'comment') ?></button>
                                </div>
                            </div>
                        </form>
                    </div>
                    <?php
                else:
                    echo '<p class="text-center">' . __('In order to comment, please sign in', 'comment') . '</p>';
                endif;
                ?>
                <h2 class="title">
                    <span><em class="totalComment"></em> <?php echo __('Comments', 'comment') ?></span>
                </h2>
                <hr/>
                <div class="listComment">
                    <div class="ajaxLoader">
                        <img src="<?php echo public_base_url().('app/plugins/others/comment/shortcodes/assets/img/loader.GIF'); ?>" alt="Loading" />
                    </div>
                    <div class="content">

                    </div>
                </div>
                <div class="pagination-comment text-right">
                </div>
            </div>
        </div>
    </div>
    <?php require_once dirname(__FILE__) . '/include.php'; ?>
    <script type="text/javascript">
        $(function() {
            initComment('<?php echo $this->atts['key']; ?>');
        });
    </script>
    <?php
    require_once dirname(__FILE__) . '/element.php';
 endif;
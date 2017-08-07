<?php defined('PF_VERSION') OR header('Location:404.html'); ?>
<?php if (isset($key)): ?>
    <?php
    require_once dirname(__FILE__) . '/include.php';
    $avg = !empty($ratings['avg']) ? $ratings['avg'] : '';
    ?>
    <div>
        <div id="rating_<?php echo $key ?>" data-token="<?php echo $token; ?>" data-url="<?php echo public_url('', true); ?>" class="ratingWrap">
            <div class="ratingBox" data-score="<?php echo $avg; ?>">
            </div>
            <strong class="showResult"><?php echo !empty($showRating) ? $showRating : '' ?></strong>
            <p class="messageRating"></p>
        </div>
    </div>
    <script type="text/javascript">
        $(function() {
            initRating('<?php echo $key; ?>', <?php echo empty($permission) ? 1 : 0; ?>);
        });
    </script>
    <?php
endif;
<?php
defined('PF_VERSION') OR header('Location:404.html');
?>
<!DOCTYPE html>
<html lang="<?php echo HTML_LANGUAGE; ?>">
<?php require dirname(__FILE__).'/header.php'; ?>
<body style="<?php echo 'background-color:' . $wrapper_background; ?>">
    <?php require dirname(__FILE__).'/navigation.php';?>
    <!-- Body -->
    <div class="wrapper" style="<?php echo!empty($background_option) ? $background_option : '' ?>">
        <!-- Topic Header -->
        <?php Pf::event()->on("theme-breadcrumb",'theme_breadcrumb',1000); ?>
        <?php echo Pf::event()->trigger("filter","theme-breadcrumb",''); ?>
        
        <div class="announcement-top">
        <?php echo Pf::shortcode()->exec("{announcement:display}"); ?>
        </div>
        <?php if (!empty($widgets['panel_1']) && has_active_widget($widgets['panel_1'], $active_widgets)) { ?>
        <div <?php if ((int) $layout ['layout_type'] != 1) { ?>class="container"<?php }?>>
            <?php load_widgets((is_array($widgets['panel_1'])) ? $widgets['panel_1'] : array(), $setting_data, $active_widgets); ?>
        </div>
        <?php } ?>
        <?php if ((int) $layout ['layout_type'] == 1) { ?> <div class="container"> <?php } ?>
            <?php echo (!empty($rs['page_content'])) ? $rs['page_content'] : ''; ?>
        <?php if ((int) $layout ['layout_type'] == 1) { ?> </div> <?php } ?>

        <?php if (!empty($widgets['panel_4']) && has_active_widget($widgets['panel_4'], $active_widgets)) { ?>
        <div <?php if ((int) $layout ['layout_type'] != 1) { ?>class="container"<?php }?>>
            <?php load_widgets((is_array($widgets['panel_4'])) ? $widgets['panel_4'] : array(), $setting_data, $active_widgets); ?>
        </div>
        <?php } ?>
    </div> <!-- / wrapper -->
    <?php require dirname(__FILE__).'/footer-copyright.php';?>
</body>
</html>

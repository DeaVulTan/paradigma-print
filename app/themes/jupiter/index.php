<?php
defined('PF_VERSION') OR header('Location:404.html');
?>
<!DOCTYPE html>
<html lang="<?php echo HTML_LANGUAGE; ?>">
<?php require dirname(__FILE__).'/header.php'; ?>
<body style="<?php echo 'background-color:' . $wrapper_background; ?>">
    <?php require dirname(__FILE__).'/navigation.php';?>
    <!-- Body -->
    <div class="wrapper" style="<?php echo!empty($background_option) ? $background_option : '' ?>"> <!-- wrapper -->
        <!-- Topic Header -->
        <?php Pf::event()->on("theme-breadcrumb",'theme_breadcrumb',1000); ?>
        <?php echo Pf::event()->trigger("filter","theme-breadcrumb",''); ?>
        <div class="container">
        <div class="row">
            <div class="col-sm-12">
        <div class="announcement-top">
        <?php echo Pf::shortcode()->exec("{announcement:display}"); ?>
        </div>
    <?php if (!empty($widgets['panel_1']) && has_active_widget($widgets['panel_1'], $active_widgets)) { ?>
            <div class="<?php echo $container_top = ' container_top'; ?>">
                <div class="row">
                    <div class="col-md-12">
    <?php load_widgets((is_array($widgets['panel_1'])) ? $widgets['panel_1'] : array(), $setting_data, $active_widgets); ?>
                    </div>
                </div>
            </div>
        <?php } ?>
        <?php
        $col1 = 4;
        $col2 = 4;
        $col3 = 4;
        if (empty($widgets['panel_2']) || !has_active_widget($widgets['panel_2'], $active_widgets)) {
            $col2 = $col2 + $col1;
        }

        if (empty($widgets['panel_3']) || !has_active_widget($widgets['panel_3'], $active_widgets)) {
            $col2 = $col2 + $col3;
        }
        ?>
        <div class="<?php echo (!isset($container_top)) ? ' container_top' : ''; ?>">
            <!-- Features -->
            <div class="row">
                <?php
                if (!empty($widgets['panel_2']) && has_active_widget($widgets['panel_2'], $active_widgets)) {
                    ?>
                    <div class="col-sm-<?php echo $col1; ?> col-md-<?php echo $col1 - 1; ?>">
                    <?php load_widgets((is_array($widgets['panel_2'])) ? $widgets['panel_2'] : array(), $setting_data, $active_widgets); ?>
                    </div>
                    <?php } ?>
                <div class="col-sm-<?php echo $col2; ?> col-md-<?php echo $col2 + 1; ?>">
                <?php echo (!empty($rs['page_content'])) ? $rs['page_content'] : ''; ?>
                </div>
                    <?php if (!empty($widgets['panel_3']) && has_active_widget($widgets['panel_3'], $active_widgets)) { ?>
                    <div class="col-sm-<?php echo $col3; ?> col-md-<?php echo $col3 - 1; ?>">
                    <?php load_widgets((is_array($widgets['panel_3'])) ? $widgets['panel_3'] : array(), $setting_data, $active_widgets); ?>
                    </div>
                <?php } ?>
            </div>
        </div>
    <?php if (!empty($widgets['panel_4']) && has_active_widget($widgets['panel_4'], $active_widgets)) { ?>
            <div>
                <div class="row">
                    <div class="col-md-12">
                        <?php load_widgets((is_array($widgets['panel_4'])) ? $widgets['panel_4'] : array(), $setting_data, $active_widgets); ?>
                    </div>
                </div>
            </div>
    <?php } ?>
            </div>
        </div>
        </div>
    </div> <!-- / wrapper -->
    <?php require dirname(__FILE__).'/footer-copyright.php';?>
</body>
</html>

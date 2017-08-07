<?php defined('PF_VERSION') OR header('Location:404.html'); 
$theme_options = get_option('theme_options');
$theme_options['color_scheme'] = (!empty($theme_options['color_scheme']))?'_'.$theme_options['color_scheme']:'';
$theme_options['google_fonts'] = (!empty($theme_options['google_fonts']))?$theme_options['google_fonts']:'';

public_css(RELATIVE_PATH . '/media/assets/font-awesome/css/font-awesome.min.css',true);

public_css(RELATIVE_PATH.'/media/assets/ui-element/css/ui-elements.css',true);
public_css('css/style'.$theme_options['color_scheme'].'.css');
public_css('css/lightbox.css');
public_css('css/custom.css');
public_css('css/animate.css');
public_js('js/custom.js');
public_js('js/menu-widgets.js');
public_js('js/scrolltopcontrol.js');
?>
<head>
    <meta charset="<?php echo Pf::event()->trigger('filter','public_meta_charset',get_head_info('charset')); ?>">
    <meta http-equiv="Content-Type" content="text/html; charset=<?php echo Pf::event()->trigger('filter','public_meta_charset',get_head_info('charset')); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="<?php echo Pf::event()->trigger('filter','public_meta_description',get_head_info('description')); ?>">
    <meta name="keywords" content="<?php echo Pf::event()->trigger('filter','public_meta_keywords',get_head_info('keywords')); ?>">
    <meta name="author" content="Vitubo">
    <link rel="shortcut icon" href="<?php echo RELATIVE_PATH; ?>/app/themes/<?php echo $theme; ?>/img/favicon.ico">
    <link rel="canonical" href="<?php echo site_url() . RELATIVE_PATH; ?>" >
    <title><?php echo Pf::event()->trigger('filter','public_title',get_head_info('title')); ?></title>
    <?php echo $theme_options['google_fonts']; ?>
    
    <!-- include css (do not remove this line) -->
    
    <!-- Resources -->
    <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
    <script src="https://oss.maxcdn.com/libs/respond.js/1.3.0/respond.min.js"></script>
    <![endif]-->
    <!-- Bootstrap core JavaScript
    ================================================== -->
    <!-- Placed at the end of the document so the pages load faster -->
    <script src="<?php echo RELATIVE_PATH; ?>/media/assets/jquery/jquery-1.11.1.min.js"></script>
    <script src="<?php echo RELATIVE_PATH; ?>/media/assets/bootstrap/js/bootstrap.js"></script>
</head>
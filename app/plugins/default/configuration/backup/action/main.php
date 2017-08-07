<?php defined('PF_VERSION') OR header('Location:404.html');?>
<?php

/*
 * * 
 * @package  Vitubo
 * @author  Vitubo Team 
 * @copyright Vitubo Team
 * @link  http://www.vitubo.com
 * @since  Version 1.0
 * @filesource
 *
 */
if (is_admin()) {
    $bkrs = new Pf_Backup();
    $bak_folder = get_configuration('backup_dir');
    if (isset($_GET['action'])) {
        switch ($_GET['action']) {
            case 'upload':
                $message =  $bkrs->upload($_FILES["file"]);
                break;
            case 'restore':
                $message = (!empty($_GET['after'])&&$_GET['after']=='delete')?$bkrs->restore($_GET['file'],'delete'):$bkrs->restore($_GET['file']);
                break;
            case 'backup':
                $message = $bkrs->backup();
                die($message);
                break;
            case 'delete':
                $file = $bak_folder . $_GET['file'];
                if(is_file($file))
                    unlink($file);
                die(__('Backup file is removed successfully','configuration'));
                break;
            case 'download':
                $bkrs->download($_GET['id']);
                break;
            case 'unset':
                unset($_SESSION['success']);
                break;
            case 'list':
                $bkrs->ajax_list();
                break;
        }
    }

    require_once abs_plugin_path(__FILE__) . '/configuration/backup/action/main_tpl.php';
} else
    echo __('Access Denied!','configuration');
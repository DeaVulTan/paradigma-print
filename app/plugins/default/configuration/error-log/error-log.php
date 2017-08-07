<?php
defined('PF_VERSION') OR header('Location:404.html');

if(isset($_REQUEST['id'])){
    $dir = ABSPATH."/tmp/logs/";
    chdir($dir);
    $dh = glob('*.log');
    krsort($dh);
    $file = chdir($dir);
    $id = '';
    $id = $_POST['id'];
    if(file_exists($id)){
            unlink($id);
        }
}else{
    $dir = ABSPATH."/tmp/logs/";
    chdir($dir);
    $dh = glob('*.log');
    krsort($dh);
    $file = chdir($dir);
    $stt = 1;
    require abs_plugin_path(__FILE__).'/configuration/error-log/templates/index.php';
}
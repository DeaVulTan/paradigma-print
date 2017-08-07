<?php
defined('PF_VERSION') OR header('Location:404.html');
ob_start();
function get_mysqlinfo(){
    $db = Pf::database();
    $db->query ('SELECT version()');
    $v = $db->fetch_assoc_all();
    foreach ($v as $item){
        $item['version()'];
    }
    return  $item['version()'];
}
phpinfo();
$phpinfo = array('phpinfo' => array());
if(preg_match_all('#(?:<h2>(?:<a name=".*?">)?(.*?)(?:</a>)?</h2>)|(?:<tr(?: class=".*?")?><t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>(?:<t[hd](?: class=".*?")?>(.*?)\s*</t[hd]>)?)?</tr>)#s', ob_get_clean(), $matches, PREG_SET_ORDER))
    foreach($matches as $match)
        if(strlen($match[1]))
            $phpinfo[$match[1]] = array();
        elseif(isset($match[3]))
        $phpinfo[end(array_keys($phpinfo))][$match[2]] = isset($match[4]) ? array($match[3], $match[4]) : $match[3];
        else
            $phpinfo[end(array_keys($phpinfo))][] = $match[2];
        
require abs_plugin_path(__FILE__).'/configuration/system-info/templates/index.php';
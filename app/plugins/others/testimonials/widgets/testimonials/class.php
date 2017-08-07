<?php
    defined('PF_VERSION') OR header('Location:404.html');
function Pf_get_testimonial(){
    $db= Pf::database();
    $db->select('testimonial_content,testimonial_avatar,testimonial_name,testimonial_info',''.DB_PREFIX.'testimonials',"`testimonial_status`='1'");
    $result =$db->fetch_assoc_all();
    return $result;
}
function list_testimonial(){
    $db= Pf::database();
    $db->select('id,testimonial_name',''.DB_PREFIX.'testimonials',"`testimonial_status`='1'");
    $data   =   $db->fetch_assoc_all();
    $list   =   array();
    foreach($data as $item){
        $list[$item['id']]  =   $item['testimonial_name'];
    }
    return $list;
}
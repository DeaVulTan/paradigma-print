<?php
function __navigation($data,$details,$controller, $children = false){
    foreach ($data as $k => $v){
        $detail = $controller->menu_shortcode_model->menu_detail($v['id'],$details);
        if (!isset($detail['status']) || $detail['status']  != 1) continue;
        $name = $detail['name'];
        
        if(!empty($detail['icon']) && $detail['icon']!=''){
            switch ($detail['icontype']) {
                case 'css':
                    if(isset($detail['color']) && $detail['color'] != NULL){$color = $detail['color'];}else{$color = 'initial';}
                    $name   =   "<i class='".$detail['icon']."' style='color:".$color."'></i> ".$detail['name'];
                    break;
                case 'img':
                    $name   =   is_file(urldecode($detail['icon']))?"<img src='".public_base_url().$detail['icon']."' style = 'height:18px; vertical-align:top; '/> ".$detail['name']:"<i class='fa fa-bars'></i> ".$detail['name'];
            }
        }else{
            $name   =   $detail['name'];
        }
        
        $caret = '';
        $description = '';
        $li_class = '';
        $a_atts = '';
        $a_href = '';
        
        if (empty($v['children'])){
            $a_href = $controller->menu_shortcode_model->get_url($detail['type'], $detail['call'], $controller->view->page_links);
        }
        
        if(!empty($detail['options'])){
            $a_href .= $detail['options'];
        }
        
        $active = "";
        
        $post_page     = get_configuration('page_lists', 'pf_post');
        if( public_url('',true) == $a_href.'/'){
            $active = "active";
        }else if(public_url($post_page,false) == $a_href){
            $page_detail     = get_configuration('page_detail', 'pf_post');
            if($page_detail == $_GET['pf_page_url']){
                $active = "active";
            }
        }
        
        if(!empty($v['children']) && is_array($v['children'])){
            $caret = "<i class='caret'></i>";
            $li_class = 'dropdown menu-withdraw';
            $a_atts = 'class="dropdown-toggle hover-dropdown" data-toggle="dropdown"';
            $a_href = '#';
        }
        
        if (!empty($detail['desc'])){
            $description = "<p style='color: rgba(55,58,65,0.4); font-size: 11px; margin: 0; line-height: 13px;'>".$detail['desc'].'</p>';
        }
        
        if (!empty($v['children']) && is_array($v['children']) && $children == true){
            $caret = "";
            $li_class = 'dropdown-submenu';
        }else if ($children == true){
            $caret = "";
            $li_class = '';
        }
        
        
        $li_class .= ' '.$active;
?>
    <li class="<?php echo $li_class; ?> menu-withdraw">
      <a href="<?php echo $a_href; ?>" <?php echo $a_atts; ?>>
        <?php echo $name; ?> <?php echo $caret; ?> <?php echo $description;?>
      </a>
<?php if(!empty($v['children']) && is_array($v['children'])){ ?>
    <ul class='dropdown-menu'>
        <?php echo __navigation($v['children'], $details,$controller, true); ?>
    </ul>
<?php } ?>
    </li>
<?php 
    }
}
?>
<?php if(!empty($this->navigation)){ __navigation($this->navigation[0], $this->navigation[1],$this->controller);} ?>
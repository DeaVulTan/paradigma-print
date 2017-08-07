<?php
function __navigation_footer($data,$details,$controller) { 
        if (!empty($data)) {
            foreach ($data as $k => $v){
                $detail = $controller->menu_shortcode_model->menu_detail($v['id'],$details);
                if (!isset($detail['status']) || $detail['status']  != 1) continue;
                
                $a_href = $controller->menu_shortcode_model->get_url($detail['type'], $detail['call'], $controller->view->page_links);
                
                echo "<li>";
                echo "<a href='" . $a_href . "'>" . $detail['name'] . "</a>";
                if(!empty($v['children']) && is_array($v['children'])){
                    echo "<ul class='widget_submenu'>";
                    __navigation_footer($v['children'], $details,$controller);
                    echo "</ul>";
                }
                echo "</li>";
            }
        }
    }
?>
<ul>
<?php __navigation_footer($this->navigation_footer[0], $this->navigation_footer[1],$this->controller); ?>
</ul>
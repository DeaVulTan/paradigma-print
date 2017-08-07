<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Menu_Shortcode extends Pf_Shortcode_Controller{
    public function navigation($atts,$content = '',$tag){
        $this->load_model('menu-shortcode');
        
        $this->view->navigation = $this->menu_shortcode_model->navigation();
        $this->view->page_links = $this->menu_shortcode_model->all_page_links();
        $this->view->render();
    }
    
    public function footer($atts,$content = '',$tag){
        $this->load_model('menu-shortcode');
        
        $this->view->navigation_footer = $this->menu_shortcode_model->get_menu($atts['id']);
        $this->view->page_links = $this->menu_shortcode_model->all_page_links();
        
        $this->view->render();
    }
    public function display($atts, $content = '',$tags){
        $this->load_model('menu-shortcode');
        $id = (!empty($atts['id'])) ? $atts['id'] : '';
        $type = (!empty($atts['type'])) ? $atts['type'] : '';
        
        if ($type == 'vertical'){
            $atts['content'] = $this->menu_shortcode_model->show_menu($id,'v');
        }elseif($type=='main'){
            $atts['content'] = $this->menu_shortcode_model->show_menu($id,'top');
        }elseif($type=='footer'){
            $atts['content'] = $this->menu_shortcode_model->show_menu($id,'footer');
        }elseif($type == 'accordion'){
            $atts['content'] = $this->menu_shortcode_model->show_menu($id, 'accordion');
        }else{
            $atts['content'] = $this->menu_shortcode_model->show_menu($id,'',0);
        }
        
        $this->view->atts = $atts;
        $this->view->render();
    }
}
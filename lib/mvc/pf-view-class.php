<?php
class Pf_view extends Pf_Base_Object{
    protected $controller;
    protected $path;
    
    public function __construct(& $controller) {
        $this->controller = $controller;
        $this->action = $controller->action;
        $this->page = $controller->page;
        
        $controller_class = get_class($controller);
        $view_directory = str_replace(array('_shortcode','-shortcode','_controller','-controller','pf_','pf-','_widget','-widget'),array('', '','', '','','','', ''), strtolower($controller_class));
        $this->path = $controller->path.'/views/'.str_replace('_', '-', $view_directory).'/';
    }
    
    public function render($template = null,$full_path = false) {
        $template_file = '';
        $template_file_name = '';
        if ($this->controller->custom_template == null){
            $template_file_name = strtolower(((!empty($_GET[$this->controller->action]))?$_GET[$this->controller->action]:'index')).'.php';
        }else{
            $template_file_name = strtolower($this->controller->custom_template).'.php';
        }
        
        $template_directory = $this->path;
        
        if ($this->controller->controller_type != 'admin'){
            $theme_template_directory = '';
            
            if (strpos($this->path, 'app/plugins/default') !== false){
                $active_theme = get_option ( 'active_theme' );
                $theme_template_directory = str_replace(ABSPATH.'/app/plugins/default', ABSPATH.'/app/themes/'.$active_theme.'/plugins/default', $template_directory);
            }else if (strpos($this->path, 'app/plugins/others') !== false){
                $active_theme = get_option ( 'active_theme' );
                $theme_template_directory = str_replace(ABSPATH.'/app/plugins/others', ABSPATH.'/app/themes/'.$active_theme.'/plugins/others', $template_directory);
            }
            
            if ($theme_template_directory != '' && is_file($theme_template_directory.$template_file_name)){
                $template_directory = $theme_template_directory;
            }
        }
        if ($template === null){
            $template_file = $template_directory.$template_file_name;
        }else{
            if ($full_path == true){
                $template_file = $template;
            }else{
                $template_file = $template_directory.$template.'.php';
            }
        }
        if (is_file($template_file)){
            require $template_file;
        }
    }
    
    public function fetch($template = null){
        ob_start();
        $this->render($template);
        $content = ob_get_contents();
        ob_get_clean();
        
        return $content;
    }
    
    public function error_class($key){
        if (!empty($this->errors[$key])){
            echo ' has-error';
        }else{
            echo  '';
        }
    }
    
    public function error_message($key){
        if (!empty($this->errors[$key])){
            if (is_array($this->errors[$key])){
                echo '<span class="help-block">'.implode('<br>', $this->errors[$key]).'</span>';
            }else{
                echo '<span class="help-block">'.$this->errors[$key].'</span>';
            }
        }else{
            echo '';
        }
    }
}
<?php
defined('PF_VERSION') OR header('Location:404.html');
class Pf_Setting {
    public $properties = array ();
    public function add_title($title_key, $title) {
        if (empty ( $this->properties [$title_key] )) {
            $this->properties [$title_key] ['title'] = $title;
        }
    }
    public function add_element($label, $elenent, $title_key) {
        if (! empty ( $this->properties [$title_key] )) {
            if (is_array ( $elenent )) {
                $this->properties [$title_key] ['elements'] [] = array (
                        $label,
                        implode ( ' ', $elenent ) 
                );
            } else {
                $this->properties [$title_key] ['elements'] [] = array (
                        $label,
                        $elenent 
                );
            }
        }
    }
    public function get_element_value($title_key, $id) {
        $val = '';
        $settings = get_option ( 'settings' );
        if (! empty ( $settings ) && is_array ( $settings ) && ! empty ( $settings [$title_key] ) && isset ( $settings [$title_key] [$id] )) {
            $val = $settings [$title_key] [$id];
        }
        
        return $val;
    }
}
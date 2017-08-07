<?php
defined ( 'PF_VERSION' ) or header ( 'Location:404.html' );
class Google_Shortcode extends Pf_Shortcode_Controller {
    public function map($atts, $content = null, $tag) {
        public_js ( 'http://maps.google.com/maps/api/js?sensor=false', true );
        public_js(RELATIVE_PATH.'/media/assets/gomap-1.3.2/jquery.gomap-1.3.2.min.js',true);
        
        $atts ['address'] = (! empty ( $atts ['address'] )) ? $atts ['address'] : '';
        $atts ['type'] = (! empty ( $atts ['type'] )) ? $atts ['type'] : 'satellite';
        $atts ['width'] = (! empty ( $atts ['width'] )) ? $atts ['width'] : '100%';
        $atts ['height'] = (! empty ( $atts ['height'] )) ? $atts ['height'] : '300px';
        $atts ['zoom'] = (! empty ( $atts ['zoom'] )) ? $atts ['zoom'] : '14';
        $atts ['scrollwheel'] = (! empty ( $atts ['scrollwheel'] )) ? $atts ['scrollwheel'] : 'true';
        $atts ['scale'] = (! empty ( $atts ['scale'] )) ? $atts ['scale'] : 'true';
        $atts ['zoom_pancontrol'] = (! empty ( $atts ['zoom_pancontrol'] )) ? $atts ['zoom_pancontrol'] : 'true';
        
        if ($atts ['scrollwheel'] == 'yes') {
            $atts ['scrollwheel'] = 'true';
        } elseif ($atts ['scrollwheel'] == 'no') {
            $atts ['scrollwheel'] = 'false';
        }
        
        if ($atts ['scale'] == 'yes') {
            $atts ['scale'] = 'true';
        } elseif ($atts ['scale'] == 'no') {
            $atts ['scale'] = 'false';
        }
        
        if ($atts ['zoom_pancontrol'] == 'yes') {
            $atts ['zoom_pancontrol'] = 'true';
        } elseif ($atts ['zoom_pancontrol'] == 'no') {
            $atts ['zoom_pancontrol'] = 'false';
        }
        
        $addresses = explode ( '|', $atts ['address'] );
        
        $mk = '';
        $sp = '';
        foreach ( $addresses as $v ) {
            $mk .= $sp . "{
                            address: '{$v}',
                            html: {
                                content: '{$v}',
                                popup: true
                            }
                        }";
            $sp = ',';
        }
        $google_map_id = "gmap-" . uniqid () . rand ( 1, 100 );
        
        $this->view->google_map_id = $google_map_id;
        $this->view->atts = $atts;
        $this->view->addresses = $addresses;
        $this->view->mk = $mk;
        
        $this->view->render();
    }
}
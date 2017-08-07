<?php
$html = '<div id="' . $this->google_map_id . '" style="width:' . $this->atts['width'] . ';height:' . $this->atts['height'] . ';"></div>';
$html .= "<script type='text/javascript'>
                    jQuery(document).ready(function($) {
                    jQuery('#" . $this->google_map_id . "').goMap({
                        address: '" . $this->addresses[0] . "',
                        zoom: " . $this->atts['zoom'] . ",
                            scrollwheel: " . $this->atts['scrollwheel'] . ",
                            scaleControl: " . $this->atts['scale'] . ",
                            navigationControl: " . $this->atts['zoom_pancontrol'] . ",
                        maptype: '" . $this->atts['type'] . "',
                        markers: [" . $this->mk . "]
                });
            });
        </script>";

echo $html;
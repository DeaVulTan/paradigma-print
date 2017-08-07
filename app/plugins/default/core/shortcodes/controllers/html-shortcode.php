<?php
defined('PF_VERSION') OR header('Location:404.html');
class Html_Shortcode extends Pf_Shortcode_Controller{
    public function general($atts, $content = null, $tag){
        $this->view->atts = $atts;
        $this->view->content = $content;
        $this->view->tag = $tag;
        $this->view->render();
    }
    
    public function get($atts, $content = null, $tag){
        if ((isset($atts['source']) && $atts['source'] != '')) {
            $source = $atts['source'];
            if (!preg_match('/^http/', $source)) {
                $file = ABSPATH . '/' . $source;
                if (preg_match('/.htm(l){0,1}$/', $file) && file_exists($file) && strpos($file, '..') === false) {
                    $contents = @file_get_contents($file);
                    if ($contents !== false) {
                        if(isset($atts['shortcode']) && $atts['shortcode'] == false){
                            return $contents;
                        }
                        return Pf::shortcode()->exec($contents);
                    }
                }
            }else{
                if ($fp = @curl_init($source)) {
                    $ch = curl_init();
                    $timeout = 5; // set to zero for no timeout
                    curl_setopt ($ch, CURLOPT_URL, $source);
                    curl_setopt ($ch, CURLOPT_RETURNTRANSFER, 1);
                    curl_setopt ($ch, CURLOPT_CONNECTTIMEOUT, $timeout);
                    $file_contents = curl_exec($ch);
                    curl_close($ch);
                    return $file_contents;
                }
            }
        }
        return $contents;
    }
    
    public function script($atts, $content = null, $tag){
        $script = "";
        $script .= '<script type="text/javascript">';
        $script .=!empty($content) ? str_replace(array("<br />", "<br>"), "\n", $content) : '';
        $script .= '</script>';
        
        return $script;
    }
    
    public function hr($atts, $content = null, $tag) {
    
        return '<hr ' . parse_atts($atts) . '/>';
    }
    
    public function title($atts, $content = null, $tag) {
        $content = !empty($content) ? $content : '';
        $size = !empty($atts['size']) ? $atts['size'] : 2;
        
        $this->view->content = $content;
        $this->view->size = $size;
        $this->view->render();
    }
    
    public function comment($content = null) {
        return "<!-- {$content} -->";
    }
    
    
    public function alert($atts, $content = null, $tag) {
        $atts['class'] = (isset($atts['class'])) ? $atts['class'] : '';
        switch (strtolower(trim($tag))) {
            case 'alert-success':
                $atts['class'] = 'alert alert-success ' . $atts['class'];
                break;
            case 'alert-info':
                $atts['class'] = 'alert alert-info ' . $atts['class'];
                break;
            case 'alert-warning':
                $atts['class'] = 'alert alert-warning ' . $atts['class'];
                break;
            case 'alert-danger':
                $atts['class'] = 'alert alert-danger ' . $atts['class'];
                break;
        }
        $this->view->atts = $atts;
        $this->view->content = $content;
        $this->view->render();
    }
    
    public function table($atts, $content = null) {
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        return "<table " . parse_atts($atts) . ">".$content."</table>";
    }
    
    public function tr($atts, $content = null) {
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        return "<tr " . parse_atts($atts) . ">$content</tr>";
    }
    
    public function td($atts, $content = null) {
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        return "<td " . parse_atts($atts) . ">$content</td>";
    }
    
    public function th($atts, $content = null) {
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        return "<th " . parse_atts($atts) . ">$content</th>";
    }
    
    public function i($atts, $content = null) {
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        $class = !empty($atts['class']) ? "class='" . $atts['class'] . "'" : '';
        $datadevice = !empty($atts['data-device']) ? "data-device='" . $atts['data-device'] . "'" : '';
        $option = !empty($atts['option']) ? "" . $atts['option'] . "" : '';
        return "<i $class $option $datadevice>$content</i>";
    }
    
    public function source($atts = null, $contacts = null) {
        $contents = html_entity_decode($contacts);
        return $contents;
    }
    
    public function code($atts = null, $content = null) {
        $linenums = isset($atts['linenums']) ? (int) $atts['linenums'] : 0;
        $class = '' . isset($atts['class']) ? $atts['class'] : '';
        $js = '';
        if (isset($atts['highline'])) {
            $js = '';
        } else {
            public_css(RELATIVE_PATH.'/media/assets/prettify/prettify.css',true);
            public_js(RELATIVE_PATH.'/media/assets/prettify/prettify.js',true);
        }
    
        return $js . '
            <div class="highlight ' . $class . '" onload="prettyPrint()"><pre><code class="html prettyprint linenums:' . $linenums . ' languague-css">' . $content . '</code></pre></div>';
    }
    
    public function fullwidth($atts, $content = null, $tag){
        $atts['style'] = (isset($atts['style']))?$atts['style']:'';
    
        if( isset($atts['backgroundattachment'])) {
            $atts['style'] .= sprintf( 'background-attachment:%s;', $atts['backgroundattachment'] );
            unset($atts['backgroundattachment']);
        }
    
        if( isset($atts['backgroundcolor']) ) {
            $atts['style'] .= sprintf( 'background-color:%s;', $atts['backgroundcolor'] );
            unset($atts['backgroundcolor']);
        }
    
        if( isset($atts['backgroundimage']) ) {
            $atts['style'] .= sprintf( 'background-image: url(%s);', $atts['backgroundimage'] );
            unset($atts['backgroundimage']);
        }
    
        if( isset($atts['backgroundposition']) ) {
            $atts['style'] .= sprintf( 'background-position:%s;', $atts['backgroundposition'] );
            unset($atts['backgroundposition']);
        }
    
        if( isset($atts['backgroundrepeat']) ) {
            $atts['style'] .= sprintf( 'background-repeat:%s;', $atts['backgroundrepeat'] );
        }
    
        if( isset($atts['backgroundrepeat']) && $atts['backgroundrepeat'] == 'no-repeat') {
            $atts['style'] .= '-webkit-background-size:cover;-moz-background-size:cover;-o-background-size:cover;background-size:cover;';
            unset($atts['backgroundrepeat']);
        }
    
        if( isset($atts['bordercolor']) ) {
            $atts['style'] .= sprintf( 'border-color:%s;', $atts['bordercolor'] );
            unset($atts['bordercolor']);
        }
    
        if( isset($atts['bordersize']) ) {
            $atts['style'] .= sprintf( 'border-bottom-width: %s;border-top-width: %s;', $atts['bordersize'], $atts['bordersize'] );
            unset($atts['bordersize']);
        }
    
        if( isset($atts['borderstyle']) ) {
            $atts['style'] .= sprintf( 'border-bottom-style: %s;border-top-style: %s;', $atts['borderstyle'], $atts['borderstyle'] );
            unset($atts['borderstyle']);
        }
    
        if( isset($atts['paddingbottom']) ) {
            $atts['style'] .= sprintf( 'padding-bottom:%s;', $atts['paddingbottom'] );
            unset($atts['paddingbottom']);
        }
    
        if( isset($atts['paddingtop']) ) {
            $atts['style'] .= sprintf( 'padding-top:%s;', $atts['paddingtop'] );
            unset($atts['paddingtop']);
        }
        return '<div ' . parse_atts($atts) . '><div class="container">' . Pf::shortcode()->exec($content) . '</div></div>';
    }
    
    
    
    public function button($atts, $content = null) {
        $defaults = array (
                'class' => "btn btn-color" 
        );
        $script = '';
        if (isset ( $atts ['data-toggle'] )) {
            switch (strtolower ( trim ( $atts ['data-toggle'] ) )) {
                case 'tooltip' :
                    $tooltip_class = 'tooltop-' . uniqid () . rand ( 1, 1000 );
                    if (isset ( $atts ['class'] )) {
                        $atts ['class'] = $atts ['class'] . ' ' . $tooltip_class;
                    } else {
                        $defaults ['class'] = $defaults ['class'] . ' ' . $tooltip_class;
                    }
                    $script = "
                    <script>
                    $('.{$tooltip_class}').tooltip();
                    </script>
                    ";
                    break;
                case 'popover' :
                    $popover_class = 'popover-' . uniqid () . rand ( 1, 1000 );
                    if (isset ( $atts ['class'] )) {
                        $atts ['class'] = $atts ['class'] . ' ' . $popover_class;
                    } else {
                        $defaults ['class'] = $defaults ['class'] . ' ' . $popover_class;
                    }
                    $script = "
                            <script>
                            $('.{$popover_class}').popover();
                            </script>
                            ";
                    break;
            }
        }
        $type = isset ( $atts ['type'] ) ? $atts ['type'] : 'button';
        $html = "<button type=\"" . $type . "\" " . parse_atts ( $atts, $defaults ) . ">" . Pf::shortcode ()->exec ( $content ) . "</button>" . $script;
        return $html;
    }
    
    function button_group($atts, $content = null) {
        $defaults = array (
                'class' => "btn-group" 
        );
        $script = '';
        if (isset ( $atts ['data-toggle'] )) {
            switch (strtolower ( trim ( $atts ['data-toggle'] ) )) {
                case 'tooltip' :
                    $tooltip_class = 'tooltop-' . uniqid () . rand ( 1, 1000 );
                    if (isset ( $atts ['class'] )) {
                        $atts ['class'] = $atts ['class'] . ' ' . $tooltip_class;
                    } else {
                        $defaults ['class'] = $defaults ['class'] . ' ' . $tooltip_class;
                    }
                    $script = "
                    <script>
                    $('.{$tooltip_class}').tooltip();
                    </script>
                    ";
                    break;
                case 'popover' :
                    $popover_class = 'popover-' . uniqid () . rand ( 1, 1000 );
                    if (isset ( $atts ['class'] )) {
                        $atts ['class'] = $atts ['class'] . ' ' . $popover_class;
                    } else {
                        $defaults ['class'] = $defaults ['class'] . ' ' . $popover_class;
                    }
                    $script = "
                            <script>
                            $('.{$popover_class}').popover();
                            </script>
                            ";
                    break;
            }
        }
        
        return "<div " . parse_atts ( $atts, $defaults ) . ">" . Pf::shortcode ()->exec ( $content ) . "</div>" . $script;
    }
    
    
    public function icon($atts, $content = null) {
        $type = 'fa';
        if (! empty ( $atts ['type'] )) {
            $type = $atts ['type'];
        }
        $type = strtolower ( $type );
        switch ($type) {
            case 'fa' :
                $atts ['name'] = (! empty ( $atts ['name'] )) ? 'fa-' . $atts ['name'] : '';
                $atts ['size'] = (! empty ( $atts ['size'] )) ? ' fa-' . $atts ['size'] : '';
                $icon_atts = array (
                        'class' => "fa " . $atts ['name'] . $atts ['size'] 
                );
                break;
        }
        $script = '';
        if (isset ( $atts ['data-toggle'] )) {
            switch (strtolower ( trim ( $atts ['data-toggle'] ) )) {
                case 'tooltip' :
                    $tooltip_class = 'tooltop-' . uniqid () . rand ( 1, 1000 );
                    $icon_atts ['class'] = $icon_atts ['class'] . ' ' . $tooltip_class;
                    $script = "
                    <script>
                    $('.{$tooltip_class}').tooltip();
                    </script>
                    ";
                    break;
                case 'popover' :
                    $popover_class = 'popover-' . uniqid () . rand ( 1, 1000 );
                    $icon_atts ['class'] = $icon_atts ['class'] . ' ' . $popover_class;
                    $script = "
                            <script>
                            $('.{$popover_class}').popover();
                            </script>
                            ";
                    break;
            }
        }
        
        return "<i " . parse_atts ( $icon_atts ) . " ></i>" . $script;
    }
    
    
    public function row($atts, $content = null) {
        $atts['class'] = (!empty($atts['class'])) ? 'row ' . $atts['class'] : 'row';
    
        return "<div " . parse_atts($atts) . ">" . Pf::shortcode()->exec($content) . "</div>";
    }
    public function container($atts, $content = null) {
        $atts['class'] = (!empty($atts['class'])) ? 'container ' . $atts['class'] : 'container';
    
        return "<div " . parse_atts($atts) . ">" . Pf::shortcode()->exec($content) . "</div>";
    }
    public function col($atts, $content = null) {
        $atts['class'] = (!empty($atts['class'])) ? $atts['class'] : 'col-sm-12';
    
        return "<div " . parse_atts($atts) . ">" . Pf::shortcode()->exec($content) . "</div>";
    }
    
    public function tabs($atts, $content = null) {
        global $pf_tab_ids;
        $pf_tab_ids = array();
    
        global $pf_tab_id;
        $pf_tab_id = uniqid() . rand(1, 1000);
    
        global $pf_tab_index;
        $pf_tab_index = 0;
    
        if (!empty($atts['value'])) {
            $atts['style'] = (!empty($atts['style'])) ? $atts['style'] . ' margin-bottom:10px;' : 'margin-bottom:10px;';
            $nav_tabs = '<ul class="nav nav-tabs" id="' . $pf_tab_id . '" ' . parse_atts($atts) . '>';
            $tabs = explode('|', $atts['value']);
            foreach ($tabs as $k => $tab) {
                $tab_id = uniqid() . rand(1, 1000);
                $pf_tab_ids[$k] = $tab_id;
                $active = ($k == 0) ? 'class="active"' : '';
                $nav_tabs .= '<li ' . $active . '><a href="#' . $tab_id . '" data-toggle="tab">' . Pf::shortcode()->exec($tab) . '</a></li>';
            }
            $nav_tabs .= '</ul>';
            $nav_tabs .= '<div class="tab-content">';
            $nav_tabs .= Pf::shortcode()->exec($content);
            $nav_tabs .= '</div>';
        }
        $pf_tab_ids = array();
        $pf_tab_id = '';
        $pf_tab_index = 0;
    
        return $nav_tabs;
    }
    
    
    public function tab($atts, $content = null) {
        global $pf_tab_ids;
        global $pf_tab_index;
        $active = ($pf_tab_index == 0) ? 'active' : '';
        $tab = '<div class="tab-pane ' . $active . '" id="' . $pf_tab_ids[$pf_tab_index] . '">';
        $tab .= Pf::shortcode()->exec($content);
        $tab .= '</div>';
        $pf_tab_index++;
    
        return $tab;
    }
    
    public function accordion($atts, $content = null) {
        global $accordion_id;
        $accordion_id = uniqid() . rand(1, 1000);
    
        $this->view->accordion_id = $accordion_id;
        $this->view->str_atts = parse_atts($atts);
        $this->view->content = $content;
        
        return $this->view->fetch();
    }
    
    public function collapse($atts, $content = null) {
        global $accordion_id;
        $active = !empty($atts['active']) ? 1 : 0;
        $collapse_id = uniqid() . rand(1, 100);
        // panel
        
        // End panel
    
//         return $collapse;
        
        $this->view->active = $active;
        $this->view->accordion_id = $accordion_id;
        $this->view->collapse_id = $collapse_id;
        $this->view->atts = $atts;
        $this->view->content = $content;
        
        return $this->view->fetch();
    }
    
    public function br($atts, $content = null) {
    
        return '<br ' . parse_atts($atts) . '>';
    }
    
    public function img($atts, $content = null) {
        $src = Pf::shortcode()->exec("{php:url url='" . $atts['src'] . "'}{/php:url}");
        unset($atts['relative']);
        unset($atts['src']);
        return '<img src="' . $src . '" ' . parse_atts($atts) . '>';
    }
    
    
    
    public function ribbon($atts, $content = null) {
        $text = !empty($text) ? $text : '';
        return "<h1 class='ribbon'><strong class='ribbon-content'>$text</strong></h1>";
    }
    


    public function pricing2($atts, $content = null) {
    $class = isset($atts['class']) ? $atts['class'] : '';
            $title = isset($atts['title']) ? $atts['title'] : null;
            $description = isset($atts['description']) ? $atts['description'] : null;
            $result = "<div class=\"pricing2 pricing hover-effect animated  fadeInDown delay1 {$class}\">";
            $result .= ($title === null) ? null : "<h3 class=\"text-center\">{$title}</h3>";
            $result .= ($description === null) ? null : "<p class=\"text-muted text-center\">{$description}</p>";
            $result .= (empty($content)) ? null : Pf::shortcode()->exec($content);
            $result .= "</div>";
            return $result;
    }

    public function pricing_head2($atts, $content = null) {
        $after = (isset($atts['after'])) ? $atts['after'] : '';
        $before = (isset($atts['before'])) ? $atts['before'] : '';
        return "<div class=\"price\"><span class='after'>{$before}</span>{$content}<span class='before'>{$after}</span></div>";
    }

    public function pricing_content2($atts, $content = null) {
        $content = !empty($content) ? Pf::shortcode()->exec($content) : null;
        $class = !empty($atts['class']) ? $atts['class'] : '';
        return "<div class=\"pricing-text text-center text-muted $class\">{$content}</div>";
    }

    public function pricing_button2($atts, $content = null) {
        $class = !empty($atts['class']) ? $atts['class'] : 'btn-default';
        $link = isset($atts['url']) ? $atts['url'] : '#';
        return "<a class=\"btn btn-block btn-xxl {$class}\" href=\"{$link}\">{$content}</a>";
    }



    public function open_div($atts = null, $contents = null) {
        $class = isset($atts['class']) ? $atts['class'] : '';
        $style = isset($atts['style']) ? $atts['style'] : '';
        return '<div class="' . $class . '" style="' . $style . '">';
    }

    public function close_div($atts = null, $contents = null) {
        return '</div>';
    }
    public function input($atts, $content = null) {
        $option = !empty($atts['option']) ? $atts['option'] : '';
        $content = !empty($content) ? Pf::shortcode()->exec($content) : '';
        $this->view->option = $option;
        $this->view->content = $content;
        $this->view->render();
    }

}
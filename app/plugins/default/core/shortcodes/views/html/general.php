<?php
$script = '';
$atts = $this->atts;

if (isset ( $atts ['data-toggle'] )) {
    switch (strtolower ( trim ( $atts ['data-toggle'] ) )) {
        case 'tooltip' :
            $tooltip_class = 'tooltop-' . uniqid () . rand ( 1, 1000 );
            if (isset ( $atts ['class'] )) {
                $atts ['class'] = $atts ['class'] . ' ' . $tooltip_class;
            } else {
                $atts ['class'] = $tooltip_class;
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
                $atts ['class'] = $popover_class;
            }
            $script = "
                            <script>
                            $('.{$popover_class}').popover();
                            </script>
                            ";
            break;
    }
}

echo '<' . $this->tag . ' ' . parse_atts ( $atts ) . '>' . Pf::shortcode ()->exec ( $this->content ) . '</' . $this->tag . '>' . $script;
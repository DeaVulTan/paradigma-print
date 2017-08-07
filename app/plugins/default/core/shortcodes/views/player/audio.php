<?php
$html = '<audio id="' . $this->id . '" controls>';
foreach ($this->source as $type => $item) {
    $item['type'] = get_type_audio($type);
    $html .= '<source ' . parse_atts($item) . '/>';
}
$html .= '</audio>';
echo $html . get_javascript_player($this->id, $this->config_js);
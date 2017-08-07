<?php
$html = '<video id="' . $this->id . '" '.  parse_atts($this->atts_video).' controls>';
foreach ($this->source as $type => $item) {
    $item['type'] = 'video/'.$type;
    $html .= '<source ' . parse_atts($item) . '/>';
}
$html .= '</video>';
echo  $html . get_javascript_player($this->id, $config_js);
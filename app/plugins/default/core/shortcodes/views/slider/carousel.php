<?php
$result = '';
if (! empty ( $this->data['data'] )) {
    $result = "<div id='{$this->id}' class='carousel slide' data-ride='carousel'>
    <div class='carousel-inner {$this->class}'>";
    foreach ( $this->data['data'] as $item ) {
        if (!is_array($item)) continue;
        if (empty ( $active ))
            $this->class = 'active';
        else
            $this->class = '';
        $text = empty ( $item ['text'] ) ? null : '<h4>' . $item ['text'] . '</h4>';
        $link = empty ( $item ['link'] ) ? null : $item ['link'];
        $style = empty ( $item ['style'] ) ? null : $item ['style'];
        $item_data = empty ( $item ['data'] ) ? null : $item ['data'];
        $image = "<img style='margin:auto;{$style}' {$item_data} alt='{$item['alt']}' src='".$item['src']."'>";
        if ($link != null) {
            $image = "<a href='$link'>{$image}</a>";
            $text = "<a href='$link'>{$text}</a>";
        }
        $desc = empty ( $item ['desc'] ) ? null : $item ['desc'];
        $caption = (! empty ( $text ) || ! empty ( $desc )) ? "<div class='carousel-caption'>{$text}<p>{$desc}</p></div>" : '';
        $result .= "<div class='item {$this->class}'>";
        $result .= $image;
        $result .= $caption;
        $result .= '</div>';
        $active = 1;
    }
    $result .= "</div>
    <a class='carousel-arrow carousel-arrow-prev' href='#{$this->id}' data-slide='prev'>
    <i class='fa fa-angle-left'></i>
    </a>
    <a class='carousel-arrow carousel-arrow-next' href='#{$this->id}' data-slide='next'>
    <i class='fa fa-angle-right'></i>
    </a>
    </div>";
}

echo $result;
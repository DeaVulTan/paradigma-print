<?php
$result = '';
$list = '';
if (!empty($this->array)) {
    foreach ($this->array as $item) {
        $image = "<img src='{$item['src']}' alt='{$item['alt']}' title='{$item['text']}'>";
        $link = empty($item['link']) ? null : $item['link'];
        if ($link != null) {
            $image = "<a href='$link'>{$image}</a>";
        }
        $list .= "<li>{$image}</li>";
    }
    $result .= " <div class='jcarousel-wrapper {$this->class}'>
    <div class='jcarousel'>
    <ul>
    $list
    </ul>
    </div>
    <a href='#' class='jcarousel-control-prev'>&lsaquo;</a>
    <a href='#' class='jcarousel-control-next'>&rsaquo;</a>
    </div>";
}

echo $result;
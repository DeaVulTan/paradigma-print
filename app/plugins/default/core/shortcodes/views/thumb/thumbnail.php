<?php
$result = "<div class='thumbnails $this->class'>
<div class='thumbnail-img'>
<div class='overflow-hidden'>
<img class='img-responsive' src='{$this->img}' alt=''>
</div>";
$result .= "</div>
<div class='caption'>
<h3> $this->subject </h3>
$this->content";
$result .= "</div>
</div>";
echo $result;
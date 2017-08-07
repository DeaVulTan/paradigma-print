<?php
$result = "<div class='borderbox border-{$this->class}-color' style='border-{$this->class}-width: {$this->width}; border-{$this->class}-style: {$this->type} ; {$this->style}'>
<p>{$this->content}</p>
</div>";
echo $result;
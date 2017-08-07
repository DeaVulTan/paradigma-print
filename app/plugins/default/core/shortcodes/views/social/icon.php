<?php
$result = "<a href='$this->url' data-original-title='$this->class' class='social-icon $this->css'><img class='margin-bottom-15' src='" . RELATIVE_PATH . '/app/themes/' . get_option('active_theme') . "/img/social_icon/$this->class.png' /></a>";
echo $result;
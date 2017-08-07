<?php
echo  '<div ' . parse_atts($this->atts) . '>' . Pf::shortcode()->exec($this->content) . '</div>';
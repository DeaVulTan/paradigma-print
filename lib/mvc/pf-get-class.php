<?php
class Pf_Get extends Pf_Base_Object{
    public function __construct(){
        parent::__construct($_GET);
    }
}
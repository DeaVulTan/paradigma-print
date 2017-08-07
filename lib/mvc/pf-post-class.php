<?php
class Pf_Post extends Pf_Base_Object{
    public function __construct(){
        parent::__construct($_POST);
    }
}
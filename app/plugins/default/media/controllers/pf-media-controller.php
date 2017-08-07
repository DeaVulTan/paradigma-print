<?php

defined('PF_VERSION') OR header('Location:404.html');

class Pf_Media_Controller extends Pf_Plugin_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->view = new Pf_Plugin_View;
        $this->view->set_path('media', true);
        $this->acl = array(1, 2, 3, 4);
        $this->check_acl();
    }

    public function main()
    {
        $this->view->render('main');
    }

    public function select()
    {
        $this->view->render('select');
    }

}

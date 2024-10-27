<?php

namespace Controller;

DEFINED ('ROOTPATH') OR exit ('Access Denied');

class Logout
{
    use MainController;
    
    public function index()
    {
        $sesh = new \Core\Session;
        $sesh->logout();
        redirect('home');
    } 
}
 
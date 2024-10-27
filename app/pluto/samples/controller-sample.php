<?php

namespace Controller;

DEFINED ('ROOTPATH') OR exit ('Access Denied');

/**
 * {CLASSNAME} class
 */

class {CLASSNAME}
{
    use MainController;
    
    public function index()
    {
        //redirect to log in page if user is not logged in
        // $sesh = new \Core\Session;
        // $data['user'] = $sesh->user();
        
        // if (!$sesh->is_logged_in())
        // redirect('signin');

        $data['sesh'] = $sesh;
        $data['model'] = ${classname};
        $data['title'] = '{classname}';
        $this -> view('{classname}', $data);
    } 
}
 
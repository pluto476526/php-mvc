<?php

namespace Controller;

DEFINED ('ROOTPATH') OR exit ('Access Denied');

class Home
{
    use MainController;
    
    public function index()
    {
        //redirect to log in page if user is not logged in
        
        // $sesh = new \Core\Session;
        
        // if (!$sesh->is_logged_in())
        // redirect('signin');

        $data['page_title'] = 'Home';
        $this -> view('home', $data);
    }
}
 
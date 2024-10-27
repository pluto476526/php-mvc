<?php

namespace Controller;

DEFINED ('ROOTPATH') OR exit ('Access Denied');

class Signup
{
    use MainController;
    
    public function index()
    {
        $data['user'] = new \Model\User;
        $request = new \Core\Request;
        
        if ($request->posted())
        {
            $data['user']->signup($_POST);
        }

        $data['page_title'] = 'Sign Up';
        $this -> view('signup', $data);
    } 
}
 
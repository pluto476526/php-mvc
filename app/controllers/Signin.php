<?php

namespace Controller;

DEFINED ('ROOTPATH') OR exit ('Access Denied');

class Signin
{
    use MainController;
    
    public function index()
    {
        $data['user'] = new \Model\User;
        $request = new \Core\Request;
        
        if ($request->posted())
        {
            $data['user']->signin($_POST);
        }

        $data['page_title'] = 'Sign In';
        $this -> view('signin', $data);
    } 
}
 
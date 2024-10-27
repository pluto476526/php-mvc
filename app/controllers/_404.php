<?php

namespace Controller;

DEFINED ('ROOTPATH') OR exit ('Access Denied');

class _404
{
    use MainController;
    /**
     * This function is the main entry point for the 404 page not found Controller.
     * 
     * It displays a custom 404 error message.
     * 
     */
    public function index()
    {
        echo "404 page not found Controller";
    }
}

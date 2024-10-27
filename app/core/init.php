<?php

DEFINED ('ROOTPATH') OR exit ('Access Denied');

/**
 * Autoload function to load model classes on-demand.
 *
 * This function is registered with spl_autoload_register() in the bootstrap file.
 * It splits the fully qualified class name into its namespace and class name parts.
 * Then, it constructs the file path for the model class and includes it.
 *
 * @param string $classname The fully qualified class name to be loaded.
 * @return void
 */
spl_autoload_register(function($classname)
{
    $classname = explode("\\", $classname);
    $classname = end($classname);
    require $filename = "../app/models/".ucfirst($classname).".php";
});

require 'config.php';
require 'functions.php';
require 'Database.php';
require 'Model.php';
require 'Controller.php';
require 'App.php';
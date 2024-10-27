<?php

DEFINED ('ROOTPATH') OR exit ('Access Denied');

class App
{
    private $controller = 'Home';
    private $method     = 'Index';

/**
 * This function splits the URL into segments based on the '/' delimiter.
 * It also trims any leading or trailing slashes from the URL.
 *
 * @return array An array of URL segments.
 */
private function splitURL()
{
    // Fetch the URL from the GET request, default to 'home' if not provided.
    $URL = $_GET['url'] ?? 'home';

    // Trim leading and trailing slashes, then split the URL into segments.
    $URL = explode("/", trim($URL,"/"));

    // Return the array of URL segments.
    return $URL;
}

/**
 * This function loads the appropriate controller based on the URL.
 * It handles nested directories and 404 errors.
 *
 * @return void
 */
public function loadController()
{
    // Fetch the URL segments.
    $URL = $this->splitURL();

    // Construct the filename for the main controller.
    $filename = "../app/controllers/" . ucfirst($URL[0]) . ".php";

    // Check if the main controller file exists.
    if (file_exists($filename))
    {
        // If it exists, require the file and set the controller name.
        require $filename;
        $this->controller = ucfirst($URL[0]);
        unset($URL[0]);
    } 
    else
    {
        // If the main controller file does not exist, construct the filename for a nested controller.
        $filename = "../app/controllers/" . ucfirst($URL[0]) . "/" . ucfirst($URL[0]) . ".php";

        // Check if the nested controller file exists.
        if (file_exists($filename))
        {
            // If it exists, require the file and set the controller name.
            require $filename;
            $this->controller = ucfirst($URL[0]);
            unset($URL[0]);
        } 
        else
        {
            // If neither the main nor the nested controller file exists, load the 404 controller.
            $filename = "../app/controllers/_404.php";
            require $filename;
            $this->controller = "_404";
        }
    }

    // Instantiate the controller class.
    $controller = new ('\Controller\\'.$this->controller);

    // Check if a method is specified in the URL.
    if (!empty($URL[1]))
    {
        // If a method is specified, check if it exists in the controller class.
        if (method_exists($controller, $URL[1]))
        {
            // If the method exists, set the method name and remove it from the URL segments.
            $this->method = $URL[1];
            unset($URL[1]);
        }
    }

    // Call the controller method with any remaining URL segments as arguments.
    call_user_func_array([$controller, $this->method], $URL);
}
}

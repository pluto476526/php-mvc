<?php

DEFINED ('ROOTPATH') OR exit ('Access Denied');

/**
 * This function checks if the required PHP extensions are loaded.
 * If any extension is not loaded, it will display an error message and terminate the script.
 *
 * @return void
 */
function check_extensions()
{
    // List of required PHP extensions
    $required_extensions = [
        'gd',
        'mysqli',
        'curl',
        'fileinfo',
        'intl',
        'exif',
        'mbstring',
    ];

    // Array to store the extensions that are not loaded
    $not_loaded = [];

    // Loop through the required extensions
    foreach ($required_extensions as $extension)
    {
        // Check if the extension is loaded
        if (!extension_loaded($extension))
        {
            // If not loaded, add it to the not_loaded array
            $not_loaded[] = $extension;
        }
    }

    // If there are any extensions not loaded
    if (!empty($not_loaded))
    {
        // Display an error message with the list of not loaded extensions
        show("Please load the following extensions in your php.ini file: <br>".implode("<br>", $not_loaded));

        // Terminate the script
        die;
    }
}

/**
 * This function is used for debugging purposes. It prints the given variable 
 * in a formatted manner using print_r() function and wraps it with <pre> tags.
 *
 * @param mixed $stuff The variable to be printed. It can be of any data type.
 *
 * @return void This function does not return any value. It only prints the variable.
 */
function show($stuff)
{
    echo "<pre>";
    print_r($stuff);
    echo "</pre>";
}

/**
 * This function escapes special characters in a string for use in HTML.
 * It helps prevent Cross-Site Scripting (XSS) attacks.
 *
 * @param string $string The string to be escaped.
 *
 * @return string The escaped string.
 */
function escape($string)
{
    // htmlspecialchars function converts special characters to their HTML entities
    // ENT_QUOTES flag ensures both single and double quotes are escaped
    // If the function fails, it throws an exception
    return htmlspecialchars($string, ENT_QUOTES);
}

/**
 * This function redirects the user to a new page.
 *
 * @param string $path The path to the new page, relative to the root directory.
 *
 * @return void This function does not return any value. It only redirects the user.
 *
 * @throws Exception If the header function fails to redirect the user.
 *
 * @example redirect('dashboard'); // Redirects to the dashboard page
 */
function redirect($path)
{
    // The 'header' function sends a raw HTTP header to the client.
    // In this case, it sets the location header to redirect the user to the new page.
    header("location: " . ROOT."/".$path);

    // The 'die' function terminates the script immediately.
    // It is used here to ensure that no further code is executed after the redirect.
    die;
}

/**
 * This function generates a random string.
 *
 * @param int $length. The length of the generated string.
 *
 * @return string The generated string.
 * 
 *  @example echo random(16); // Generates a random string of length 16 
 */
function random($length = 10)
{ 
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    $charactersLength = strlen($characters); 
    $randomString = '';
    
    for ($i = 0; $i < $length; $i++) 
    { 
        $randomString .= $characters[rand(0, $charactersLength - 1)]; 
    } 
    
    return $randomString;
}

/**
 * This function retrieves the image path based on the given file and type.
 * If the file exists, it returns the full path of the file.
 * If the file does not exist, it returns the path of a default image based on the type.
 *
 * @param mixed $file The file path or name. If not provided, an empty string is used.
 * @param string $type The type of image. It can be either 'post' or 'user'. Default is 'post'.
 *
 * @return string The full path of the image file or the default image path.
 *
 * @throws Exception If the file does not exist and the type is not 'post' or 'user'.
 *
 * @example get_image('profile_picture.jpg', 'user'); // Returns '/path/to/root/profile_picture.jpg'
 * @example get_image('post_image.jpg'); // Returns '/path/to/root/post_image.jpg'
 * @example get_image(); // Returns '/path/to/root/assets/images/no-image.jpg'
 * @example get_image('non_existent_file.jpg'); // Throws an exception
 */
function get_image(mixed $file = '', string $type = 'post' ):string
{
    $file = $file ?? '';
    if (file_exists($file))
    {
        return ROOT . "/" . $file;
    }

    if ($type == 'user')
    {
        return ROOT . "/assets/images/user.jpg";
    }
    else
    {
        return ROOT ."/assets/images/no-image.jpg";
    }
}

/**
 * This function retrieves pagination variables from the URL.
 *
 * @return array An associative array containing the pagination variables: 'page', 'prev_page', and 'next_page'.
 *
 * @throws Exception If the 'page' parameter is not a valid integer.
 *
 * @example get_pagination_vars(); // Returns ['page' => 1, 'prev_page' => 1, 'next_page' => 2]
 * @example get_pagination_vars()['page']; // Returns 1
 * @example get_pagination_vars()['prev_page']; // Returns 1
 * @example get_pagination_vars()['next_page']; // Returns 2
 */
function get_pagination_vars():array
{
    $vars = [];
    $vars['page'] = $_GET['page'] ?? 1;
    $vars['page'] = (int)$vars['page'];

    // If the page is less than or equal to 1, set the previous page to 1
    $vars['prev_page'] = $vars['page'] <= 1 ? 1 : $vars['page'] - 1;

    // Set the next page to the current page plus 1
    $vars['next_page'] = $vars['page'] + 1;

    return $vars;
}

/**
 * This function is used to handle and retrieve messages stored in the session.
 *
 * @param string $msg The message to be set in the session. If not provided, it will retrieve the message from the session.
 * @param bool $clear If set to true, it will clear the message from the session after retrieving it. Default is false.
 *
 * @return mixed If a message is provided, it returns void. If no message is provided, it returns the message from the session or false if no message is found.
 *
 * @example message('Welcome back!'); // Sets the message in the session.
 * @example message(); // Retrieves the message from the session.
 * @example message(null, true); // Retrieves and clears the message from the session.
 */
function message(string $msg = null, bool $clear = false)
{
    $sesh = new Core\Session();

    if (!empty($msg))
    {
        $sesh->set('message', $msg);
    }
    else if (!empty($sesh->get('message')))
    {
        $msg = $sesh->get('message');

        if ($clear)
        {
            $sesh->pop('message', $msg);
        }

        return $msg;
    }

    return false;
}

/**
 * This function retrieves a specific URL segment based on the provided key.
 *
 * @param string $key The key to identify the URL segment. It can be one of the following:
 * - 'page' or 0: Returns the first segment of the URL.
 * - 'section' or 'slug' or 1: Returns the second segment of the URL.
 * - 'action' or 2: Returns the third segment of the URL.
 * - 'id' or 3: Returns the fourth segment of the URL.
 * - Any other value: Returns null.
 *
 * @return mixed The URL segment identified by the provided key or null if the key is not found.
 *
 * @throws Exception If the URL is not found in the $_GET superglobal array.
 *
 * @example URL('page'); // Returns the first segment of the URL.
 * @example URL('section'); // Returns the second segment of the URL.
 * @example URL('action'); // Returns the third segment of the URL.
 * @example URL('id'); // Returns the fourth segment of the URL.
 * @example URL('unknown'); // Returns null.
 */
function URL($key):mixed
{
    $URL = $_GET['url'] ?? 'home';
    $URL = explode("/", trim($URL,"/"));

    switch ($key)
    {
        case 'page':
        case 0:
            return $URL[0] ?? null;
            break;
        case 'section':
        case 'slug':
        case 1:
            return $URL[1] ?? null;
            break;
        case 'action':
        case 2:
            return $URL[2] ?? null;
            break;
        case 'id':
        case 3:
            return $URL[3] ?? null;
            break;
        default:
            return null;
            break;
    }
}
/**
 * This function retrieves the value of a specific key from the POST or GET superglobal arrays.
 * If the key exists in the specified mode (POST or GET), it returns the value.
 * If the key does not exist or the mode is not specified, it returns the default value.
 *
 * @param string $key The key to retrieve the value from the POST or GET superglobal arrays.
 * @param mixed $default The default value to return if the key does not exist in the specified mode. Default is an empty string.
 * @param string $mode The mode to retrieve the value from. It can be either 'post' or 'get'. Default is 'post'.
 *
 * @return mixed The value of the specified key from the POST or GET superglobal arrays, or the default value if the key does not exist or the mode is not specified.
 *
 * @example old_value('email'); // Returns the value of 'email' from the POST superglobal array.
 * @example old_value('name', 'Guest'); // Returns the value of 'name' from the POST superglobal array, or 'Guest' if the key does not exist.
 * @example old_value('id', 0, 'get'); // Returns the value of 'id' from the GET superglobal array, or 0 if the key does not exist.
 */
function old_value(string $key, mixed $default = "", string $mode = 'post'):mixed
{
    $_POST = ($mode == 'post') ? $_POST : $_GET;

    if (isset($_POST[$key]))
    {
        return $_POST[$key];
    }

    return $default;
}

/**
 * This function is used to generate the 'selected' attribute for HTML select elements.
 * It checks if the provided value matches the value from the POST or GET superglobal arrays.
 * If a match is found, it returns the ' selected ' string. Otherwise, it returns an empty string.
 *
 * @param string $key The key to retrieve the value from the POST or GET superglobal arrays.
 * @param mixed $value The value to compare with the value from the POST or GET superglobal arrays.
 * @param mixed $default The default value to return if the key does not exist in the specified mode. Default is an empty string.
 * @param string $mode The mode to retrieve the value from. It can be either 'post' or 'get'. Default is 'post'.
 *
 * @return string The ' selected ' string if the provided value matches the value from the POST or GET superglobal arrays, or an empty string otherwise.
 *
 * @example old_select('country', 'USA'); // Returns ' selected ' if the 'country' key in the POST superglobal array has the value 'USA'.
 * @example old_select('country', 'USA', 'Canada'); // Returns ' selected ' if the 'country' key in the POST superglobal array has the value 'USA'.
 * @example old_select('country', 'USA', 'Canada', 'get'); // Returns ' selected ' if the 'country' key in the GET superglobal array has the value 'USA'.
 */
function old_select(string $key, mixed $value, mixed $default = "", string $mode = 'post'):mixed
{
    $_POST = ($mode == 'post') ? $_POST : $_GET;

    if (isset($_POST[$key]))
    {
        if ($_POST[$key] == $value)
        {
            return " selected ";
        }
    }
    else if ($default == $value)
    {
        return " selected ";
    }

    return "";
}

/**
 * This function is used to generate the 'checked' attribute for HTML input elements.
 * It checks if the provided value matches the value from the POST or GET superglobal arrays.
 * If a match is found, it returns the ' checked ' string. Otherwise, it returns an empty string.
 *
 * @param string $key The key to retrieve the value from the POST or GET superglobal arrays.
 * @param string $value The value to compare with the value from the POST or GET superglobal arrays.
 * @param string $default The default value to return if the key does not exist in the specified mode. Default is an empty string.
 *
 * @return string The ' checked ' string if the provided value matches the value from the POST or GET superglobal arrays, or an empty string otherwise.
 *
 * @example old_checked('remember_me', 'yes'); // Returns ' checked ' if the 'remember_me' key in the POST superglobal array has the value 'yes'.
 * @example old_checked('remember_me', 'yes', 'no'); // Returns ' checked ' if the 'remember_me' key in the POST superglobal array has the value 'yes'.
 * @example old_checked('remember_me', 'yes', 'no', 'get'); // Returns ' checked ' if the 'remember_me' key in the GET superglobal array has the value 'yes'.
 */
function old_checked(string $key, string $value, string $default = ""):string
{
    if (isset($_POST[$key]))
    {
        if ($_POST[$key]  == $value)
        {
            return ' checked ';
        }
    }
    else
    {
        if ($_SERVER['REQUEST_METHOD'] == 'GET' && $default == $value)
        {
            return ' checked ';
        }
    }

    return '';
}

/**
 * This function converts a given date string into a more readable format.
 *
 * @param string $date The date string to be converted. It should be in a format that can be understood by strtotime().
 *
 * @return string The converted date string in the format "jS M, Y".
 *
 * @example get_date('2022-01-01'); // Returns '1st Jan, 2022'
 * @example get_date('2022/01/01'); // Returns '1st Jan, 2022'
 * @example get_date('January 1, 2022'); // Returns '1st Jan, 2022'
 */
function get_date($date)
{
    return date("jS M, Y", strtotime($date));
}

/**
 * This function adds the root path to all image tags in the provided HTML content.
 *
 * @param string $contents The HTML content to be processed.
 *
 * @return string The modified HTML content with the root path added to all image tags.
 */
function add_root_to_images($contents)
{
    // Regular expression to match all image tags in the content
    preg_match_all('/<img[^>]+>/', $contents, $matches);

    // Check if matches were found
    if (is_array($matches) && count($matches) > 0)
    {
        // Loop through each image tag found
        foreach ($matches[0] as $match)
        {
            // Regular expression to match the 'src' attribute of the image tag
            preg_match('/src="[^"]+/', $match, $matches2);

            // Check if the 'src' attribute does not contain 'http' (indicating an external image)
            if (!strstr($matches2[0], 'http'))
            {
                // Replace the 'src' attribute with the root path added
                $contents = str_replace($matches2[0], 'src="'.ROOT.'/'.str_replace('src="', "", $matches2[0]), $contents);
            }
        }
    }

    // Return the modified HTML content
    return $contents;
}

/**
 * This function removes all image tags from the provided HTML content and saves the images to a specified folder.
 * It also resizes the images to a maximum width of 1000 pixels.
 *
 * @param string $content The HTML content to be processed.
 * @param string $folder The folder where the images will be saved. Default is "uploads/".
 *
 * @return string The modified HTML content without the image tags.
 */
function remove_images_from_content($content, $folder = "uploads/")
{
    // Check if the folder exists, if not create it
    if (!file_exists($folder))
    {
        mkdir($folder, 0777, true);
        file_put_contents($folder."index.php", "Access Denied");
    }

    // Regular expression to match all image tags in the content
    preg_match_all('/<img[^>]+>/', $content, $matches);
    $new_content = $content;

    // Check if matches were found
    if (is_array($matches) && count($matches) > 0)
    {
        // Instantiate the Image class
        $image_class = new \Model\Image();

        // Loop through each image tag found
        foreach ($matches[0] as $match)
        {
            // Check if the image tag has an 'http' source (indicating an external image)
            if (strstr($match, "http"))
            {
                continue;
            }

            // Regular expression to match the 'src' attribute of the image tag
            preg_match('/src="[^"]+/', $match, $matches2);
            // Regular expression to match the 'data-filename' attribute of the image tag
            preg_match('/data-filename="[^\"]+/', $match, $matches3);

            // Check if the image tag has a 'data' source (indicating an image encoded in the 'src' attribute)
            if (strstr($matches2[0], 'data'))
            {
                // Split the 'src' attribute into parts
                $parts = explode(",", $matches2[0]);
                // Extract the 'data-filename' attribute
                $basename = $matches3[0] ?? 'basename.jpg';
                $basename = str_replace('data-filename="', "", $basename);
 
                // Generate a unique filename for the image
                $filename = $folder . "img " . sha1(rand(0, 9999999999)) . $basename;

                // Replace the image tag with a new image tag pointing to the new filename
                $new_content = str_replace($parts[0] . ",". $parts[1], 'src="'.$filename, $new_content);
                // Save the image to the new filename
                file_put_contents($filename, base64_decode($parts[1]));

                // Resize the image to a maximum width of 1000 pixels
                $image_class->resize($filename, 1000);
            }
        }
    }

    // Return the modified HTML content without the image tags
    return $new_content;
}

/**
 * This function deletes all image files referenced in the HTML content of the old content,
 * but only if they are not referenced in the new content.
 *
 * @param string $content The original HTML content.
 * @param string $content_new The new HTML content. If not provided, all images referenced in the old content will be deleted.
 *
 * @return void
 */
function delete_images_from_content(string $content, string $content_new = ''):void
{
    if (empty($content_new))
    {
        // Extract all image tags from the old content
        preg_match_all('/<img[^>]+/', $content, $matches);

        // Loop through each image tag found
        foreach ($matches[0] as $match)
        {
            // Extract the 'src' attribute from the image tag
            preg_match('/src="[^"]+/', $match, $matches2);
            $matches2[0] = str_replace('src="', "", $matches2[0]);

            // Check if the image file exists
            if (file_exists($matches2[0]))
            {
                // Delete the image file
                unlink($matches2[0]);
            }
        }
    }
    else
    {
        // Extract all image tags from the old and new contents
        preg_match_all('/img[^>]+>/', $content, $matches);
        preg_match_all('/img[^>]+>/', $content_new, $matches_new);

        $old_images = [];
        $new_images = [];

        // Loop through each image tag found in the old content
        foreach ($matches[0] as $match)
        {
            // Extract the 'src' attribute from the image tag
            preg_match('/src="[^"]+/', $match, $matches2);
            $matches2[0] = str_replace('src="', "", $matches2[0]);

            // Check if the image file exists
            if (file_exists($matches2[0]))
            {
                // Add the image file to the list of old images
                $old_images[] = $matches2[0];
            }
        }

        // Loop through each image tag found in the new content
        foreach ($matches_new[0] as $match)
        {
            // Extract the 'src' attribute from the image tag
            preg_match('/src="[^"]+/', $match, $matches2);
            $matches2[0] = str_replace('src="', "", $matches2[0]);

            // Check if the image file exists
            if (file_exists($matches2[0]))
            {
                // Add the image file to the list of new images
                $new_images[] = $matches2[0];
            }
        }

        // Loop through each old image file
        foreach ($old_images as $image)
        {
            // Check if the old image file is not referenced in the new content
            if (!in_array($image, $new_images))
            {
                // Check if the image file exists
                if (file_exists($image))
                {
                    // Delete the image file
                    unlink($image);
                }
            }
        }
    }
}
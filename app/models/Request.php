<?php

namespace Core;

DEFINED ('ROOTPATH') or exit ('Access Denied');
DEFINE ('UPLOAD_DIRECTORY', 'assets/uploads/');
DEFINE ('MAXSIZE', 5242880); // 5MB in bytes.

class Request
{
    /**
     * Returns the request method.
     *
     * @return string The request method (e.g., GET, POST, PUT, DELETE).
     */
    public function method(): string
    {
        return $_SERVER['REQUEST_METHOD'];
    }

    /**
     * Checks if the request method is POST and if there are any POST data.
     *
     * @return bool Returns true if the request method is POST and there are POST data, otherwise false.
     */
    public function posted(): bool
    {
        if ($_SERVER['REQUEST_METHOD'] == "POST" && count($_POST) > 0) {
            return true;
        }

        return false;
    }

    /**
     * Retrieves a specific POST variable or all POST variables.
     *
     * @param string $key The name of the POST variable to retrieve. If not provided, all POST variables will be returned.
     * @param mixed $default The default value to return if the specified POST variable is not found.
     *
     * @return mixed The value of the specified POST variable or all POST variables if no key is provided.
     *               Returns the default value if the specified POST variable is not found.
     */
    public function post(string $key = '', mixed $default = ''): mixed
    {
        if (empty($key)) {
            return $_POST;
        } else
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }

        return $default;
    }

    /**
     * Retrieves a specific file uploaded via POST or all uploaded files.
     *
     * @param string $key The name of the file to retrieve. If not provided, all uploaded files will be returned.
     * @param mixed $default The default value to return if the specified file is not found.
     *
     * @return mixed The value of the specified file or all uploaded files if no key is provided.
     *               Returns the default value if the specified file is not found.
     *               If the file is not uploaded or the key is not provided, returns an empty array.
     */
    public function files(string $key = '', mixed $default = ''): mixed
    {
        if (empty($key)) {
            return $_FILES;
        } else
        if (isset($_FILES[$key])) {
            return $_FILES[$key];
        }

        return $default;
    }

    /**
     * Retrieves a specific GET variable or all GET variables.
     *
     * @param string $key The name of the GET variable to retrieve. If not provided, all GET variables will be returned.
     * @param mixed $default The default value to return if the specified GET variable is not found.
     *
     * @return mixed The value of the specified GET variable or all GET variables if no key is provided.
     *               Returns the default value if the specified GET variable is not found.
     */
    public function get(string $key = '', mixed $default = ''): mixed
    {
        if (empty($key)) {
            return $_GET;
        } else
        if (isset($_GET[$key])) {
            return $_GET[$key];
        }

        return $default;
    }

    /**
     * Retrieves a specific variable from the combined GET and POST data or all variables.
     *
     * @param string $key The name of the variable to retrieve. If not provided, all variables will be returned.
     * @param mixed $default The default value to return if the specified variable is not found.
     *
     * @return mixed The value of the specified variable or all variables if no key is provided.
     *               Returns the default value if the specified variable is not found.
     *               If the key is not provided, returns an empty array.
     */
    public function input(string $key = '', mixed $default = ''): mixed
    {
        if (isset($_REQUEST[$key])) {
            return $_REQUEST[$key];
        }

        return $default;
    }

    /**
     * Checks if given file's extension and MIME are defined as allowed.
     *
     * @param $uploadedTempFile The file that is has been uploaded already, from where the MIME will be read.
     * 
     * @param $destFilePath The path that the dest file will have, from where the extension will be read.
     * 
     * @return True if file's extension and MIME are allowed; false if at least one of them is not.
     */
    protected function validFileType($uploadedTempFile, $destFilePath)
    {
        $ALLOWED_EXTENSIONS = array('jpg', 'jpeg', 'png');
        $ALLOWED_MIMES = array('image/jpg', 'image/jpeg', 'image/png');
        $fileExtension = pathinfo($destFilePath, PATHINFO_EXTENSION);
        $fileMime = mime_content_type($uploadedTempFile);
        $validFileExtension = in_array($fileExtension, $ALLOWED_EXTENSIONS);
        $validFileMime = in_array($fileMime, $ALLOWED_MIMES);
        $validFileType = $validFileExtension && $validFileMime;
        return $validFileType;
    }

    /**
     * 
     * Handles the file upload.
     *
     * Checks if the file is actually an uploaded file, smaller than specified and is a valid file (extension and MIME).
     * 
     * @param $data The file name.
     *
     */
    public function handleUpload($data)
    {
        $uploadedTempFile = $data['tmp_name'];
        $filename = basename($data['name']);
        $destFile = UPLOAD_DIRECTORY . random(5) . $filename;
        $isUploadedFile = is_uploaded_file($uploadedTempFile);
        $validSize = $data['size'] <= MAXSIZE && $data['size'] >= 0;

        if ($isUploadedFile && $validSize && $this->validFileType($uploadedTempFile, $destFile))
        {
            move_uploaded_file($uploadedTempFile, $destFile);

            return $destFile;
        }

        return $isUploadedFile;
    }

    /**
     * Retrieves all variables from the combined GET and POST data.
     *
     * @return mixed An associative array containing all variables from the combined GET and POST data.
     *               If no variables are present, returns an empty array.
     */
    public function all(): mixed
    {
        return $_REQUEST;
    }
}

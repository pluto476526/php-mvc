<?php

namespace Core;

DEFINED ('ROOTPATH') OR exit ('Access Denied');

class Session
{
    public $mainKey = 'APP';
    public $userKey = 'USER';

    /**
     * Starts the PHP session if it's not already started.
     *
     * @return int Returns 1 if the session was started or already active.
     *
     */
        private function start_session():int
        {
            if (session_status() === PHP_SESSION_NONE)
            {
                session_start();
            }

            return 1;
        }
    
    /**
     * Sets a session value.
     *
     * @param mixed $keyOrArray The key or associative array of keys and values to be set.
     * @param mixed $value The value to be set if $keyOrArray is a string.
     * @return int Returns 1 if the session value was set successfully.
     *
     * @throws Exception If the session could not be started.
     */
    public function set(mixed $keyOrArray, mixed $value = ''):int
    {
        $this->start_session();

        if (is_array($keyOrArray))
        {
            foreach ($keyOrArray as $key => $value)
            {
                $_SESSION[$this->mainKey][$key] = $value;
            }

            return 1;
        }

        $_SESSION[$this->mainKey][$keyOrArray] = $value;
        return 1;
    }

    /**
     * Retrieves a session value.
     *
     * @param string $key The key of the session value to retrieve.
     * @param mixed $default The default value to return if the session value is not set.
     * @return mixed The session value or the default value if the session value is not set.
     *
     * @throws Exception If the session could not be started.
     */
    public function get(string $key, mixed $default = ''):mixed{
        $this->start_session();

        if (isset($_SESSION[$this->mainKey][$key]))
        {
            return $_SESSION[$this->mainKey][$key];
        }

        return $default;
    }

    /**
     * Authenticates the user by setting the user data in the session.
     *
     * @param mixed $user_row The user data to be stored in the session.
     * @return int Returns 0 if the user is authenticated successfully.
     *
     * @since 1.0.0
     */
    public function auth(mixed $user_row):int
    {
        $this->start_session();
        $_SESSION[$this->userKey] = $user_row;
        return 0;
    }

    /**
     * Logs out the user by unsetting the user data from the session.
     *
     * @return int Returns 0 if the user is logged out successfully.
     *
     * @throws Exception If the session could not be started.
     *
     * @since 1.0.0
     */
    public function logout():int
    {
        $this->start_session();

        // Check if the user data is set in the session
        if (!empty($_SESSION[$this->userKey]))
        {
            // Unset the user data from the session
            unset($_SESSION[$this->userKey]);
        }

        // Return 0 to indicate successful logout
        return 0;
    }

    /**
     * Checks if the user is logged in by verifying the existence of user data in the session.
     *
     * @return bool Returns true if the user is logged in, false otherwise.
     *
     * @throws Exception If the session could not be started.
     *
     * @since 1.0.0
     */
    public function is_logged_in():bool
    {
        $this->start_session();

        if (!empty($_SESSION[$this->userKey]))
        {
            return true;
        }

        return false;
    }

    /**
     * Retrieves the user data from the session.
     *
     * @param string $key The key of the user data to retrieve. If not provided, the entire user data will be returned.
     * @param mixed $default The default value to return if the user data is not set.
     * @return mixed The user data or the default value if the user data is not set.
     *
     * @throws Exception If the session could not be started.
     *
     * @since 1.0.0
     */
    public function user(string $key = '', mixed $default = '' ):bool
    {
        $this->start_session();

        // If no key is provided and user data is set in the session, return the entire user data
        if (empty($key) && !empty($_SESSION[$this->userKey]))
        {
            return $_SESSION[$this->userKey];
        }
        // If a key is provided and user data is set in the session, return the value associated with the key
        else if (!empty($_SESSION[$this->userKey]))
        {
            return $_SESSION[$this->userKey];
        }

        // If the user data is not set in the session, return the default value
        return $default;
    }

    /**
     * Removes and returns a session value.
     *
     * @param string $key The key of the session value to remove and retrieve.
     * @param mixed $default The default value to return if the session value is not set.
     * @return mixed The session value or the default value if the session value is not set.
     *
     * @throws Exception If the session could not be started.
     *
     * @since 1.0.0
     */
    public function pop(string $key, mixed $default):mixed
    {
        $this->start_session();

        if (!empty($_SESSION[$this->mainKey][$key]))
        {
            $value = $_SESSION[$this->mainKey][$key];
            unset($_SESSION[$this->mainKey][$key]);
            return $value;
        }

        return $default;
    }

    /**
     * Retrieves all session values associated with the main key.
     *
     * @return mixed An associative array of session values or an empty array if no session values are set.
     *
     * @throws Exception If the session could not be started.
     *
     * @since 1.0.0
     */
    public function all():mixed
    {
        $this->start_session();

        // Check if the main key is set in the session
        if (isset($_SESSION[$this->mainKey]))
        {
            // Return all session values associated with the main key
            return $_SESSION[$this->mainKey];
        }

        // Return an empty array if no session values are set
        return [];
    }
}
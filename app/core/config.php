<?php

DEFINED ('ROOTPATH') OR exit ('Access Denied');

if ((empty($_SERVER['SERVER_NAME']) && php_sapi_name() == 'cli') || (!empty($_SERVER['SERVER_NAME']) && $_SERVER['SERVER_NAME'] == 'localhost'))
{
    DEFINE ('DBNAME', 'rms');
    DEFINE ('DBHOST', 'localhost');
    DEFINE ('DBUSER', 'root');
    DEFINE ('DBPASS', '');
    DEFINE ('DBDRIVER', '');

    DEFINE ('ROOT', 'http://localhost/mvc/public');

}
else
{

    DEFINE ('DBNAME', 'rms');
    DEFINE ('DBHOST', 'localhost');
    DEFINE ('DBUSER', 'root');
    DEFINE ('DBPASS', '');
    DEFINE ('DBDRIVER', '');

    DEFINE ('ROOT', 'https://www.myweb.com');
}

DEFINE('APP_NAME', 'Andromeda Tech');

DEFINE('DEBUG', true);
<?php
// Error Reporting Turn On
ini_set('error_reporting', E_ALL);

// Setting up the time zone
date_default_timezone_set('Asia/Kolkata');

// Host Name
$dbhost = 'localhost';

// Database Name
$dbname = 'hpj_database';

// Database Username
$dbuser = 'root';

// Database Password
$dbpass = '';



function get_Base_url()
{
    $server_name = $_SERVER['SERVER_NAME'];

    if (!in_array($_SERVER['SERVER_PORT'], [80, 443])) {
     $port = ":$_SERVER[SERVER_PORT]";
    } else {
     $port = '';
    }

    if (!empty($_SERVER['HTTPS']) && (strtolower($_SERVER['HTTPS']) == 'on' || $_SERVER['HTTPS'] == '1')) {
     $scheme = 'https';
    } else {
     $scheme = 'http';
    }
    return $scheme.'://'.$server_name.$port;
}
$http = get_Base_url()."/";
$http2 = get_Base_url();
$WebBaseUrl = 'hpj/';

// Defining base url
define("BASE_URL", $http.$WebBaseUrl);
$uri_parts = explode('?', $_SERVER['REQUEST_URI'], 2);

define("CURR_LINK", $http2.$uri_parts[0]);


// Getting Admin url
define("ADMIN_URL", BASE_URL . "admin" . "/");

try {
	$pdo = new PDO("mysql:host={$dbhost};dbname={$dbname}", $dbuser, $dbpass);
	$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
}
catch( PDOException $ex ) {
    echo "Connection error :" . $ex->getMessage();
}
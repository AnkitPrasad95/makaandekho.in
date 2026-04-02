<?php
// Error Reporting Turn On
ini_set('error_reporting', E_ALL);

// Setting up the time zone
date_default_timezone_set('Asia/KolKata');

// Host Name
$dbhost = 'localhost';

// Database Name
$dbname = 'hpj_database';

// Database Username
$dbuser = 'root';

// Database Password
$dbpass = '';

try {
	// set the PDO error mode to exception
	$opt = [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION, PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC, PDO::ATTR_EMULATE_PREPARES => false, ];
	$this->db = new PDO("mysql:host=$dbhost;dbname=$dbname;charset=utf8", $dbuser, $dbpass, $opt);
	return $this->db;
}
catch(PDOException $e) {
	echo "Connection error :" . $e->getMessage();
	die();
}
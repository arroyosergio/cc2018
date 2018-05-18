<?php


spl_autoload_register(function ($className) {
	$path='./libs/'.$className . '.php';
	if (file_exists($path)) { 
          	require_once $path; 
    } 
});

require_once './config/paths.php';
require_once './config/database.php';
require_once './config/smtp.php';

$app = new Bootstrap();

<?php 

defined('ROOTPATH') OR exit('Access Denied!');

// spl_autoload_register(function($classname){

// 	$classname = explode("\\", $classname);
// 	$classname = end($classname);
// 	 $filename = "../app/models/".ucfirst($classname).".php";
// 	show($filename);
// });

require_once 'config.php';
require_once 'functions.ini.php';
require_once 'Database.php';
require_once 'Model.php';
require_once 'Controller.php';
require_once 'App.php';
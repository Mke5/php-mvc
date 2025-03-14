<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

class Controller
{
	// In app/Core/Controller.php

	// public function __construct()
	// {
	// 	$request = new \Core\Request();

	// 	if ($request->method() === 'POST' && !$request->validate_csrf()) {
	// 		die("CSRF Token validation failed!");
	// 	}
	// }

	protected function loadModel($model){

		if(file_exists("../app/models/".$model.".php")){
			
			include "../app/models/".$model.".php";
			return $model = new $model();
		}

		return false;
	}


	protected function view($name, $data = [])
	{
		$filename = "../app/views/".$name.".view.php";
		if(file_exists($filename))
		{
			if (is_array($data)) {
				extract($data);
			}
            require $filename;
		}else{

			$filename = "../app/views/404.view.php";
			require $filename;
		}
	}
}
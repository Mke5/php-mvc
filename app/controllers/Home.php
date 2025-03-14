<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * home class
 */
class Home extends Controller
{

	public function index()
	{

		$this->view('home');
	}

}

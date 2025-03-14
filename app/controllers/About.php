<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

/**
 * home class
 */
class About extends Controller
{

	public function index()
	{

		$this->view('about');
	}

}
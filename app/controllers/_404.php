<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');

class _404 extends Controller
{
	
	public function index()
	{
		$this->view('404');
	}
}

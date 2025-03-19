<?php 

namespace Controller;

defined('ROOTPATH') OR exit('Access Denied!');


use App\Models\User;
use Core\Session;

class Logout extends Controller
{

	public function index()
	{

        $session = new Session();
        $session->logout();
        redirect('home');
	}

}
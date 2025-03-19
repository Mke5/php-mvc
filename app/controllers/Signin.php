<?php 
namespace Controller;


defined('ROOTPATH') OR exit('Access Denied!');

use App\Models\User;
use Core\Session;


class Signin extends Controller
{

	public function index()
	{
		$this->view('signin');
	}

	public function Authenticate(){
		
		$user = new User();
		$session = new Session();
	
		if($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])){
			
			if(!$session->validate_csrf($_POST['csrf_token'])){
				$session->set('signin', 'Invalid Login');
				redirect('signin');
				die();
			}

			try{$user->validate($_POST);
			}catch (\Exception $e){
				$session->set('signin', $e->getMessage());
				redirect('signin');
				exit;
			}
			
			$email = trim(filter_var($_POST['email'], FILTER_VALIDATE_EMAIL));
			$password = $_POST['password'];


			$session->set('last_email', $email);
			
			try {
				$user = $user->login($email, $password);
				$session->auth([
					'id' => $user->id,
					'email' => $user->email,
					'fname' => $user->fname,
					'lname' => $user->lname,
					'role' => $user->role,
				]);

				$session->set('last_email', '');

				redirect('home');
				exit;
			} catch (\Exception $e) {
				$session->set('signin', $e->getMessage());
				redirect('signin');
				exit;
			}
		} else {
			redirect('signin');
			die();
		}
	}

}
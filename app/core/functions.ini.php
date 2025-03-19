<?php 

/** check which php extensions are required **/
check_extensions();
function check_extensions()
{

	$required_extensions = [

		'gd',
		'mysqli',
		'pdo_mysql',
		'pdo_sqlite',
		'curl',
		'fileinfo',
		'intl',
		'exif',
		'mbstring',
	];

	$not_loaded = [];

	foreach ($required_extensions as $ext) {
		
		if(!extension_loaded($ext))
		{
			$not_loaded[] = $ext;
		}
	}

	if(!empty($not_loaded))
	{
		show("Please load the following extensions in your php.ini file: <br>".implode("<br>", $not_loaded));
		die;
	}
}


function get_random_string_max($lenght){

  $array = array(0,1,2,3,4,5,6,7,8,9,'a','b','c','d','e','f','g','h','i','j','k','l','m','n','o','p','q','r','s','t','u','v','w','x','y','z','A','B','C','D','E','F','G','H','I','J','K','L','M','N','O','P','Q','R','S','T','U','V','W','X','Y','Z');
  $text = "";

  $lenght = rand(4, $lenght);

  for ($i=0; $i < $lenght; $i++) { 
      
      $random = rand(0, 61);
      $text .= $array[$random];
  }

  return $text;
}


function show($stuff)
{
	echo "<pre>";
	print_r($stuff);
	echo "</pre>";
}

function esc($str)
{
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}


function redirect($path)
{
	header("Location: " . ROOT_URL."/".$path);
	exit();
}

/** load image. if not exist, load placeholder **/
function get_image(mixed $file = '',string $type = 'post'):string
{

	$file = $file ?? '';
	if(file_exists($file))
	{
		return ROOT_URL . "/". $file;
	}

	if($type == 'user'){
		return ROOT_URL."/assets/images/user.webp";
	}else{
		return ROOT_URL."/assets/images/no_image.jpg";
	}

}


/** returns pagination links **/
function get_pagination_vars():array
{
	$vars = [];
	$vars['page'] 		= $_GET['page'] ?? 1;
	$vars['page'] 		= (int)$vars['page'];
	$vars['prev_page'] 	= $vars['page'] <= 1 ? 1 : $vars['page'] - 1;
	$vars['next_page'] 	= $vars['page'] + 1;

	return $vars;
}


/** saves or displays a saved message to the user **/
function message(string $msg = "", bool $clear = false)
{
	$ses 	= new Core\Session();

	if(!empty($msg)){
	    $ses->set('message',$msg);
	}else
	if(!empty($ses->get('message'))){
	    
	    $msg = $ses->get('message');
	    
	    if($clear){
	      $ses->pop('message');
	    }
	    return $msg;
	}
	
	return false;
}

/** return URL variables **/
function URL($key):mixed
{
	$URL = $_GET['url'] ?? 'home';
	$URL = explode("/", trim($URL,"/"));
	
	switch ($key) {
		case 'page':
		case 0:
			return $URL[0] ?? null;
			break;
		case 'section':
		case 'slug':
		case 1:
			return $URL[1] ?? null;
			break;
		case 'action':
		case 2:
			return $URL[2] ?? null;
			break;
		case 'id':
		case 3:
			return $URL[3] ?? null;
			break;
		default:
			return null;
			break;
	}

}


/** displays input values after a page refresh **/
function old_checked(string $key, string $value, string $default = ""):string
{

  if(isset($_POST[$key]))
  {
    if($_POST[$key] == $value){
      return ' checked ';
    }
  }else{

    if($_SERVER['REQUEST_METHOD'] == "GET" && $default == $value)
    {
      return ' checked ';
    }
  }

  return '';
}


function old_value(string $key, mixed $default = "", string $mode = 'post'):mixed
{
  $POST = ($mode == 'post') ? $_POST : $_GET;
  if(isset($POST[$key]))
  {
    return $POST[$key];
  }

  return $default;
}

function old_select(string $key, mixed $value, mixed $default = "", string $mode = 'post'):mixed
{
  $POST = ($mode == 'post') ? $_POST : $_GET;
  if(isset($POST[$key]))
  {
    if($POST[$key] == $value)
    {
      return " selected ";
    }
  }else

  if($default == $value)
  {
    return " selected ";
  }

  return "";
}

/** returns a user readable date format **/
function get_date($date)
{
	return date("jS M, Y",strtotime($date));
}

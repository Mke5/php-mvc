<?php 

defined('ROOTPATH') OR exit('Access Denied!');

// Check if the script is running from CLI
$isCLI = (php_sapi_name() === 'cli');

// Set defaults if running in CLI mode
$serverName = $isCLI ? 'localhost' : ($_SERVER['SERVER_NAME'] ?? 'localhost');

if($serverName == 'localhost')
{
	/** database config **/
	define('DBNAME', 'mvc');
	define('DBHOST', 'localhost');
	define('DBUSER', 'root');
	define('DBPASS', '');
	define('DBDRIVER', '');
	
	define('PROTOCOL', 'http');

	if (!$isCLI) {
        $path = str_replace("\\", "/", PROTOCOL . "://" . $_SERVER['SERVER_NAME'] . __DIR__ . "/"); 
        $path = str_replace($_SERVER['DOCUMENT_ROOT'], "", $path);

        define('ROOT_URL', 'http://localhost/mvc');
        define('ASSETS', str_replace("app/core", "public/assets", $path));
    }

}else
{
	/** database config **/
	define('DBNAME', 'my_db');
	define('DBHOST', 'localhost');
	define('DBUSER', 'root');
	define('DBPASS', '');
	define('DBDRIVER', '');

	define('ROOT_URL', 'https://www.yourwebsite.com');

}

define('APP_NAME', "My Webiste");
define('APP_DESC', "Best website on the planet");


define('DEBUG', true);

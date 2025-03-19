<?php 

defined('ROOTPATH') OR exit('Access Denied!');



spl_autoload_register(function ($class) {
    
    $class = str_replace("\\", "/", $class);

    $directories = [
        "../app/core/",
        "../app/models/",
        "../app/controllers/"
    ];

    foreach ($directories as $directory) {
        $filename = $directory . basename($class) . ".php";
        if (file_exists($filename)) {
            require_once $filename;
            return;
        }
    }

    error_log("Autoload error: Class '$class' not found in expected directories.");
});



require_once 'config.php';
require_once 'functions.ini.php';
require_once 'Database.php';
require_once 'Model.php';
require_once 'Controller.php';
require_once 'App.php';

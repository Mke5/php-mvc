<?php

if(PHP_SAPI !== 'cli') {
    die('This script can only be run from the command line.');
}

define('DS', DIRECTORY_SEPARATOR);
define('CPATH', __DIR__.DIRECTORY_SEPARATOR);
chdir(CPATH);

$action = $argv[1] ?? 'help';

require_once CPATH."app".DS."sh".DS."init.php";

$sh = new App\Sh\sh;

if(empty($action)) {
    call_user_func_array([$sh, 'help'], []);
} else {
    call_user_func_array([$sh, $action],[]);
}

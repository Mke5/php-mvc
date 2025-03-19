<?php

if(PHP_SAPI !== 'cli') {
    die('This script can only be run from the command line.');
}

define('CPATH', __DIR__.DIRECTORY_SEPARATOR);
chdir(CPATH);

$action = $argv[1] ?? 'help';


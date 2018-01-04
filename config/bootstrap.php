<?php

ob_start();

ini_set('display_errors', 0);
error_reporting(0);

define('wSGI', microtime());
define('DS', DIRECTORY_SEPARATOR);

$app = require __DIR__.'./application.php';
$db  = require __DIR__.'./database.php';

$fileHelpers = __DIR__.'.'.DS.'..'.DS.'core'.DS.'helpers'.DS.'functions.php';
if(!file_exists($fileHelpers))
    die('Arquivos estão ausentes!');
require $fileHelpers;

$App = new \Core\Router($app);





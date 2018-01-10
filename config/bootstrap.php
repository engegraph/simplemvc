<?php

ob_start();

define('wSGI', microtime());
define('DS', DIRECTORY_SEPARATOR);

$app = require __DIR__.'./application.php';

$fileHelpers = __DIR__.'.'.DS.'..'.DS.'core'.DS.'helpers'.DS.'functions.php';
if(!file_exists($fileHelpers))
    die('Arquivos estão ausentes!');

$DotEnv = new \Dotenv\Dotenv(__DIR__.'./../');
$DotEnv->load();

require $fileHelpers;

$Orm = new \Core\Orm();
$Orm->run();

$App = new \Core\Router($app);
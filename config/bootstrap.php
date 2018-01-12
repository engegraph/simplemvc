<?php

ob_start();

session_start();

ini_set('display_errors',1);
error_reporting(E_ALL);

define('wSGI', microtime());
define('DS', DIRECTORY_SEPARATOR);

$functions = __DIR__.'.'.DS.'..'.DS.'core'.DS.'helpers'.DS.'functions.php';

if(!file_exists($functions))
    die('Arquivos estão ausentes!');

$app = require __DIR__.'./application.php';

$DotEnv = new \Dotenv\Dotenv(__DIR__.'./../');
$DotEnv->load();

require $functions;

$Orm = new \Core\Orm();
$Orm->run();

$App = new \Core\Router($app);
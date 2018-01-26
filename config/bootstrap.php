<?php

ob_start();

ini_set('display_errors', 1);
error_reporting(E_ALL);

date_default_timezone_set('America/Sao_Paulo');
setlocale (LC_ALL, 'pt_BR');
setlocale(LC_ALL, "en_US.UTF-8");

define('wSGI', microtime());
define('DS', DIRECTORY_SEPARATOR);

ini_set('xdebug.var_display_max_depth', 5);
ini_set('xdebug.var_display_max_children', 256);
ini_set('xdebug.var_display_max_data', 1024);

$functions = __DIR__.'.'.DS.'..'.DS.'core'.DS.'helpers'.DS.'functions.php';
$helpers   = __DIR__.DS.'..'.DS.'app'.DS.'wsgi'.DS.'common'.DS.'helpers.php';

if(!file_exists($functions))
    die('Arquivo defunções do sistema está ausentes!');

if(!file_exists($helpers))
    die('Arquivo de funções da aplicação está ausentes!');

require $functions;
require $helpers;

# iniciando sessão
sec_session_init();

$app = require __DIR__.'./application.php';

$DotEnv = new \Dotenv\Dotenv(__DIR__.'./../');
$DotEnv->load();

$Orm = new \Core\Orm();
$Orm->run();

set_error_handler('Core\\Error::handler');

$App = new \Core\Router($app);
<?php namespace Core;


class Container
{
    public static function Service(string $name, $params=null)
    {
        $split = explode('.', $name);
        $modulo = array_shift($split);
        $ns = $modulo=='core' ? 'Core\\Services\\' : 'wSGI\\Modules\\'.ucfirst($modulo).'\\Services\\';
        $class = $ns.implode('\\', $split);

        if(class_exists($class))
            return new $class($params);

        trigger_error('Serviço <code>'.$class.'</code> não encontrado', E_USER_ERROR);
    }

    public static function _error(\stdClass $var)
    {
        $file = __DIR__ . './system/error.phtml';
        require_once $file;
    }
}
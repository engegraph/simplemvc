<?php namespace Core;

/**
 * Class Ajax Realiza validação de requisições ajax via headers
 * @package Core
 */

class Ajax
{
    private static $Header = 'X-wSGI-Request';
    public static $Handler;
    public static $Controller;

    public static function resolve($Controller, $Action)
    {
        $Headers = getallheaders();
        if(in_array(self::$Header, array_keys($Headers)))
        {
            if(function_exists('xdebug_disable'))
                xdebug_disable();

            self::$Handler = $Action;
            self::$Controller = $Controller;

            self::$Handler = $Name = $Headers[self::$Header];

            if(strpos($Name,'.'))
            {
                $explode = explode('.', $Name);
                self::$Controller = array_shift($explode);
                self::$Handler = array_shift($explode);
            }

            if(substr(self::$Handler,0, 2) !== 'on')
            {
                http_response_code(500);
                throw new \Exception('Método inválido: '.self::$Handler);
            }
            $class = get_called_class();
            return new $class;
        }
    }
}
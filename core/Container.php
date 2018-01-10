<?php
/**
 * Created by PhpStorm.
 * User: webcomcafe
 * Date: 31/12/2017
 * Time: 22:26
 */

namespace Core;


class Container
{
    public static function Service(string $Name)
    {
        $Class = 'Core\\Services\\'.$Name;
        if(class_exists($Class))
            return new $Class;

        die('Serviço <code>'.$Class.'</code> não encontrado');
    }
}
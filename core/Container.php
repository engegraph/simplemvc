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
    public static function Service(string $Name, $params=null)
    {
        $Class = 'Core\\Services\\'.$Name;
        if(class_exists($Class))
            return new $Class($params);

        die('Serviço <code>'.$Class.'</code> não encontrado');
    }
}
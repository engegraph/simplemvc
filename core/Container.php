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
    public static function Controller(string $Name)
    {
        if(class_exists($Name))
            return new $Name;

        die('Controller <code>'.$Name.'</code> não encontrado');
    }
}
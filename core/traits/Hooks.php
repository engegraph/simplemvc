<?php namespace Core\Traits;

use Core\MiddleWares\Nav;
use Core\Providers\ModuleBase;
use Doctrine\Common\Inflector\Inflector;

trait Hooks
{
    private $Menu = [];

    /**
     *
     */
    final private function modulesInit()
    {
        $path = __DIR__.'./../../app/wsgi/modules';
        $dir  = new \DirectoryIterator($path);
        foreach ($dir as $item)
        {
            if($item->isDot())
                continue;

            $Name  = Inflector::tableize(Inflector::camelize($item->getBasename()));
            $Class = 'wSGI\\Modules\\'.$Name.'\\Module';
            if(class_exists($Class))
            {
                $Module = new $Class;
                if($Module instanceof ModuleBase)
                {
                    $module     = key($Module->registerNavigation());
                    $navigation = $Module->registerNavigation()[$module];
                    $order = isset($navigation['order']) ? $navigation['order'] : null;
                    $navigation['module'] = $module;
                    array_insert_pos($this->Menu, $navigation, $order);
                }
            }
        }
    }


    final private function showMenuPrincipal()
    {
        var_dump($this->Menu);
        return Nav::getNavigation($this->Menu);
    }
}
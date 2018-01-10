<?php namespace Core\Traits;

use Core\Providers\ModuleBase;
use Doctrine\Common\Inflector\Inflector;

trait Module
{
    private $Menu = [];
    private $Module = null;

    final private function onModulesInit()
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
                    $name     = key($Module->registerNavigation());
                    $navigation = $Module->registerNavigation()[$name];
                    $order = isset($navigation['order']) ? $navigation['order'] : null;
                    $navigation['module'] = $name;

                    if($this->Request->Module==$name)
                    {
                        $Module->resolveNav($name);
                        $this->Module = $Module;
                        $this->Nav = $navigation;
                    }
                    array_insert_pos($this->Menu, $navigation, $order);
                }
            }
        }
    }
}
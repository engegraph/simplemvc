<?php namespace Core\Traits;

use Core\Providers\ModuleBase;
use Doctrine\Common\Inflector\Inflector;

trait Hooks
{
    private $ModuleNavigation = [];

    final protected function modulesInit()
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
                    $this->ModuleNavigation[] = $Module->registerNavigation();
                }
            }
        }
    }
}
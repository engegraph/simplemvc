<?php namespace Core\Providers;

abstract class ModuleBase
{
    public $Menu;

    public abstract function registerNavigation() : array ;

    final public function resolveNav($name)
    {
        $Nav = json_decode(json_encode($this->registerNavigation()));
        $this->Menu = $Nav->$name;
    }
}
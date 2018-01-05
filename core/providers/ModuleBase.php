<?php namespace Core\Providers;

abstract class ModuleBase
{
    protected $Navigation = [];

    public function __construct()
    {
        $this->Navigation[] = $this->registerNavigation();
    }

    public abstract function registerNavigation() : array ;
}
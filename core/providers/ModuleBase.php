<?php namespace Core\Providers;

abstract class ModuleBase
{
    public abstract function registerNavigation() : array ;
}
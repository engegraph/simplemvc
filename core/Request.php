<?php namespace Core;

class Request
{
    // public $Tenant;
    public $Module     = 'Index';
    public $Controller = 'Index';
    public $Action     = 'Index';
    public $Params     = [];

    public $isBack = false;

    public function __construct(array $App)
    {
        $Url = array_filter(explode('/', $this->getUrl()));
        $Module = ($M=array_shift($Url)) ? $M : $this->Module;
        if($Module === $App['backend'])
        {
            $this->isBack = true;
            $Module = ($M=array_shift($Url)) ? $M : $this->Module;
        }
        $Controller = ($C=array_shift($Url)) ? $C : $this->Controller;
        $Action = ($A=array_shift($Url)) ? $A : $this->Action;
        $this->Module = $Module;
        $this->Controller = $Controller;
        $this->Action = $Action;
        $this->Params = sizeof($Url) ? $Url : [];

        define('__MODULE', $this->Module);
    }

    private function getUrl()
    {
        $Url = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $Url;
    }
}
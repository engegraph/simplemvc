<?php namespace Core;

use Core\MiddleWares\Route;

class Router
{
    /**
     * @var Request
     */
    private $Request;

    /**
     * @var array $AppConfig Guarda as configurações de aplicação vindos do bootstrap
     */
    private $AppConfig = [];


    /**
     * Router constructor.
     */
    public function __construct(array $App)
    {
        $this->AppConfig = $App;
        $this->Request = new Request($this->AppConfig);
        $this->resolve();
    }

    private function resolve()
    {
        if($this->Request->isBack)
        {
            if(Route::valid($this->Request))
            {
                $Request = $this->Request;
                $Class = "wSGI\\Modules\\{$Request->Module}\\Controllers\\{$Request->Controller}";
                $Controller = new $Class($Request, $this->AppConfig); #Container::Controller($Class);

                /**
                 * Disparando evento onRun antes da chamada de qualquer action
                 */
                $Controller->onRun();

                $Response = call_user_func_array([$Controller, $Request->Action], $Request->Params);
                return $Response ? $Response : true;
            }
        }
        else
        {
            echo 'Bem bindo ao wSGI';
        }
    }
}
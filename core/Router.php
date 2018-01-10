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
            $Request = $this->Request;
            if($App = Route::valid($Request))
            {
                $App = json_decode(json_encode($this->AppConfig+$App));
                $Class = "wSGI\\Modules\\{$App->Module}\\Controllers\\{$App->Controller}";
                $Controller = new $Class($Request, $App); #Container::Controller($Class);

                /**
                 * Disparando evento onRun antes da chamada de qualquer action
                 */
                $Controller->onRun();

                $Response = call_user_func_array([$Controller, $App->Action], $Request->Params);
                return $Response ? $Response : true;
            }
        }
        else
        {
            echo 'Bem bindo ao wSGI';
        }
    }
}
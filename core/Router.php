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

                if($AjaxHandler = Ajax::resolve($App->Controller, $App->Action))
                {
                    $App->Controller = $AjaxHandler::$Controller;
                    $App->Action = $AjaxHandler::$Handler;
                }

                $Class = "wSGI\\Modules\\{$App->Module}\\Controllers\\{$App->Controller}";
                $Controller = new $Class($Request, $App); #Container::Controller($Class);

                /**
                 * Disparando evento onRun antes da chamada de qualquer action
                 */
                $Controller->onRun();

                if(!method_exists($Controller, $App->Action))
                    die('Método não encontrado');

                $Response = call_user_func_array([$Controller, $App->Action], $Request->Params);

                if($AjaxHandler)
                {
                    if(is_array($Response) || is_object($Response))
                    {
                        http_response_code(200);
                        $data['X-wSGI-Response'] = true;
                        foreach ($Response as $key => $val)
                            $data[$key] = $val;

                        echo json_encode($data);
                        return ;
                    }
                    die('O Retorno deve ser: '.$App->Action.'() : array');
                }
            }
        }
        else
        {
            echo 'Bem bindo ao wSGI';
        }
    }
}
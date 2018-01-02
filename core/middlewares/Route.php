<?php namespace Core\MiddleWares;

use Core\Container;
use Core\Request;
use Doctrine\Common\Inflector\Inflector;

class Route implements \Core\Interfaces\Request
{
    /**
     * @var Request $Request Resuisição
     */
    private static $Request;

    /**
     * @param Request $request verifica se a url não contém caracteres estranhos
     * @return bool
     */
    public static function valid(Request &$request)
    {
        $Validate = function($Str){
            if(preg_match('/^[a-z0-9\-\_\#]+$/i', $Str))
                return true;
        };

        if(!$Validate($request->Module))
            return false;

        if(!$Validate($request->Controller))
            return false;

        if(!$Validate($request->Action))
            return false;

        $request->Module     = Inflector::tableize(Inflector::camelize($request->Module));
        $request->Controller = ucfirst(Inflector::camelize($request->Controller));
        $request->Action     = Inflector::camelize($request->Action);

        self::$Request = $request;

        $class = get_called_class();
        return new $class;
    }

    /**
     * Chama o controllador requisitado
     * @return bool|mixed
     */
    public function dispatch()
    {
        $Request = self::$Request;
        if($Request->isBack)
        {
            $Class = "wSGI\\Modules\\{$Request->Module}\\Controllers\\{$Request->Controller}";
            $Controller = Container::Controller($Class);
            $Response = call_user_func_array([$Controller, $Request->Action], $Request->Params);
            return $Response ? $Response : true;
        }
    }
}
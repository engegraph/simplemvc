<?php namespace Core\Services;

use \Core\Translator;
use Doctrine\Common\Inflector\Inflector;

class Validator
{
    private static $instance = null;

    public static function __callStatic($name, $arguments)
    {
        $validate = self::init();
        if(method_exists($validate, $name))
            return call_user_func_array([$validate, $name], $arguments);

        throw new \Exception('Método '.$name.' não encontrado. Lembre-se do prefixo "validate"');
    }

    private static function init()
    {
        if(!self::$instance)
        {
            $translator = new Translator;
            $validator  = new \Illuminate\Validation\Factory($translator);
            self::customValidate($validator);
            self::$instance = $validator;
        }
        return self::$instance;
    }


    private static function customValidate(&$validator)
    {
        $Module = ucfirst(Inflector::tableize(Inflector::camelize(__MODULE)));
        $CustomValidator = "wSGI\\Modules\\{$Module}\\Util\\Validator";
        if(class_exists($CustomValidator))
        {
            $validator->resolver(function($translator, $data, $rules, $messages, $customAttributes) use ($CustomValidator){
                return new $CustomValidator($translator, $data, $rules, $messages, $customAttributes);
            });
        }
    }

}

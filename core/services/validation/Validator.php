<?php namespace Core\Services\Validation;

use Core\Orm;
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
            $presence = new \Illuminate\Validation\DatabasePresenceVerifier(Orm::$Instance->getDatabaseManager());
            $validator->setPresenceVerifier($presence);
            self::resolveCustomValidate($validator);
            self::$instance = $validator;
        }
        return self::$instance;
    }


    private static function resolveCustomValidate(&$validator)
    {
        $CustomValidator = 'wSGI\\Modules\\'.__APP_MODULE.'\\Util\\Validator';
        if(class_exists($CustomValidator))
        {
            $validator->resolver(function($translator, $data, $rules, $messages, $customAttributes) use ($CustomValidator){
                return new $CustomValidator($translator, $data, $rules, $messages, $customAttributes);
            });
        }
    }

    public function exception($validator)
    {
        $erro = $validator->errors();
        foreach ($validator->failed() as $field => $format)
        {
            $message = $erro->first($field);
            #throw new \Exception($message);
            return $message;
        }
    }
}

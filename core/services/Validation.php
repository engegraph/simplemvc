<?php namespace Core\Services;

use Core\Message;
use Core\Session;
use Doctrine\Common\Inflector\Inflector;

/**
 * Class Validator Util para validação de campos de formulários
 * A classe que herda deverá implementar métodos que poderão ser utilizados para a validação de campos de formulários
 * @package Core\Classes
 * @author Airton Lopes <airton@engegraph.com.br>
 * @copyright 1991-2017 Engegraph® Sistemas
 */

class Validation
{
    private static $data = [];

    private static $defaultMessage = 'Validação para :attr';

    private static $defaultFuncs = [
        'int' => 'O campo :attr deve ser Int',
        'bool' => 'O campo :attr deve ser Boleano',
        'string' => 'O campo :attr deve ser String',
        'array' => 'O campo :attr deve ser um Array',
        'float' => 'O campo :attr deve ser Float',
        'double' => 'O campo :attr deve ser Double',
        'required' => 'O campo :attr é necessário'
    ];

    private static $rules = [];

    private static $cutomMessages = [];

    private static $fails = [];

    private static function call($funcname, array $parrams)
    {
        $class = get_called_class();
        $instance = new $class;
        return call_user_func_array([$instance,'val'.ucfirst(Inflector::camelize($funcname))],$parrams);
    }

    protected static function data($name)
    {
        $wrap = function($name){
            $str = "['";
            if($count = substr_count($name,'.'))
            {
                $exp = explode('.',$name);
                $imp = implode("']['",$exp);
                $name = $imp;
            }
            $str .= $name;
            $str .= "']";
            return $str;
        };
        $index = $wrap($name);
        return eval('return isset(self::$data'.$index.') ?  self::$data'.$index.' : NULL;');
    }


    /**
     * @param array $data
     * @param array $rules
     * @param array $messages
     * @return bool
     */
    public static function make(array $data, array $rules, array $messages = [], $model = null)
    {
        self::$data = $data;
        $messages = array_merge(self::$defaultFuncs, $messages);
        $value = '';
        $model = $model ? "{$model}." : '';
        foreach ($rules as $rulekey => $ruleval)
        {
            foreach ($data as $datakey => $dataval)
            {
                $params = [];
                $args = [];
                $value = self::data($rulekey);
                if($value || $value == '') #$rulekey == $datakey
                {
                    $params[] = $rulekey;
                    $params[] = $value;

                    if(is_string($ruleval))
                    {
                        if(strpos($ruleval,'|') !== FALSE)
                        {
                            foreach (explode('|',$ruleval) as $val)
                            {
                                $params = [$rulekey, $value];
                                $args = [];
                                if(strpos($val,':') !== FALSE)
                                {
                                    $exp = explode(':',$val);
                                    $func = trim($exp[0]);
                                    if(strpos($exp[1],',') !== FALSE)
                                        $args = explode(',',$exp[1]);
                                    else
                                        $args[] = $exp[1];
                                }
                                else
                                {
                                    $func = $val;
                                }

                                $params[] = $args;
                                $index = "{$rulekey}.{$func}";
                                $msg = isset($messages[$index]) ? $messages[$index] : (isset($messages[$func]) ? $messages[$func] : self::$defaultMessage);
                                $msg = str_replace(':attr',$rulekey,$msg);

                                if(!self::call($func,$params))
                                    self::$fails[] = [$index => $msg];
                            }
                        }
                        else
                        {
                            if(strpos($ruleval,':') !== FALSE)
                            {
                                if(!$value)
                                    continue;

                                $exp = explode(':',$ruleval);
                                $func = trim($exp[0]);
                                if(strpos($exp[1],',') !== FALSE)
                                    $args = explode(',',$exp[1]);
                                else
                                    $args[] = $exp[1];
                            }
                            else
                            {
                                $func = $ruleval;
                            }

                            $params[] = $args;
                            $index = "{$rulekey}.{$func}";
                            $msg = isset($messages[$index]) ? $messages[$index] : (isset($messages[$func]) ? $messages[$func] : self::$defaultMessage);
                            $msg = str_replace(':attr',$rulekey,$msg);
                            if(!self::call($func,$params))
                                self::$fails[] = [$index => $msg];
                        }
                    }
                }
            }
        }

        foreach ($data as $name => $val)
            Session::set("val.{$model}{$name}",$val);

        if(!empty(self::$fails))
        {
            /*foreach ($errors as $name => $val)
                Session::set('rule.'.$name,$val);*/

            $err = array_shift(self::$fails);
            $key = key($err);
            $val = $err[$key];
            $key = substr($key,0,strrpos($key,'.'));
            Session::set("err.{$model}{$key}",$val);
            Message::danger('Problemas com validação');
            return false;
        }

        return true;
    }

    /*public function fails()
    {
        if(empty(self::$fails))
            return array_shift(self::$fails);
    }*/


    public function __call($name, $arguments)
    {
        if(in_array(strtolower(substr($name,3)),array_keys(self::$defaultFuncs)))
        {
            $value = $arguments[1];
            return eval('return is_'.strtolower(substr($name,3)).'($value);');
        }

        echo "Método <i>{$name}</i> não encontrado <br>";
    }
}
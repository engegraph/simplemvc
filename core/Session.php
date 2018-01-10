<?php

namespace Core;


class Session
{
    public static function set($name, $value)
    {
        self::wrap('set',$name,$value);
    }

    public static function get($name)
    {
        $res = self::wrap('get',$name);
        //self::del($name);
        return $res;
    }

    public static function has($name)
    {
        return self::wrap('has', $name);
    }

    public static function del($name)
    {
        self::wrap('del',$name);
        self::flush();
    }

    public static function all()
    {
        return $_SESSION;
    }

    public static function flush()
    {
        $_SESSION = self::destroy();
    }

    public static function destroy()
    {

        $sess = self::all();

        if(sizeof($sess))
        {
            foreach ($sess as $name => $value)
                unset($_SESSION[$name]);

            @session_destroy();

            session_start();

        }

        return $sess;
    }

    private static function wrap($acao, $name = null, $val = null)
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

        switch ($acao)
        {
            # ['alert']['error']
            case 'set':
                if(substr($index,0,9)=="['alert']")
                    $index = "['alert'][]".substr($index,9);

                eval('$_SESSION'.$index.' = $val;');
                break;
            case 'get':
                return eval('return isset($_SESSION'.$index.') ? $_SESSION'.$index.' : NULL;');
                break;
            case 'has':
                return eval('return isset($_SESSION'.$index.') ? TRUE : NULL;');
                break;
            case 'del':
                eval('if(isset($_SESSION'.$index.')) unset($_SESSION'.$index.');');
                break;
            default:
                break;
        }
    }
}
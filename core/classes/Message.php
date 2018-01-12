<?php namespace Core\Classes;


class Message
{
    public static function success($value)
    {
        Session::set('alert.success',$value);
    }

    public static function danger($value)
    {
        Session::set('alert.danger',$value);
    }

    public static function warning($value)
    {
        Session::set('alert.warning',$value);
    }

    public static function info($value)
    {
        Session::set('alert.info',$value);
    }

    public static function set($name, $val)
    {
        Session::set($name, $val);
    }

    public static function get($name)
    {
        $msg = Session::get($name);
        Session::del($name);
        return $msg;
    }

    public static function alerts()
    {
        if(Session::has('alert'))
        {
            $Alerts = Session::get('alert');
            Session::del('alert');
            return $Alerts;
        }
    }

}
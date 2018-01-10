<?php

namespace Core;


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

    private static function alerts_old($raw = false)
    {
        $message = [];

        if(Session::has('alert.danger'))
            $message['danger'] = Session::get('alert.danger');

        if(Session::has('alert.success'))
            $message['success'] = Session::get('alert.success');

        if(Session::has('alert.warning'))
            $message['warning'] = Session::get('alert.warning');

        if(Session::has('alert.info'))
            $message['info'] = Session::get('alert.info');

        if(empty($message))
            return '';

        if($raw)
            return $message;

        $htm = '';
        foreach ($message as $class => $msg):

        $htm .= <<< htm
            <span class="alert-close fa fa-close" title="Fechar"></span>
            <div class="alert alert-{$class} text-center">{$msg}</div>
htm;
            Session::del('alert');
        endforeach;

        return $htm;
    }
}
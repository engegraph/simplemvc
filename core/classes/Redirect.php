<?php namespace Core\Classes;

class Redirect
{
    public static function to($url)
    {
        header("Location: {$url}");
        return self::getInstance();
    }

    public static function refresh()
    {
        self::to(url());
        return self::getInstance();
    }

    public function withAlert($name,$message)
    {
        Message::set("alert.{$name}", $message);
    }

    public function withMessage($name, $message)
    {
        Message::set($name,$message);
    }

    private static function getInstance()
    {
        $instance = get_called_class();
        return new $instance;
    }
}
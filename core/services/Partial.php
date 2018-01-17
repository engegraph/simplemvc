<?php namespace Core\Services;

class Partial
{
    public function get($name, $model, $data = null)
    {
        $name = str_replace(['\\', '/'], '', $name);
        $name = str_replace('.', DS, $name);
        $path = __DIR__.'.'.DS.'..'.DS.'..'.DS.'app'.DS.'wsgi'.DS.'modules'.DS.__REQUEST_MODULE.DS.'partials'.DS;
        $file = $path.$name.'.phtml';
        if(!file_exists($file))
            trigger_error('Partial não encontrado '.$file, E_USER_ERROR);

        require $file;
        return '';
    }
}
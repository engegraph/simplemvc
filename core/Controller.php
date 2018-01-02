<?php namespace Core;

class Controller
{
    use \Core\Traits\Events,
        \Core\Traits\Files;

    private $path;
    private $pathAssets;
    private $view;

    public function __construct()
    {
        $this->onInit();
    }

    final protected function view(string $name, $layout = 'default')
    {
        $this->view = $name;
        $this->path = __DIR__.'.'.DS.'..'.DS.'app'.DS.$path;
        $this->pathAssets = url('/app/'.$path);

        if($layout)
        {
            $this->layout($layout);
        }
        else
        {
            $this->page();
        }
    }

    private function page()
    {
        $view = $this->path.DS.'views'.DS.$this->view.'.phtml';
        if(!file_exists($view))
            die('View não encontrada : <code>'.$view.'</code>');

        require_once $view;
        $content = ob_get_flush();
        ob_end_clean();
        return $content;
    }

    private function layout(string $name)
    {
        $layout = __DIR__.'.'.DS.'..'.DS.'app'.DS.'templates'.DS.$this->App['template'].DS.'layouts'.DS.$name.'.phtml';
        if(!file_exists($layout))
            die('Layout não encontrado : <code>'.$layout.'</code>');

        require_once $layout;
    }


    public function path()
    {
        $path = '';
        $path = __DIR__.'.'.DS.'..'.DS.'app'.DS.$path;
    }

    public function pathAssets(string $file = null)
    {
        $modulePath = 'modules'.DS.$this->Request->Module;
        $path = $this->Request->isBack ? 'wsgi'.DS.$modulePath : 'web'.DS.$modulePath;
        $url = url('/app/'.$path);
        return $file ? $url.'/'.$file : $url;
    }
}
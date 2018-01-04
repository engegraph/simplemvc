<?php namespace Core;

class Controller
{
    use \Core\Traits\Events,
        \Core\Traits\Files;

    private $view;
    private $content = '';
    private static $checkViewinc = 0;

    public function __construct()
    {
        $this->onInit();
    }

    final protected function view(string $name)
    {
        $view = $this->path().DS.'views'.DS.$name.'.phtml';
        if(!file_exists($view))
            die('View não encontrada : <code>'.$view.'</code>');

        require_once $view;
        $content = ob_get_clean();
        $this->viewFilter($content);
        $this->content = $content;
        $this->layout();
    }

    private function page()
    {
        return $this->content;
    }

    private function layout()
    {
        $layout = __DIR__.'.'.DS.'..'.DS.'app'.DS.'templates'.DS.$this->App['template'].DS.'index.phtml';
        if(!file_exists($layout))
            die('Layout não encontrado : <code>'.$layout.'</code>');

        require_once $layout;
    }

    private function viewFilter(string &$Content)
    {
        $Pattern = '/{\s*%\s*put\s*(?<tag>style|script)\s*%\s*}(?<content>[^%]+)*{\s*%\s*endput\s*%\s*}/im';
        $Assets  = [];
        $Result  = preg_replace_callback($Pattern, function($m) use (&$Assets){

            $tagname = $m['tag'];
            $content = $m['content'];

            if(preg_match('/^\s*<'.$tagname.'(\s*>|\s+[^>]+>)/i', $content))
            {
                $Assets[$tagname][] = $content;
                return '';
            }
            else
                return $content;

        }, $Content);

        if(isset($Assets['style']))
            $this->assets['style'] = $Assets['style'];
        if(isset($Assets['script']))
            $this->assets['script'] = $Assets['script'];

        $Content = $Result;
    }

    public function path()
    {
        $modulePath = 'modules'.DS.$this->Request->Module;
        $path = $this->Request->isBack ? 'wsgi'.DS.$modulePath : 'web'.DS.$modulePath;
        return __DIR__.'.'.DS.'..'.DS.'app'.DS.$path;
    }

    public function pathAssets(string $file = null)
    {
        $modulePath = 'modules'.'/'.$this->Request->Module;
        $path = '/app/'.($this->Request->isBack ? 'wsgi'.'/'.$modulePath : 'web'.'/'.$modulePath).'/assets';
        return url($file ? $path.'/'.$file : $path);
    }
}
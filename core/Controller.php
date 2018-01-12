<?php namespace Core;

use Core\Classes\Message;

class Controller
{
    use \Core\Traits\Events,
        \Core\Traits\Assets,
        \Core\Traits\Navigation,
        \Core\Traits\Crud;

    /**
     * @var $Nav array informações de navegação do módulo
     */
    public $Nav;

    /**
     * @var bool $Model Model Atrelado ao controlador
     */
    public $model = TRUE, $Model = TRUE;

    /**
     * @var \stdClass $App Informações da aplicação
     */
    public $App;

    /**
     * @var Request $Request Informações da requisição
     */
    public $Request;

    /**
     * Parâmetros diversos em diferentes locais da aplicação
     * @var array
     */
    public $Param = [];

    /**
     * @var $content string Conteúdo da viwe a ser exibida
     */
    private $content;


    final public function __construct(Request $Request, \stdClass $App)
    {
        /**
         * Setando variáveis da aplicação
         */
        $this->Request = $Request;
        $this->App = $App;

        /**
         * Disparando evento onInit, antes de tudo começar
         */
        $this->onInit();

        /**
         * Preparando os módulos
         */
        $this->onModulesInit();

        /**
         * Configurando model padrão atrelado ao controller
         */
        $this->onConfigModel();

        /**
         * Preparando o menu principal
         */
        $this->onNavigationInit();

        /**
         * Adiconando alguns serviços
         */
        $this->addService('csrf');
        #$this->addService('validation.validator');
    }

    final protected function view(string $name, $data = null)
    {
        $view = $this->path().DS.'views'.DS.$this->Request->Controller.DS.$name.'.phtml';
        if(!file_exists($view))
            die('View não encontrada : <code>'.$view.'</code>');

        $this->setBreadCrumbs();

        /**
         * Disparando evento onRender, antes de renderizar uma view
         */
        $this->onRender();

        require_once $view;
        $content = ob_get_clean();
        $this->viewFilter($content);
        $this->content = $content;
        $this->layout();
    }

    private function content()
    {
        return $this->content;
    }

    private function layout()
    {
        $layout = __DIR__.'.'.DS.'..'.DS.'app'.DS.'templates'.DS.$this->App->template.DS.'index.phtml';
        if(!file_exists($layout))
            die('Layout não encontrado : <code>'.$layout.'</code>');

        require_once $layout;

        /**
         * Disparando evento onEnd, após a página ter sideo exibida completamente
         */
        $this->onEnd();
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

    final public function path()
    {
        $modulePath = 'modules'.DS.$this->Request->Module;
        $path = $this->Request->isBack ? 'wsgi'.DS.$modulePath : 'web'.DS.$modulePath;
        return __DIR__.'.'.DS.'..'.DS.'app'.DS.$path;
    }

    final public function pathAssets(string $file = null)
    {
        $modulePath = 'modules'.'/'.$this->Request->Module;
        $path = '/app/'.($this->Request->isBack ? 'wsgi'.'/'.$modulePath : 'web'.'/'.$modulePath).'/assets';
        return url($file ? $path.'/'.$file : $path);
    }

    /**
     * Atrela um serviço ao controller
     * @param string $name
     * @param null $alias
     */
    final protected function addService(string $name, $alias=null, $params=null) : void
    {
        $Name = $alias ? $alias : $name;
        $this->{$Name} = Container::service(ucfirst($name), $params);
    }


    /**
     * Exibe as mensagens
     * @return mixed
     */
    protected function alerts()
    {
        return Message::alerts();
    }
}
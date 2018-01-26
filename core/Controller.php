<?php namespace Core;

use Core\Classes\Message;
use Core\Classes\Session;

class Controller
{
    use \Core\Traits\Events,
        \Core\Traits\Assets,
        \Core\Traits\Navigation,
        \Core\Traits\Crud,
        \Core\Classes\Modals\Modal,
        \wSGI\Common\Util\Globals;

    /**
     * @var array
     */
    protected $pageInfo = [];

    /**
     * @var $Nav array informações de navegação do módulo
     */
    public $Nav;

    /**
     * @var bool $Model Model Atrelado ao controlador
     */
    public $model = TRUE, $Model = TRUE, $crud = true;

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
        $this->addService('core.partial');
        $this->addService('core.csrf');
        $this->addService('core.validation.validator');
        $this->addService('auth.authentication', 'auth');

        /**
         * Adiciona alguns arquivos js/css padrão ao sistema
         */
        $this->defaultAssets();
    }

    /**
     * Redenriza uma view
     * @param string $name
     * @param string $layout
     */
    final protected function view(string $name, $layout = 'default')
    {
        if(!$this->viewExists($name))
            trigger_error('View não encontrada : <code>'.$name.'</code>', E_USER_ERROR);

        $name = str_replace(['/','\\'], '', $name);
        $name = str_replace('.',DS, $name);
        $view = $this->path().DS.'views'.DS.$name.'.phtml';

        $this->setBreadCrumbs();

        /**
         * Disparando evento onRender, antes de renderizar uma view
         */
        $this->onRender();

        require_once $view;

        if($layout)
        {
            $content = ob_get_clean();
            $this->viewFilter($content);
            $this->content = $content;
            $this->layout($layout);
        }

        /**
         * Disparando evento onEnd, após a página ter sideo exibida completamente
         */
        $this->onEnd();
    }

    private function content()
    {
        return $this->content;
    }

    private function layout($name)
    {
        $layout = __DIR__.'.'.DS.'..'.DS.'app'.DS.'wsgi'.DS.'templates'.DS.$this->App->template.DS.'layouts'.DS.$name.'.phtml';
        if(!file_exists($layout))
            trigger_error('Layout não encontrado : <code>'.$layout.'</code>', E_USER_ERROR);

        require_once $layout;

        # Apagando mensagens
        $this->clearMessages();
    }


    /**
     * Inclui um bloco de conteúdo
     * @param $name
     * @param null $data
     * @return string
     */
    final protected function partial($name, $data = null)
    {
        return $this->partial->get($name, $this->model, $data);
    }

    /**
     * Verifica se uma view existe
     * @param string $name
     * @return bool
     */
    final protected function viewExists(string $name) : bool
    {
        $name = str_replace(['/','\\'], '', $name);
        $name = str_replace('.',DS, $name);
        $view = $this->path().DS.'views'.DS.$name.'.phtml';
        if(file_exists($view))
            return true;
        return false;
    }


    /**
     * Capturar variaveis definidas na propriedade pageInfo. Util para transportar informações entre as diferentes views
     * @param string $name
     * @return string
     */
    final protected function get(string $name) : string
    {
        if(key_exists($name, $this->pageInfo))
            return $this->pageInfo[$name];

        return $name;
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
        $path = 'wsgi'.DS.$modulePath;
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
        $name = strtolower(str_replace(['/','\\'], '', $name));
        $split = explode('.', $name);
        $service = $alias ? $alias : end($split);
        $this->{$service} = Container::service($name, $params);
    }


    /**
     * Exibe as mensagens
     * @return mixed
     */
    protected function alerts()
    {
        return Message::alerts();
    }

    /**
     * Retorna o link para salvar os dados
     * @return string
     */
    private function DataPoint()
    {
        $url = backend_url("/{$this->Request->Module}/{$this->Request->Controller}/save");
        if($Uuid=$this->model->Id)
            $url .= '/'.$Uuid;

        return $url;
    }

    /**
     * Retorna a mensagem de errro atual de validação
     * @param string $var
     * @return mixed
     */
    final protected function err(string $var)
    {
        $split = explode('.', $var);
        $count = count($split);

        $sessmodel = $split[$count-2];
        $sesskey   = $split[$count-1];
        $sessname  = 'err.'.$sessmodel.'.'.$sesskey;

        if($e = Session::get($sessname))
        {
            echo ' has-error';
            Session::del($sessname);
            return $e;
        }
    }

    /**
     * Retorna o valor da propriedade que está na sessão, ou no model, ou vazio
     * @param string $var
     * @return mixed|string
     */
    final public function val(string $var)
    {
        $split = explode('.', $var);
        $count = count($split);

        $sessmodel = $split[$count-2];
        $sesskey   = $split[$count-1];
        $sessname  = 'val.'.$sessmodel.'.'.$sesskey;

        $class = array_shift($split);
        $model = $this->model;
        if($class == $model->getClass())
        {
            if(count($split) > 1)
                $relation = implode('->', $split);
            else
                $relation = $split[0];

            $property = '$model->'.$relation;
            $eval     = eval('return '.$property.' ?? NULL;');
            $value    = Session::has($sessname) ? Session::get($sessname) : ($eval ? $eval : '');
            return $value;
        }
    }

    /**
     * Apaga as mensagens de erros e valores previalmente enviados pelos formulário
     */
    final private function clearMessages()
    {
        Session::del('err');
        Session::flush();

        Session::del('val');
        Session::flush();
    }


    /**
     * Adiciona arquivos css e js
     */
    private function defaultAssets() : void
    {
        /**
         * Estilos do sistema
         */
        $this->addStyle(url('/core/system/assets/css/system.css'));
        $this->addStyle(url('/core/system/assets/css/inputs.elegant.css'));
        $this->addStyle(url('/core/system/assets/css/bootstrap-datetimepicker.min.css'));
        $this->addStyle(common_assets('css/globals.css'));

        /**
         * Escripts do sistema
         */
        $this->addScript(tpl_assets('js/plugin/moment/moment.min.js'));
        $this->addScript(tpl_assets('js/plugin/moment/locale/pt-br.js'));
        $this->addScript(url('/core/system/assets/js/autosize.min.js'));
        $this->addScript(url('/core/system/assets/js/bootstrap-datetimepicker.min.js'));
        $this->addScript(url('/core/system/assets/js/system.js'));
        $this->addScript(tpl_assets('js/wsgi.app.js'));
        $this->addScript(common_assets('js/globals.js'));
    }
}
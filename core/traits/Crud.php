<?php namespace Core\Traits;

use Core\Classes\Message;
use Core\Classes\Redirect;
use Core\Classes\Session;
use Doctrine\Common\Inflector\Inflector;

trait Crud
{
    public function index()
    {
        return $this->view('index');
    }

    public function cadastro()
    {
        return $this->view('cadastro');
    }

    public function save($Uuid = null)
    {
        $base = backend_url('/'.$this->Request->Module.'/'.$this->Request->Controller);
        $local = $base.($Uuid ? '/editar/'.$Uuid : '/cadastro');
        try
        {
            if($this->csrf->resolve())
            {
                if(post('_save'))
                {
                    if($res = $this->model->push())
                    {
                        $to = $this->getRedirectInfo($base, $res, $Uuid);
                        return Redirect::to($to['url'])->withAlert('success', $to['message']);
                    }
                    return Redirect::to($local)->withAlert('warning', 'Verifique se informou os dados corretamente');
                }
            }
        }
        catch (\Exception $e)
        {
            return Redirect::to($local)->withAlert('danger', $e->getMessage());
        }
    }


    /**
     * Retorna a url de destino asssim como sua mensagem de alerta, após uma submissão de formulário
     * @param $result
     * @param $url
     * @return array
     */
    private function getRedirectInfo($url, $res, $uuid) : array
    {
        $redirect = '';

        if(is_guid($res))
            $message = 'Cadastro realizado com sucesso :)';
        else
            $message = 'Atualização bem sucedida :)';

        $uuid = is_guid($res) ? $res : $uuid;

        if(post('_save')=='-1')
            $redirect = $url."/editar/{$uuid}";
        if(post('_save')=='1')
        {
            Session::del('val');
            $redirect = $url."/cadastro";
        }
        if(post('_save')=='2')
            $redirect = $url;

        return ['url'=>$redirect, 'message'=>$message];
    }

    public function editar($Uuid)
    {
        return $this->view('editar');
    }

    public function onDelete()
    {

    }

    /**
     * onConfigModel atrela um model ao controlador instanciado
     */
    private function onConfigModel() : void
    {
        if($Name = $this->model)
        {
            $Ns = 'wSGI\\Modules\\'.$this->App->Module.'\\Models\\';
            if(is_string($Name))
            {
                $Class = $Ns.$Name;
            }
            else
            {
                $Name = Inflector::singularize($this->Request->Controller);
                $Class = $Ns.$Name;
            }

            $Model = new $Class;;

            if(@$Uuid=$this->Request->Params[0])
            {
                if(is_guid($Uuid) && ($this->Request->Action=='editar' || $this->Request->Action=='save'))
                {
                    $Model = $Class::find($Uuid);
                }
            }
            $this->model = $this->Model = $Model;
        }
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
}
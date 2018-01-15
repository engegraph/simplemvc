<?php namespace Core\Traits;

use Core\Classes\Message;
use Core\Classes\Redirect;
use Core\Classes\Session;
use Doctrine\Common\Inflector\Inflector;

trait Crud
{
    public function index()
    {
        return $this->view($this->App->Controller.'.index');
    }

    public function cadastro()
    {
        return $this->view($this->App->Controller.'.cadastro');
    }

    /**
     * Responsável por enviar os dados para ser salvo no modelo
     * @param null $Uuid
     * @return mixed
     */
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

    public function editar($Uuid)
    {
        return $this->view($this->App->Controller.'editar');
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

            if(isset($this->Request->Params[0]))
            {
                $Uuid = $this->Request->Params[0];
                if(is_guid($Uuid) && ($this->Request->Action=='editar' || $this->Request->Action=='save'))
                {
                    $Model = $Class::find($Uuid);
                }
            }
            $this->model = $this->Model = $Model;
        }
    }


    /**
     * Remoção de registros
     * @return array
     */
    public function onDelete()
    {
        $Name = Inflector::singularize($this->App->Controller).'.Uuid';
        if($Uuids = post($Name))
        {
            try
            {
                foreach ($Uuids as &$uuid)
                    $uuid = str_guid($uuid, true);

                forward_static_call_array([$this->model, 'destroy'], $Uuids);
                $msg = (count($Uuids) > 1 ? 'Registros eliminados' : 'Registro eliminado').' com sucesso !';
                Message::info($msg);
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();
            }

            return ['redirect' => backend_url('/'.$this->Request->Module.'/'.$this->Request->Controller)];
        }
    }
}
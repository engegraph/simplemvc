<?php namespace Core\Traits;

use Doctrine\Common\Inflector\Inflector;
use Webpatser\Uuid\Uuid;

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
    public function create()
    {

    }

    public function editar($Uuid)
    {
        return $this->view('editar');
    }
    public function update($Uuid)
    {
        if($this->csrf->resolve())
        {
            try
            {
                var_dump($this->Model);
            }
            catch (\Exception $e)
            {
                echo $e->getMessage();
            }
        }
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
                if(is_guid($Uuid) && ($this->Request->Action=='editar' || $this->Request->Action=='update'))
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
        $url = backend_url("/{$this->Request->Module}/{$this->Request->Controller}");
        if($Uuid=$this->Model->Id)
            $url .= '/update/'.str_guid($Uuid);
        else
            $url .= "/create";

        return $url;
    }

    final public function err()
    {

    }

    final public function val()
    {

    }
}
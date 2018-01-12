<?php namespace Core\Traits;

use Core\Session;
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

    /**
     * Retorna a mensagem de errro atual de validação
     * @param string $var
     * @return mixed
     */
    final protected function err(string $var)
    {
        $name = "err.{$var}";
        if( $e = Session::get($name))
        {
            echo ' has-error';
            Session::del($name);
            return $e;
        }
    }

    final public function val(string $var)
    {
        $split = explode('.', $var);
        $class = array_shift($split);
        if($class == $this->model->getClass())
        {
            if(count($split) > 1)
                $relation = implode('->', $split);
            else
                $relation = $class.'->'.$split[0];

            $model = $this->model;
            $property = $model->Endereco->Cep;
            var_dump($split, $relation, $model, $property);
        }
    }
}
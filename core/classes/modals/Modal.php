<?php namespace Core\Classes\Modals;

use Core\Classes\Message;
use Core\Classes\Session;
use Webpatser\Uuid\Uuid;

trait Modal
{
    /**
     * @var $Model Object Intancia do modelo
     */
    private $_Model;

    /**
     * @var Form $Form Object Instancia do Formulário
     */
    private $Form;

    private function init()
    {
        try
        {
            if(!post('modelName'))
                throw new \Exception('Modelo não informado');

            $Class = 'wSGI\\Modules\\Controles\\Models\\'.ucfirst(post('modelName'));
            $Uuid = ($Id=post('uuid')) ? str_guid($Id, true) : Null;
            $this->_Model = $Model = $Uuid ? $Class::find($Uuid) : new $Class;
            $this->Form = new Form($Model);
        }
        catch (\Exception $e){
            echo $e->getMessage();
            return http_response_code(500);
        }
    }

    public function onGetModalModel()
    {
        try
        {
            $this->init();
            return ['list'=>$this->Form->getList(), 'modelName'=>post('modelName')];
        }
        catch (\Exception $e)
        {
            echo $e->getMessage();
            return http_response_code(500);
        }
    }

    public function onGetModelForm()
    {
        try
        {
            $this->init();
            return ['form' => $this->Form->getMarckup(), 'uuid'=>$this->_Model->Id];
        }
        catch (\Exception $e)
        {
            echo $e->getMessage();
            return http_response_code(500);
        }
    }


    public function onSaveModel()
    {
        try
        {
            $this->init();
            $Model = $this->_Model;
            if($Result=$Model->save())
            {
                /*$Response = ['success'=>true];
                if(is_guid($Result))
                    $Response['Uuid'] = $Result;

                return $Response;*/

                $Highlight = Uuid::validate($Result) ? $Result : $Model->Id;
                return ['alerts' => [['content'=>'Alterações salvas com sucesso :)', 'type' => 'success']], 'success'=>true, 'list'=>$this->Form->getList($Highlight)];
            }
            return ['alerts'=>$this->getMessages()];
        }
        catch (\Exception $e){
            echo $e->getMessage();
            return http_response_code(500);
        }
    }


    public function onModelDelete()
    {
        try{

            $this->init();
            $Model = $this->_Model->getClass();
            $Uuids = post("{$Model}.Uuid");
            foreach ($Uuids as &$Uuid)
                $Uuid = str_guid($Uuid, true);

            forward_static_call_array([$this->_Model, 'destroy'], $Uuids);
            $msg = (sizeof($Uuids) > 1 ? 'Registros eliminados' : 'Registro eliminado').' com sucesso !';
            return ['alerts' => [['type'=>'info', 'content'=>$msg]], 'success'=>true, 'list'=>$this->Form->getList()];

        }
        catch (\Exception $e){
            echo $e->getMessage();
            return http_response_code(500);
        }
    }

    private function getMessages() : array
    {
        $Messages = [];
        if(Session::has('alert'))
        {
            $Alerts = Message::alerts();
            foreach ($Alerts as $alert)
            {
                $type = key($alert);
                $Alert = ['type'=>$type, 'content'=>$alert[$type]];
                $Messages[] = $Alert;
            }
        }
        if(Session::has('err'))
        {
            $Err = Session::get('err');
            Session::del('err');
            $Model = key($Err);
            $Name  = key($Err[$Model]);
            $Alert = ['type'=>'warning', 'content'=>$Err[$Model][$Name], 'fieldName'=>"{$Model}[{$Name}]"];
            $Messages[] = $Alert;
        }
        return $Messages;
    }


    /**
     * Retorna instancia do Model
     * @return mixed
     */
    private function getModel()
    {
        $Class = 'wSGI\\Modules\\Controles\Models\\';
        $Model = '';
        foreach (post() as $Name => $Val)
        {
            if(substr($Name,0,8)=='Controle' &&  is_array($Val))
            {
                $Model .= $Name;
                $Class .= $Model;
                break;
            }
        }
        if(class_exists($Class))
        {
            $Obj = ($uuid=post("{$Model}.Uuid")) ? $Class::find($uuid) : new $Class;
            return $Obj;
        }
    }


}
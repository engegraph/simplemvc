<?php namespace Core;

use Core\Traits\Validator;
use Doctrine\Common\Inflector\Inflector;
use Illuminate\Database\Capsule\Manager;
use Webpatser\Uuid\Uuid;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use Validator;

    public $columns = [];

    public $modalColumns = [];

    protected $guarded = ['*'];

    public $fillable = ['*'];

    public $dispatchesEvents = true;

    public $incrementing = false;

    protected $primaryKey = 'Id';

    protected $keyType = 'string';

    protected $dateFormat = 'Y-m-d H:i:s';

    const CREATED_AT = 'DataCriacao';

    const UPDATED_AT = 'DataAtualizacao';

    const EVENTS = [
        'creating'  => 'onBeforeCreate',
        'created'   => 'onAfterCreate',
        'updating'  => 'onBeforeUpdate',
        'updated'   => 'onAfterUpdate',
        'saving'    => 'onBeforeSave',
        'saved'     => 'onAfterSave',
        'deleting'  => 'onBeforeDelete',
        'deleted'   => 'onAfterDelete',
        #'restoring' => 'onBeforeRestore',
        #'restored'  => 'onAfterRestore'
    ];

    /**
     * @var \PDO
     */
    protected $Conn;

    public function __construct(array $attributes = [])
    {
        $this->Conn = \Illuminate\Database\Capsule\Manager::connection()->getPdo();
        #$this->Schema = new \Illuminate\Database\Schema\Builder($this->getConnection());

        $this->infoTable();
        $this->applyValidate();
        parent::__construct($attributes);
    }

    /**
     * Recupera as colunas da tabela do atual modelo
     * @return bool|int|string|array
     */
    private function getColumnsTable() : array
    {
        $sql = "SELECT COLUMN_NAME,COLUMN_DEFAULT,DATA_TYPE,CHARACTER_MAXIMUM_LENGTH,IS_NULLABLE FROM INFORMATION_SCHEMA.COLUMNS WHERE TABLE_NAME = ':table'";
        $res = $this->rawQuery($sql,['table'=>'this']);
        return $res;
    }

    /**
     * Executa uma query manual
     * @param $sql
     * @param array $bindings
     * @return bool|int|string
     */
    public function rawQuery($sql, array $bindings = [])
    {
        $sql = trim($sql);
        if(!empty($bindings))
        {
            foreach ($bindings as $bind => &$value)
            {
                if($value == 'this') $value = $this->table;
                $sql = str_replace(":{$bind}",$value, $sql);
            }
        }

        $command = substr($sql,0,strpos($sql,' '));
        $stmt = $this->Conn->prepare($sql,[\PDO::ATTR_EMULATE_PREPARES=>true]);
        if($stmt->execute($bindings))
        {
            switch (strtoupper($command))
            {
                case 'SELECT':
                    if($stmt->rowCount())
                    {
                        $data = $stmt->fetchAll(\PDO::FETCH_OBJ);
                        return sizeof($data) > 1 ? $data : $data[0];
                    }
                    break;
                case 'UPDATE':
                case 'DELETE':
                    return $stmt->rowCount();
                    break;
                case 'INSERT':
                    return $this->Conn->lastInsertId();
                    break;
                default:
                    return false;
                    break;
            }
        }
        else
            return false;
    }


    /**
     * Pesquisa pelos dados do modelo na variável global POST
     * @return array
     */
    private function searchModelInPostData($SetId = false)
    {
        $Model = $this->getClass();
        $SearchPostData = function(array &$Data = []) use (&$SearchPostData, $Model, $SetId){
            $Data = sizeof($Data) ? $Data : $_POST;
            if(sizeof($Data))
            {
                foreach ($Data as $Name => &$Value)
                {
                    if(is_array($Value))
                    {
                        if($Name==$Model)
                        {
                            if($SetId)
                                $Value['Uuid'] = $this->Id;

                            return [$Name=>$Value];
                        }
                        return $SearchPostData($Value);
                    }
                }
            }
        };
        return $SearchPostData($_POST);
    }


    /**
     * @param array $options
     * @return bool|string
     * @throws \Exception
     */
    public function save(array $options = [])
    {
        $data = $this->searchModelInPostData();
        if(!empty($data))
            $this->populate($data[$this->getClass()]);

        if($this->validate())
        {
            $primaryKey = $this->primaryKey;
            if(!$this->$primaryKey)
            {
                $key = $this->getUuid();
                $this->$primaryKey = $key;
                if(parent::save())
                {
                    return trim($key,'{}');
                }
            }
            return parent::save($options); // TODO: Change the autogenerated stub
        }
    }


    /**
     * Salva no modelo atual e em tabelas relacionadas
     * @param array $Relations
     * @return bool|string
     */
    public function saveAll(array $Relations = [])
    {
        $Model = $this->getClass();
        $Data = $Relations ? $Relations : post($Model);
        $Ns = 'App\\Modules\\'.(defined('ADMIN') ? 'wSGI' : 'Site').'\\Models\\';

        $SaveAll = function(array $Relations, &$Obj) use (&$SaveAll, $Ns){
            if(sizeof($Relations))
            {
                $Attr = [];
                foreach ($Relations as $Name => $Val)
                {
                    if(is_array($Val))
                    {
                        if(!$Obj->validate())
                            return false;

                        $Class = $Ns.$Name;
                        $Fk = "{$Name}Id";
                        $Model = ($Id=$Obj->$Fk) ? forward_static_call_array([$Class,'find'],[$Id]) : new $Class;
                        $SaveAll($Val, $Model);
                        $ResId = $Model->save();
                        $Obj->$Fk = $Id ? $Id : $ResId;

                    }
                    else
                    {
                        $Attr[$Name] = $Val;
                        $Obj->$Name = $Val;
                    }
                }
            }
        };

        try
        {
            Manager::connection()->beginTransaction();
            $SaveAll($Data, $this);
            $result = $this->save();
            Manager::connection()->commit();
            return $result;
        }
        catch (\Exception $e){
            Manager::connection()->rollBack();
            Message::danger('Erro :: '.$e->getMessage());
            return false;
        }
    }


    /**
     * Recupera o nome da tabela do modelo atual. Para posteriormente recuperar suas colunas
     * e seta-las como como atributos do modelo
     * @return void
     */
    private function infoTable()
    {
        $model = $this->getClass();
        $this->table = ($t=$this->table) ? $t : Inflector::pluralize($model);
        $columns = $this->getColumnsTable();
        if(sizeof($columns))
        {
            foreach ($columns as $obj)
            {
                $Name  = $obj->COLUMN_NAME;
                $this->attributes[$Name] = $obj->COLUMN_DEFAULT;
                $Size  = ($Size=$obj->CHARACTER_MAXIMUM_LENGTH) ? $Size : '0';
                $Type  = $this->getFieldDbType($Name, ['type'=>$obj->DATA_TYPE, 'size'=>$Size]);
                $Label = Inflector::ucwords(str_replace('_',' ',Inflector::tableize($Name)));
                $Column = [
                    'type'      => $Type,
                    'label'     => $Label,
                    'showlabel' => true,
                    'form'      => true,
                    'list'      => true,
                    'link'      => true,
                ];

                if($Name=='DataCriacao')
                {
                    $Column['form'] = false;
                    $Column['list'] = false;
                }
                if($Name=='DataAtualizacao')
                {
                    $Column['form'] = false;
                    $Column['list'] = false;
                }

                $Modal = isset($this->modalColumns[$Name]) ? $this->modalColumns[$Name] : [];
                $Merge = array_merge($Column, $Modal);
                $Merge['type_raw'] = $obj->DATA_TYPE;
                $Merge['size'] = $Size;
                $Merge['default'] = $obj->COLUMN_DEFAULT;
                $Merge['nullable'] = $obj->IS_NULLABLE;
                $this->columns[$Name] = $Merge;
            }
        }
        else
            trigger_error('Não possível ler as informações da tabela '.$this->table, E_USER_ERROR);
    }


    /**
     * Retorna o nome da classe atual
     * @return bool|string
     */
    public function getClass()
    {
        $str = get_class($this);
        $name = substr(strrchr($str,'\\'),1);
        return $name;
    }


    /**
     * Validação de entradas de formulário
     * @return bool
     */
/*    private function validate()
    {
        $data = $this->toArray();
        if(!empty($this->rules))
        {
            $this->searchModelInPostData(true);  #$_POST[$this->getClass()]['Uuid'] = $this->Id;
            $v = Validator::make($data, $this->rules, $this->ruleMessages, $this->getClass());
            return $v;
        }
        return true;
    }*/


    /**
     * Retorna todos os registros de um modelo
     * @param $model
     * @return mixed
     */
    protected function hasAll($model)
    {
        return forward_static_call("{$model}::all");
    }


    /**
     * GUID para chave primária - Utilizar biblioteca do laravel ou função manual
     * @link https://github.com/webpatser/laravel-uuid
     * @return Uuid
     */
    private function getUuid()
    {
        $guid = Uuid::generate(); #guid()
        return strtoupper($guid);
    }


    /**
     * Inicialização do Model
     */
    protected static function boot()
    {
        parent::boot(); // TODO: Change the autogenerated stub

        /**
         * Definindo eventos
         */
        foreach (self::EVENTS as $Event => $Method)
        {
            self::$Event(function($Model) use ($Method){
                if(method_exists($Model,$Method))
                    return $Model->$Method();
            });
        }
    }


    /**
     * Aplicando validação nos campos do banco de dados que não aceitam nulos
     */
    private function applyValidate()
    {
        foreach ($this->columns as $Name => $Column)
        {
            if(in_array($Name, array_keys($this->rules)) || in_array($Name,['Id','DataCriacao']) || ($Column['nullable']=='YES') || $Column['type_raw']=='bit')
                continue;

            $this->rules[$Name] = 'required';
            $this->ruleMessages["{$Name}.required"] = 'O campo <strong>'.$Name.'</strong> não aceita nulo. Por favor informe-o.';
        }
    }


    /**
     * Faz uma avaliação do campo do banco para ver qual melhor elemento de formulário lhe será atribuído
     * @param string $Name
     * @param array $Info
     * @return string
     */
    private function getFieldDbType(string $Name, array $Info) : string
    {
        $Type = 'text';

        if(preg_match('/(?<model>.+)Id$/', $Name, $match) && $Info['type']=='uniqueidentifier')
            $Type = 'select';

        if($Info['type']=='bit')
            $Type = 'radio';

        if($Info['size'] > 200)
            $Type = 'textarea';

        return $Type;
    }

    public function __set($key, $value)
    {
        $this->setProperty($key, $value);
        parent::__set($key, $value); // TODO: Change the autogenerated stub
    }
}
<?php namespace Core;

use Core\Classes\Session;
use Core\Services\Partial;
use Core\Traits\Validator;
use Doctrine\Common\Inflector\Inflector;
use Illuminate\Database\Capsule\Manager;
use Illuminate\Support\Carbon;
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

    #protected $dates = ['DataCriacao','DataAtualizacao'];

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

    /**
     * @var Partial
     */
    protected $partial;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->Conn = \Illuminate\Database\Capsule\Manager::connection($this->getConnectionName())->getPdo();
        #$this->Schema = new \Illuminate\Database\Schema\Builder($this->getConnection());

        $this->infoTable();
        $this->applyValidate();

        /**
         * Processa blocos de conteúdo independentes
         */
        $this->partial = new Partial;
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
     * Preenche o objeto setando suas propriedades
     * com os valores do formulário submetido
     * @return array
     */
    public function populate(array $data)
    {
        if(!empty($data))
        {
            /**
             * Disparando evento onBeforePopulate
             */
            $this->onBeforePopulate();

            /**
             * Iterando os elementos e atribuindo ao modelo
             */
            foreach ($data as $prop => $val)
                $this->$prop = $val;

            /**
             * Disparando evento onAfterPopulate
             */
            $this->onAfterPopulate();
        }
    }


    /**
     * Salva no modelo atual e seus relacionamentos
     * @param array $Relations
     * @return bool|string
     */
    public function push(array $Relations = [])
    {
        $Model = $this->getClass();
        $Data = $Relations ? $Relations : post($Model);

        $Ns   = 'wSGI\\Modules\\'.__APP_MODULE.'\\Models\\';
        $Push = function(Model &$Model, array $Relations) use (&$Push, $Ns){
            foreach ($Relations as $Prop => $Val)
            {
                if(!is_array($Val))
                {
                    $Model->$Prop = $Val;
                }
                else
                {
                    $Class = $Ns.$Prop;
                    $Fk = $Prop.'Id';
                    $Obj = ($Id=$Model->$Fk) ? $Class::find($Id) : new $Class;
                    $Push($Obj, $Val);
                    $Res = $Obj->save();
                    $Model->$Fk = $Id ? $Id : $Res;
                }
            }
        };

        try
        {
            Manager::connection()->beginTransaction();
            $Push($this, $Data);
            $result = $this->save();
            Manager::connection()->commit();
            return $result;
        }
        catch (\Exception $e){
            Manager::connection()->rollBack();
            throw new \Exception($e->getMessage());
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


    /**
     * Evento realizado antes do ojbeto ser completamente populado
     */
    protected function onBeforePopulate(){}

    /**
     * Evento realizado logo após o objeto ter sido populado
     */
    protected function onAfterPopulate(){}


    /**
     * * Inclui um bloco de conteúdo
     *
     * @param $name
     * @param null $data
     * @return string
     */
    final public function partial($name, $data = null)
    {
        return $this->partial->get($name, $this, $data);
    }

    /**
     *
     * @param string $key
     * @param mixed $value
     */
    public function __set($key, $value)
    {
        $model = $this->getClass();
        $val = ($v=$value) ? $v : NULL;
        Session::set("val.{$model}.{$key}", $val);
        $this->setAttribute($key, $val);
    }
}
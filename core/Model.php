<?php namespace Core;

use Core\Classes\Message;
use Core\Classes\Session;
use Core\Services\Partial;
use Core\Traits\Validator;
use Doctrine\Common\Inflector\Inflector;
use Illuminate\Support\Carbon;
use Webpatser\Uuid\Uuid;
use wSGI\Modules\Auth\Util\Password;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use Validator;

    public $formname;

    protected $references = [];

    public $columns = [];

    public $attrmodal = [];

    public $modalColumns = [];

    protected $guarded = ['*'];

    public $fillable = ['*'];

    public $incrementing = false;

    public $primaryKey = 'Id';

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

    protected $connection = 'default';

    /**
     * @var DB $DB Eloquent Capsule
     */
    public static $DB;

    protected $Conn;


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::$DB = new DB;
        $this->Conn = DB::connection($this->getConnectionName())->getPdo();
        $this->infoTable();
        #$this->applyValidate();
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
     * Preenche o objeto setando suas propriedades
     * com os valores do formulário submetido
     * @return bool
     */
    public function populate(array $data = []) : bool
    {
        $data = !empty($data) ? $data : $this->findData(post(), ($name=$this->formname) ? $name : $this->getClass());
        $data = array_filter($data, 'is_scalar');
        if(!empty($data))
        {
            /**
             * Disparando evento onBeforePopulate
             */
            if(method_exists($this, 'onBeforePopulate')) $this->onBeforePopulate($data);

            /**
             * Iterando os elementos e atribuindo ao modelo
             */
            foreach ($data as $prop => $val)
            {
                $model = $this->getClass();
                Session::set("val.{$model}.{$prop}", $val);
                $this->$prop = $val;
            }

            /**
             * Disparando evento onAfterPopulate
             */
            if(method_exists($this, 'onAfterPopulate')) $this->onAfterPopulate($data);
        }

        return true;
    }


    /**
     * Pesquisa pelos dados do modelo na variável $Request ou global POST
     * @param array $Request
     * @return array
     */
    public function findData(array $data, $model = null, $result = []) : array
    {
        $model  = $model ? $model : $this->getClass();
        if(sizeof($data))
        {
            foreach ($data as $name => &$val)
            {
                if(is_array($val))
                {
                    if($name == $model)
                        return $val;

                    $result = $this->findData($val, $model, $result);
                }
            }
        }
        return $result;
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
     * @param array $options
     * @return bool|string
     * @throws \Exception
     */
    public function save(array $options = [])
    {
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


    public function dump(array $relations, &$model = null)
    {
        if(!$model){
            if($data = $this->findData($relations)){
                $relations = $data;
            }
            $model = $this;
        }

        if($model->validate($relations))
        {
            if($model->populate($relations))
            {
                foreach ($relations as $name => $value)
                {
                    if(is_array($value))
                    {
                        if(key_exists($name, $model->references))
                        {
                            $refer = $model->references[$name];
                            $class = array_shift($refer);
                            $dbofk = ($k=array_shift($refer)) ? $k : $name.'Id';
                            $field = ($f=array_shift($refer)) ? $f : $this->primaryKey;
                            $obj   = ($fk=$model->{$dbofk}) ? $class::where($field, $fk)->first() : new $class;
                            $obj->formname = $name;
                            $refer = $this->dump($value, $obj);
                            $model->$dbofk = $fk ? $fk : $refer;
                        }
                        else
                            $this->dump($value);
                    }
                }
                return $model->save();
            }
        }
    }

    public function saveAll(array $relations = [])
    {
        $data = !empty($relations) ? $relations : post();
        $conn = DB::connection($this->getConnectionName());
        try
        {
            $conn->beginTransaction();
            $result = $this->dump($data);
            $conn->commit();
            return $result;
        }
        catch (\Exception $e)
        {
            Message::danger($e->getMessage());
            $conn->rollBack();
        }
    }

    /**
     * Aplicando validação nos campos que não aceitam nulos
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
                $this->attrmodal[$Name] = $obj->COLUMN_DEFAULT;
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
     * Retorna o hash de uma string
     * @param string $password
     * @return string
     */
    public function hash(string $password) : string
    {
        return Password::hash($password);
    }

    /**
     * Confere se uma string bate com o seu hash
     * @param string $pass
     * @param string $hash
     * @return bool
     */
    public function verify(string $pass, string $hash) : bool
    {
        return Password::verify($pass, $hash);
    }


    /**
     * Salva no modelo atual e seus relacionamentos elencadas apartir de um array
     * @param array $Relations
     * @return bool|string
     */
    /*public function saveAll(array $Relations = [])
    {
        $Model = $this->getClass();
        $Data = $Relations ? $Relations : post($Model);
        $Conn = $this->getConnectionName();
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
            DB::connection($Conn)->beginTransaction();
            $Push($this, $Data);
            $result = $this->save();
            DB::connection($Conn)->commit();
            return $result;
        }
        catch (\Exception $e){
            DB::connection($Conn)->rollBack();
            throw new \Exception($e->getMessage());
        }
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
}
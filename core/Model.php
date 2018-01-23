<?php namespace Core;

use Core\Classes\Session;
use Core\Services\Partial;
use Core\Traits\Validator;
use Doctrine\Common\Inflector\Inflector;
use Illuminate\Support\Carbon;
use Webpatser\Uuid\Uuid;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use Validator;

    protected $references = [];

    public $columns = [];

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


    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);
        self::$DB = new DB;
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
            {
                if(is_scalar($val))
                {
                    $model = $this->getClass();
                    Session::set("val.{$model}.{$prop}", $val);
                    $this->$prop = $val;
                }
            }

            /**
             * Disparando evento onAfterPopulate
             */
            $this->onAfterPopulate();
        }

        return true;
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
     * Pesquisa pelos dados do modelo na variável $Request ou global POST
     * @param array $Request
     * @return array
     */
    public function findData(array $data, $model = null, $result = []) : array
    {
        $model  = $model ? $model : $this->getClass();
        if(sizeof($data))
        {
            foreach ($data as $name => $val)
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



    public function dump(array $relations = [], $fmodel = null)
    {
        if($data = $this->findData($relations, $fmodel))
            if($this->populate($data))

        foreach ($this->references as $name => $reference)
        {
            $class = array_shift($reference);
            $fk    = array_shift($reference);
            $field = ($f=array_shift($reference)) ? $f : $this->primaryKey;

            $model = ($key=$this->{$fk}) ? $class::where($field, $key)->first() : new $class;
            $refer = $model->dump($data, $name);
            $this->{$fk} = $key ? $key : $refer;
        }

        return $this->save();
    }


    public function push(array $relations = [])
    {
        $conn = $this->getConnectionName();
        try
        {
            DB::connection($conn)->beginTransaction();
            $result = $this->dump($relations);
            DB::connection($conn)->commit();
            return $result;
        }
        catch (\Exception $e)
        {
            echo $e->getMessage();
            DB::connection($conn)->rollBack();
        }
    }

    public function dump2(array $relations = [], $obj = null)
    {
        $relations = !empty($relations) ? $relations : post();
        $obj = $obj ? $obj : new \stdClass;

        foreach ($relations as $name => $value)
        {
            if(is_array($value))
            {
                if(key_exists($name, $this->references))
                {
                    $refer = $this->references[$name];
                    $class = array_shift($refer);
                    $dbofk = ($k=array_shift($refer)) ? $k : $name.'Id';
                    $field = ($f=array_shift($refer)) ? $f : $this->primaryKey;
                    $model = ($fk=$this->{$dbofk}) ? $class::where($field, $fk)->first() : new $class;

                    /*if($model->populate($value))
                    {
                        $res = $model->save();
                        $this->{$dbofk} = $fk ? $fk : $res;
                    }*/

                }
                $this->dump2($value, $obj);
            }
        }

    }


    /**
     * Salva no modelo atual e seus relacionamentos elencadas apartir de um array
     * @param array $Relations
     * @return bool|string
     */
    public function saveAll(array $Relations = [])
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
    }

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
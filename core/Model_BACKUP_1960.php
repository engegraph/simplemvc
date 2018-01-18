<?php namespace Core;

use Carbon\Carbon;
use Core\Classes\Session;
use Core\Services\Partial;
use Core\Traits\Validator;
use Doctrine\Common\Inflector\Inflector;
<<<<<<< HEAD
use Illuminate\Database\Capsule\Manager;
=======
use Illuminate\Support\Carbon;
>>>>>>> 1474adc2e6c27b7d299ac9dc6fefb6cb44e0bde9
use Webpatser\Uuid\Uuid;

class Model extends \Illuminate\Database\Eloquent\Model
{
    use Validator;

    public $columns = [];

    public $modalColumns = [];

    protected $guarded = ['*'];

    public $fillable = ['*'];

    public $incrementing = false;

<<<<<<< HEAD
    public $timestamps = false;

    protected $dateFormat = 'Y-m-d H:i:s';

    protected $primaryKey = 'Id';

    protected $keyType = 'string';
=======
    public $primaryKey = 'Id';
>>>>>>> 1474adc2e6c27b7d299ac9dc6fefb6cb44e0bde9

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
        'deleted'   => 'onAfterDelete'
    ];

    /**
     * @var DB $DB Eloquent Capsule
     */
    public static $DB;


    public function __construct(array $attributes = [])
    {
<<<<<<< HEAD
        self::$Conn = \Illuminate\Database\Capsule\Manager::connection($this->getConnectionName())->getPdo();
        #$this->Schema = new \Illuminate\Database\Schema\Builder($this->getConnection());

        $this->infoTable();
        $this->applyValidate();

        /**
         * Processa blocos de conteúdo independentes
         */
        $this->partial = new Partial;

        parent::__construct($attributes);
=======
        parent::__construct($attributes);
        self::$DB = new DB;
>>>>>>> 1474adc2e6c27b7d299ac9dc6fefb6cb44e0bde9
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
     * Salva no modelo atual e seus relacionamentos
     * @param array $Relations
     * @return bool|string
     */
    public function push(array $Relations = [])
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
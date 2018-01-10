<?php namespace Core;

use Illuminate\Database\Capsule\Manager;
use Illuminate\Events\Dispatcher;

class Orm
{
    private $Capsule;

    public function __construct()
    {
        try
        {
            $database = require __DIR__.'./../config/database.php';
            if(!isset($database['connections']))
                throw new \Exception('Configurações de banco de dados não encontradas');

            $Capsule = new Manager;
            foreach ($database['connections'] as $Name => $Connection)
                $Capsule->addConnection($Connection, $Name);

            $this->Capsule = $Capsule;
        }
        catch (\Exception $e)
        {
            echo $e->getMessage();
        }
    }

    public function run()
    {
        $EventsDispatcher = new Dispatcher(new \Illuminate\Container\Container());
        $this->Capsule->setEventDispatcher($EventsDispatcher);
        $this->Capsule->setAsGlobal();
        $this->Capsule->bootEloquent();
    }
}
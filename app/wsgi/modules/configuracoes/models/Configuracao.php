<?php namespace wSGI\Modules\Configuracoes\Models;

use Core\Model;
use App\Modules\wSGI\Models\BoletoBanco;

class Configuracao extends Model
{
    protected $table = 'Configuracoes';

    public function ModulosConfiguracao()   {
        $data = $this->rawQuery("select distinct Modulo from Configuracoes order by Modulo");
        //return ['pessoas'=>$data];
        return $data;
    }

    public function Bancos()   {

        return $this->hasAll('App\Modules\wSGI\Models\BoletoBanco');
    }


}
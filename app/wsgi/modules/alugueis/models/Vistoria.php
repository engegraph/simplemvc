<?php namespace wSGI\Modules\Alugueis\Models;

use Core\Model;

class Vistoria extends Model
{
    protected $table = 'ImovelControleVistoria';

    public function TipoVistoria() {

        return $this->hasOne('wSGI\Modules\Controles\Models\TipoVistoria','Id', 'TipoVistoriaId');
    }

    public function Imovel() {

        return $this->hasOne('wSGI\Modules\Imoveis\Models\Imovel','Id', 'ImovelId');
    }

    public function TipoVistorias() {

        return $this->hasAll('wSGI\Modules\Controles\Models\TipoVistoria');
    }
}
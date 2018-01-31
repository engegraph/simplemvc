<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class ImovelCaracteristicas extends Model
{
    public $table = 'ImovelCaracteristicas';

    public function Caracteristica()
    {
        return $this->hasOne('wSGI\Modules\Controles\Models\CaracteristicasImovel','Id', 'CaracteristicaImovelId');
    }
}
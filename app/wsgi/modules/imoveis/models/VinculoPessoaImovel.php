<?php namespace wSGI\Modules\Imoveis\Models;

use Core\Model;

class VinculoPessoaImovel extends Model
{
    public $table = 'VinculoPessoaImovel';

    public function Pessoa()
    {
        return $this->hasOne('wSGI\Modules\Pessoas\Models\Pessoa','Id', 'PessoaId');
    }

    public function Imovel()
    {
        return $this->hasOne('wSGI\Modules\Imoveis\Models\Imovel','Id', 'ImovelId');
    }

    public function TipoVinculo()
    {
        return $this->hasOne('wSGI\Modules\Controles\Models\TipoVinculo','Id', 'TipoVinculoId');
    }
}
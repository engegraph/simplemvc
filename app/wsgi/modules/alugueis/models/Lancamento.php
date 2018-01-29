<?php namespace wSGI\Modules\Alugueis\Models;

use Core\Model;

class Lancamento extends Model
{
    public $table = 'ImovelLancamentos';

    public function TipoLancamento()
    {
        return $this->hasOne('App\Modules\wSGI\Models\TipoLancamentoFinanceiro','Id', 'TipoLancamentoId');
    }

    public function TipoLancamentos()
    {
        return $this->hasAll('App\Modules\wSGI\Models\TipoLancamentoFinanceiro');
    }

}
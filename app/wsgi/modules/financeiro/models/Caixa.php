<?php namespace wSGI\Modules\Financeiro\Models;

use Core\Model;

class Caixa extends Model
{
    public $table = 'caixa';

    public function CaixaItens()
    {
        return $this->hasMany('wSGI\Modules\Caixa\Models\Item','CaixaId')->orderBy('DataHoraLancamento', 'asc');
    }
}
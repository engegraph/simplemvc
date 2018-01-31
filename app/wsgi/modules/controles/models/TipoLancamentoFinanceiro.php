<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class TipoLancamentoFinanceiro extends Model
{
    public $table = 'TbTiposLancamentoFinanceiro';

    public $modalColumns = [
        'Nome' => ['label'=>'Nome'],
        'DataCriacao' => ['label'=>'Criação', 'format'=>'d/m/Y', 'form'=>false],
        'DataAtualizacao' => ['label'=>'Atualização'],
    ];
}
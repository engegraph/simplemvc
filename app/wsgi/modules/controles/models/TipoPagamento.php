<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class TipoPagamento extends Model
{
    public $table = 'TbTiposPagamento';

    public $modalColumns = [
        'Descricao' => ['label'=>'Descrição'],
        'DataCriacao' => ['label'=>'Criação', 'format'=>'d/m/Y', 'form'=>false],
        'DataAtualizacao' => ['label'=>'Atualização'],
    ];
}
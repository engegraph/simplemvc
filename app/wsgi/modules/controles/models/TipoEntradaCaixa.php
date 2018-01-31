<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class TipoEntradaCaixa extends Model
{
    public $table = 'TbTiposEntradaCaixa';

    public $modalColumns = [
        'Nome' => ['label'=>'Nome'],
        'Descricao' => ['label'=>'Descrição'],
        'ImobiliariaFilialId' => ['label'=>'TipoContrato', 'form'=> false, 'list'=>false],
        'DataCriacao' => ['label'=>'Criação', 'format'=>'d/m/Y', 'form'=>false],
        'DataAtualizacao' => ['label'=>'Atualização'],
    ];
}
<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class ContratoCategoria extends Model
{
    public $table = 'TbContratosCategoria';

    public $modalColumns = [
        'Nome' => ['label'=>'Nome'],
        'Descricao' => ['label'=>'Descrição'],
        'TipoContrato' => ['label'=>'TipoContrato', 'form'=> false, 'list'=>false],
        'ImobiliariaFilialId' => ['label'=>'TipoContrato', 'form'=> false, 'list'=>false],
        'DataCriacao' => ['label'=>'Criação', 'format'=>'d/m/Y', 'form'=>false],
        'DataAtualizacao' => ['label'=>'Atualização'],
    ];
}
<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class EstadoCivil extends Model
{
    public $table = 'TbEstadosCivil';

    public $modalColumns = [
        'Nome' => ['label'=>'Nome'],
        'Descricao' => ['label'=>'Descrição'],
        'DataCriacao' => ['label'=>'Criação', 'format'=>'d/m/Y', 'form'=>false],
        'DataAtualizacao' => ['label'=>'Atualização'],
    ];
}
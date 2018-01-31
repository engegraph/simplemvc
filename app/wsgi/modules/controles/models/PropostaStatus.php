<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class PropostaStatus extends Model
{
    public $table = 'TbPropostasStatus';

    public $modalColumns = [
        'Nome' => ['label'=>'Nome'],
        'DataCriacao' => ['label'=>'Criação', 'format'=>'d/m/Y', 'form'=>false],
        'DataAtualizacao' => ['label'=>'Atualização'],
    ];
}
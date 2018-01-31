<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class TipoParteProposta extends Model
{
    public $table = 'TbTiposParteProposta';

    public $modalColumns = [
        'Nome' => ['label'=>'Nome'],
        'DataCriacao' => ['label'=>'Criação', 'format'=>'d/m/Y', 'form'=>false],
        'DataAtualizacao' => ['label'=>'Atualização'],
    ];
}
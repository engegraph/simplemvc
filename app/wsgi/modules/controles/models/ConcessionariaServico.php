<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class ConcessionariaServico extends Model
{
    public $table = 'TbConcessionariasServico';

    public $modalColumns = [
        'Nome' => ['label'=>'Nome'],
        'Servico' => ['label'=>'Serviço'],
        'Url' => ['label'=>'URL', 'form'=>true, 'list'=>true],
        'Parametro' => ['label'=>'Parametro', 'form'=>true, 'list'=>true],
        'Cidade' => ['label'=>'Cidade', 'form'=>true, 'list'=>true],
        'UF' => ['label'=>'UF', 'form'=>true, 'list'=>true],
        'DataCriacao' => ['label'=>'Criação', 'format'=>'d/m/Y', 'form'=>false],
        'DataAtualizacao' => ['label'=>'Atualização'],
    ];


}
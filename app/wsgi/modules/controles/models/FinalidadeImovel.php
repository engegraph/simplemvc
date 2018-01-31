<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class FinalidadeImovel extends Model
{
    public $table = 'TbFinalidadesImovel';

    public $modalColumns = [
        'Nome' => ['label'=>'Nome'],
        'Descricao' => ['label'=>'Descrição'],
        'ImobiliariaFilialId' => ['label'=>'TipoContrato', 'form'=> false, 'list'=>false],
        'DataCriacao' => ['label'=>'Criação', 'format'=>'d/m/Y', 'form'=>false],
        'DataAtualizacao' => ['label'=>'Atualização'],
    ];
}
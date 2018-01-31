<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class Profissao extends Model
{
    public $table = 'TbProfissoes';

    public $modalColumns = [
        'Nome'                => ['label'=>'Nome'],
        'Descricao'           => ['label'=>'Descrição'],
        'ImobiliariaFilialId' => ['label'=>'Imobiliaria Filial','reference'=>['wSGI\Modules\Controles\Models\ImobiliariaFilial'], 'list'=> false, 'form'=> false],
    ];
}
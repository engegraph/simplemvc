<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class AnexoCategoria extends Model
{
    public $table = 'TbAnexosCategoria';

    public $modalColumns = [
        'Nome' => ['label'=>'Nome'],
        'Descricao' => ['label'=>'Descrição'],
        'IndiceOrdenacao' => ['label'=>'Ordenação', 'form'=>true, 'list'=>true],
        'ImobiliariaFilialId' => ['label'=>'Filial Imobiliaria', 'form'=> false, 'list'=>false],
        'DataCriacao' => ['label'=>'Criação', 'format'=>'d/m/Y', 'form'=>false],
        'DataAtualizacao' => ['label'=>'Atualização'],
    ];
}
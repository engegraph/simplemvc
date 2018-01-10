<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class Estado extends Model
{
    public $table = 'TbEstados';

    public $modalColumns = [
        'Nome' => ['label'=>'Nome'],
        'Uf' => ['label'=>'UF'],
        'DataCriacao' => ['label'=>'Criação', 'format'=>'d/m/Y', 'form'=>false],
        'DataAtualizacao' => ['label'=>'Atualização'],
    ];

    public function Cidades()
    {
        return $this->hasMany('wSGI\Modules\Controles\Models\Cidade','EstadoId');
    }
}
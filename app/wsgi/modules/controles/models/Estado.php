<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class Estado extends Model
{
    public $table = 'TbEstados';

    protected $rules = [
        'Nome' => 'required',
        'Uf'   => 'required|size:2',
    ];

    protected $ruleMessages = [
        'Nome.required' => 'Informe o nome',
        'Nome.wsgi' => 'O nome é wsgi',
        'Uf.required' => 'Uf nome é necessário',
        'Uf.size' => 'Uf deve ter 2 dígitos',
    ];

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
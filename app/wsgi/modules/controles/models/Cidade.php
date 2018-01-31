<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class Cidade extends Model
{
    protected $references = [
        'Estado' => ['wSGI\Modules\Controles\Models\Estado']
    ];

    public $table = 'TbCidades';

    public $modalColumns = [
        'Capital' =>  ['type'=>'radio', 'options'=>['Não','Sim']],
        'Nome' =>  ['cssClass'=>'nome-class'],
        'EstadoId' => ['label'=>'Estado', 'reference'=>['wSGI\Modules\Controles\Models\Estado']],
        'DataCriacao' => ['label'=>'Criação', 'format'=>'d/m/Y', 'form'=>false],
        'DataAtualizacao' => ['label'=>'Atualização', 'callback'=>'formatData'],
    ];

    public function Estado()
    {
        return $this->hasOne('wSGI\Modules\Controles\Models\Estado', 'Id', 'EstadoId');
    }

    public function Endereco()
    {
        return $this->hasOne('wSGI\Modules\Controles\Models\Endereco','Id','EnderecoId');
    }

    public function onBeforeSave()
    {
        $this->Capital = post('Cidade.Capital') ? 1 : 0;
    }
}
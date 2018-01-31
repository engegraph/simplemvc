<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class TipoContaGrupo extends Model
{
    public $table = 'TbTiposContaGrupo';

    public $modalColumns = [
        'Nome' => ['label'=>'Nome'],
        'Descricao' => ['label'=>'Descrição'],
        'Ativo' =>  ['type'=>'radio', 'options'=>['Não','Sim'],'label'=>'Ativo', 'form'=> true, 'list'=>true],
        'DataCriacao' => ['label'=>'Criação', 'format'=>'d/m/Y', 'form'=>false],
        'DataAtualizacao' => ['label'=>'Atualização'],
    ];

    public function onBeforeSave()
    {
        $this->Ativo = post('TipoContaGrupo.Ativo') ? 1 : 0;
    }

}
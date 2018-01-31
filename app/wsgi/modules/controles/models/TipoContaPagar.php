<?php namespace wSGI\Modules\Controles\Models;

use Core\Model;

class TipoContaPagar extends Model
{
    public $table = 'TbTiposContaPagar';

    public $modalColumns = [
        'Nome' => ['label'=>'Nome'],
        'Descricao' => ['label'=>'Descrição'],
        'ImobiliariaFilialId' => ['label'=>'TipoContrato', 'form'=> false, 'list'=>false],
        'UsuarioCadastroId' => ['label'=>'Usuário', 'form'=> false, 'list'=>false],
        'Ativo' =>  ['type'=>'radio', 'options'=>['Não','Sim'],'label'=>'Ativo', 'form'=> true, 'list'=>true],
        'ContasGrupoId' => ['label'=>'Grupo', 'reference'=>['App\Modules\wSGI\Models\TipoContaGrupo'], 'form'=>true],
        'DataCriacao' => ['label'=>'Criação', 'format'=>'d/m/Y', 'form'=>false],
        'DataAtualizacao' => ['label'=>'Atualização'],
    ];

    public function onBeforeSave()
    {
        $this->Ativo = post('TipoContaPagar.Ativo') ? 1 : 0;
    }
}
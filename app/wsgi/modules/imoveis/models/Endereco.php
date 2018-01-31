<?php namespace wSGI\Modules\Imoveis\Models;

use Core\Model;

class Endereco extends Model
{
    protected $references = [
        'Cidade' => ['wSGI\Modules\Controles\Models\Cidade'],
    ];

    protected $rules = [
        'Cep' => 'required',
    ];

    protected $ruleMessages = [
        'Cep.required' => 'Informe o Cep'
    ];

    public function Cidade()
    {
        return $this->hasOne('wSGI\Modules\Controles\Models\Cidade', 'Id', 'CidadeId');
    }

    public function Cidades()
    {
        return $this->hasAll('wSGI\Modules\Controles\Models\Cidade');
    }

    public function Empreendimento()
    {
        return $this->hasMany('wSGI\Modules\Models\Empreendimento','Id', 'EnderecoId');
    }

}
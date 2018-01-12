<?php namespace wSGI\Modules\Pessoas\Models;

use Core\Model;

class Pessoa extends Model
{
    protected $rules = [
        'Nome' => 'required',
        'NomeFantasia' => 'required',
        'TipoPessoa' => 'required',
        'CpfCnpj' => 'required|unique:pessoa',
    ];

    protected $ruleMessages = [
        'Nome.required' => 'Informe seu nome',
        'Nome.enum' => 'Nome inválido',
        'NomeFantasia.required' => 'Nome fantasia é necessário',
        'TipoPessoa.required' => 'Informe o tipo de pessoa',
        'CpfCnpj.required' => 'Informe seu cpf',
        'CpfCnpj.unique' => 'Cpf ou Cnpj informado já existe',
    ];

    public function profissoes()
    {
        return $this->hasAll('wSGI\Modules\Controles\Models\Profissao');
    }

    public function parents()
    {
        return self::all();
    }

    public function nacionalidades()
    {
        return $this->hasAll('wSGI\Modules\Controles\Models\Nacionalidade');
    }

    public function naturalidades()
    {
        return $this->hasAll('wSGI\Modules\Controles\Models\Naturalidade');
    }

    public function estadoscivil()
    {
        return $this->hasAll('wSGI\Modules\Controles\Models\EstadoCivil');
    }

    public function enderecos()
    {
        return $this->hasAll('wSGI\Modules\Controles\Models\Endereco');
    }

    public function Endereco()
    {
        return $this->hasOne('wSGI\Modules\Controles\Models\Endereco','Id','EnderecoId');
    }

    public function Cidades()
    {
        return $this->hasAll('wSGI\Modules\Controles\Models\Cidade');
    }

    public function Pai()
    {
        return $this->hasOne('wSGI\Modules\Controles\Models\Pai','Id', 'PaiId');
    }

    public function Mae()
    {
        return $this->hasOne('wSGI\Modules\Controles\Models\Mae','Id', 'MaeId');
    }

    public function Conjuge()
    {
        return $this->hasOne('wSGI\Modules\Controles\Models\Conjuge','Id', 'ConjugeId');
    }
}
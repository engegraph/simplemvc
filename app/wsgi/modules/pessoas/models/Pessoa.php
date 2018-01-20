<?php namespace wSGI\Modules\Pessoas\Models;

use Core\Model;

class Pessoa extends Model
{
    protected $references = [
        'Endereco'      => ['wSGI\Modules\Controles\Models\Endereco', 'EnderecoId'],
        /*'Conjuge'       => ['wSGI\Modules\Pessoas\Models\Pessoa', 'ConjugeId'],
        'Mae'           => ['wSGI\Modules\Pessoas\Models\Pessoa', 'MaeId'],
        'Pai'           => ['wSGI\Modules\Pessoas\Models\Pessoa', 'PaiId'],
        'EstadoCivil'   => ['wSGI\Modules\Controles\Models\EstadoCivil', 'EstadoCivilId'],
        'Nacionalidade' => ['wSGI\Modules\Controles\Models\Nacionalidade', 'NacionalidadeId'],
        'Naturalidade'  => ['wSGI\Modules\Controles\Models\Naturalidade', 'NaturalidadeId'],
        'Profissao'     => ['wSGI\Modules\Controles\Models\Profissao', 'ProfissaoId'],*/
    ];

    protected $rules = [
        'Nome' => 'required',
        'NomeFantasia' => 'required',
        'TipoPessoa' => 'required',
        'CpfCnpj' => 'required',
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

    public function onBeforeSave()
    {
        $this->DataNascimento = date_conv($this->DataNascimento, 'en');
    }
}
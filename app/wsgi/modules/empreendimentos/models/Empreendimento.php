<?php namespace wSGI\Modules\Empreendimentos\Models;

use Core\Model;

class Empreendimento extends Model
{
    protected $rules = [
        'Nome' => 'required',
    ];

    protected $ruleMessages = [
        'Nome.required' => 'Informe o nome do Empreendimento'
    ];

    public function Endereco()
    {
        return $this->hasOne('App\Modules\wSGI\Models\Endereco','Id','EnderecoId');
    }

    public function Cidades()
    {
        return $this->hasAll('App\Modules\wSGI\Models\Cidade');
    }

    public function TipoEmpreendimento()
    {
        return $this->hasOne('App\Modules\wSGI\Models\TipoEmpreendimento','Id', 'TipoEmpreendimentoId');
    }

    public function TipoEmpreendimentos()
    {
        return $this->hasAll('App\Modules\wSGI\Models\TipoEmpreendimento');
    }

    public function TipoImovel()
    {
        return $this->hasOne('App\Modules\wSGI\Models\TipoImovel','Id', 'TipoImovelId');
    }

    public function TipoImoveis()
    {
        return $this->hasAll('App\Modules\wSGI\Models\TipoImovel');
    }

    public function FinalidadeImovel()
    {
        return $this->hasOne('App\Modules\wSGI\Models\FinalidadeImovel','Id', 'FinalidadeImovelId');
    }

    public function FinalidadeImoveis()
    {
        return $this->hasAll('App\Modules\wSGI\Models\FinalidadeImovel');
    }

    public function ImoveisRelacionados()
    {
        return $this->hasMany('App\Modules\wSGI\Models\Imovel','EmpreendimentoId', 'Id');
    }

    public function ImobiliariaFiliais()
    {
        return $this->hasAll('App\Modules\wSGI\Models\ImobiliariaFilial');
    }

    //Eventos
    public function onBeforeSave()
    {
        //Chaves estrangerias
        $this->CNPJ = RetiraCaracteres($this->CNPJ);

    }
}
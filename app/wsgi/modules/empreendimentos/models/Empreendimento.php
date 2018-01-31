<?php namespace wSGI\Modules\Empreendimentos\Models;

use Core\Model;

class Empreendimento extends Model
{

    protected $references = [
        'Endereco' => ['wSGI\Modules\imoveis\Models\Endereco']
    ];

    public function Endereco()
    {
        return $this->hasOne('wSGI\Modules\imoveis\Models\Endereco','Id','EnderecoId');
    }

    public function Cidades()
    {
        return $this->hasAll('wSGI\Modules\controles\Models\Cidade');
    }

    public function TipoEmpreendimento()
    {
        return $this->hasOne('wSGI\Modules\controles\Models\TipoEmpreendimento','Id', 'TipoEmpreendimentoId');
    }

    public function TipoEmpreendimentos()
    {
        return $this->hasAll('wSGI\Modules\controles\Models\TipoEmpreendimento');
    }

    public function TipoImovel()
    {
        return $this->hasOne('wSGI\Modules\controles\Models\TipoImovel','Id', 'TipoImovelId');
    }

    public function TipoImoveis()
    {
        return $this->hasAll('wSGI\Modules\controles\Models\TipoImovel');
    }

    public function FinalidadeImovel()
    {
        return $this->hasOne('wSGI\Modules\controles\Models\FinalidadeImovel','Id', 'FinalidadeImovelId');
    }

    public function FinalidadeImoveis()
    {
        return $this->hasAll('wSGI\Modules\controles\Models\FinalidadeImovel');
    }

    public function ImoveisRelacionados()
    {
        return $this->hasMany('wSGI\Modules\imoveis\Models\Imovel','EmpreendimentoId', 'Id');
    }

    public function ImobiliariaFiliais()
    {
        return $this->hasAll('wSGI\Modules\Controles\Models\ImobiliariaFilial');
    }
    //Eventos
    public function onBeforeSave()
    {
        var_dump($this);
        die;

        //Chaves estrangerias
        $this->CNPJ   = RetiraCaracteres($this->CNPJ);
        $this->Status = 1;

        var_dump($this);
        die;


    }
}
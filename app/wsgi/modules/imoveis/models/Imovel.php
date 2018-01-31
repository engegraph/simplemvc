<?php namespace wSGI\Modules\Imoveis\Models;

use Core\Model;
use wSGI\Modules\Imoveis\Models\Endereco;
use wSGI\Modules\Controles\Models\TipoVinculo;

class Imovel extends Model
{
    public $table = 'Imoveis';


    //Relacionamentos
    public function Endereco()
    {
        return $this->hasOne('wSGI\Modules\Imoveis\Models\Endereco','Id', 'EnderecoId');
    }

    public function Empreendimento()
    {
        return $this->hasOne('wSGI\Modules\empreendimentos\Models\Empreendimento','Id', 'EmpreendimentoId');
    }

    public function Contratos() {
        return $this->hasMany('wSGI\Modules\Alugueis\Models\ImovelContrato','ImovelId', 'Id');
    }

    public function PessoasVinculadas() {
        return $this->hasMany('wSGI\Modules\Imoveis\Models\VinculoPessoaImovel','ImovelId', 'Id');
    }

    public function CaracteristicasAvancadas() {
        return $this->hasMany('wSGI\Modules\Imoveis\Models\ImovelCaracteristicas','ImovelId', 'Id');
    }

    public function PropostasVenda() {
        return $this->hasMany('wSGI\Modules\vendas\Models\Central','ImovelId', 'Id');
    }





    //Tabelas basicas
    public function Empreendimentos()
    {
        return $this->hasAll('wSGI\Modules\empreendimentos\Models\Empreendimento');
    }

    public function AnexoCategorias()
    {
        return $this->hasAll('wSGI\Modules\controles\Models\AnexoCategoria');
    }

    public function TiposImovel()
    {
        return $this->hasAll('wSGI\Modules\controles\models\TipoImovel');
    }

    public function TiposParteProposta()
    {
        return $this->hasAll('wSGI\Modules\controles\models\TipoParteProposta');
    }

    public function TiposPagamento()
    {
        return $this->hasAll('wSGI\Modules\controles\models\TipoPagamento');
//        return [];
    }

    public function TiposLaje()
    {
        return $this->hasAll('wSGI\Modules\controles\models\TipoLaje');
    }

    public function Cidades()
    {
        return $this->hasAll('wSGI\Modules\controles\models\Cidade');
    }

    public function Caracteristicas()
    {
        return $this->hasAll('wSGI\Modules\controles\models\CaracteristicasImovel');
    }

    public function TipoVinculos()
    {
        return TipoVinculo::where('Nome','<>','Locatário')->get();
    }

    public function FinalidadesImovel()
    {
        return $this->hasAll('wSGI\Modules\controles\models\FinalidadeImovel');
    }

    public function LigacoesTelefonicas()   {
        return $this->hasMany('wSGI\Modules\crm\Models\Recepcao', 'ImovelId', 'Id');
    }

    public function GraficoLigacoesMes() {
        return $this->rawQuery('SELECT MONTH(C.DATACRIACAO) AS MES, COUNT(C.ID) AS TOTAL FROM CRMS C GROUP BY MONTH(C.DATACRIACAO)');
    }


    //Eventos
    public function onBeforeSave()  {

        //Descobre a latitude e longitude
        if (($this->MapaLatitude == '') && ($this->MapaLongitude))  {
            $address = 'rua+g,setor+elisio+campos+goiania,goias,brasil';
            $address = str_replace(' ','+',$this->Endereco->Logradouro . ',' . $this->Endereco->Numero . ',' .$this->Endereco->Bairro . ',' . $this->Endereco->Cidade->Nome . ',' . $this->Endereco->Cidade->Estado->Nome . ',brasil');
            $geocode = file_get_contents('http://maps.google.com/maps/api/geocode/json?address='.$address.'&sensor=false');
            $output= json_decode($geocode);
            $lat = $output->results[0]->geometry->location->lat;
            $long = $output->results[0]->geometry->location->lng;


            $this->MapaLatitude       = $lat;
            $this->MapaLongitude      = $long;
            $this->MapaEndereco       = $address;
        }

        //Chaves estrangerias
        $this->EmpreendimentoId   = ($val = $this->EmpreendimentoId) ? $val : null;
        $this->TipoLajeId         = ($val = $this->TipoLajeId) ? $val : null;
        $this->CorretorId         = ($val = $this->CorretorId) ? $val : null;
        $this->ProcuradorId       = ($val = $this->ProcuradorId) ? $val : null;
        $this->FinalidadeImovelId = ($val = $this->FinalidadeImovelId) ? $val : null;
        $this->TipoImovelId       = ($val = $this->TipoImovelId) ? $val : null;

        //Campos inteiros
        $this->Quartos            = ($val = $this->Quartos) ? $val : null;
        $this->Suites             = ($val = $this->Suites) ? $val : null;
        $this->Salas              = ($val = $this->Salas) ? $val : null;
        $this->Banheiros          = ($val = $this->Banheiros) ? $val : null;
        $this->Garagem            = ($val = $this->Garagem) ? $val : null;
        $this->PosicaoVisualizacao            = ($val = $this->PosicaoVisualizacao) ? $val : null;
        $this->Versao             = ($val = $this->Versao) ? $val : null;
        $this->ReservadoCorretor  = ($val = $this->ReservadoCorretor) ? $val : null;
        $this->Torre              = ($val = $this->Torre) ? $val : null;
        $this->Andar              = ($val = $this->Andar) ? $val : null;

        //Campos bit
        $this->Muro               = ($val = $this->Muro) ? $val : 0;
        $this->CercaEletrica      = ($val = $this->CercaEletrica) ? $val : 0;
        $this->Ativo              = ($val = $this->Ativo) ? $val : 1;
        $this->Vendido            = ($val = $this->Vendido) ? $val : 0;
        $this->Averbado           = ($val = $this->Averbado) ? $val : 0;
        $this->DisponivelVenda    = ($val = $this->DisponivelVenda) ? $val : 0;
        $this->DisponivelAluguel  = ($val = $this->DisponivelAluguel) ? $val : 0;
        $this->DivulgacaoPlaca    = ($val = $this->DivulgacaoPlaca) ? $val : 0;
        $this->DivulgacaoJornal   = ($val = $this->DivulgacaoJornal) ? $val : 0;
        $this->DivulgacaoFacebook = ($val = $this->DivulgacaoFacebook) ? $val : 0;
        $this->DivulgacaoTwitter  = ($val = $this->DivulgacaoTwitter) ? $val : 0;
        $this->Reservado          = ($val = $this->Reservado) ? $val : 0;
        $this->ExigFiador         = ($val = $this->ExigFiador) ? $val : 0;
        $this->ExigDeposito       = ($val = $this->ExigDeposito) ? $val : 0;
        $this->ExigSeguroFianca   = ($val = $this->ExigSeguroFianca) ? $val : 0;
        $this->ExigExclusividade  = ($val = $this->ExigExclusividade) ? $val : 0;
        $this->ExigVisitaCorredor = ($val = $this->ExigVisitaCorredor) ? $val : 0;
        $this->ExigAltoPadrao     = ($val = $this->ExigAltoPadrao) ? $val : 0;
        $this->ExigTemporada      = ($val = $this->ExigTemporada) ? $val : 0;

        //Campos decimal
        $this->ValorVenda         = ($val = $this->ValorVenda) ? str_replace(',','.',$val) : null;
        $this->ValorAluguel       = ($val = $this->ValorAluguel) ? str_replace(',','.',$val) : null;
        $this->ValorAdministracao = ($val = $this->ValorAdministracao) ? str_replace(',','.',$val) : null;
        $this->ValorRepasse       = ($val = $this->ValorRepasse) ? str_replace(',','.',$val) : null;
        $this->ValorM2            = ($val = $this->ValorM2) ? str_replace(',','.',$val) : null;
        $this->PercentualAdministracao = ($val = $this->PercentualAdministracao) ? $val : null;
        $this->PercentualComissao = ($val = $this->PercentualComissao) ? $val : null;
        $this->AreaContruida      = ($val = $this->AreaContruida) ? $val : null;
        $this->AreaMedidasEsquerda = ($val = $this->AreaMedidasEsquerda) ? $val : null;
        $this->AreaMedidasDireita = ($val = $this->AreaMedidasDireita) ? $val : null;
        $this->AreaMedidasFrente  = ($val = $this->AreaMedidasFrente) ? $val : null;
        $this->AreaMedidasFundo   = ($val = $this->AreaMedidasFundo) ? $val : null;
        $this->ValorComissaoVenda = ($val = $this->ValorComissaoVenda) ? str_replace(',','.',$val) : null;
        $this->ValorRepasseVenda  = ($val = $this->ValorRepasseVenda) ? str_replace(',','.',$val) : null;
        $this->DivulgacaoJornalValor = ($val = $this->DivulgacaoJornalValor) ? $val : null;
        $this->ValorComissaoCaptador = ($val = $this->ValorComissaoCaptador) ? str_replace(',','.',$val) : null;
        $this->PercentualCaptacao = ($val = $this->PercentualCaptacao) ? $val : null;
        $this->ValorIptu          = ($val = $this->ValorIptu) ? str_replace(',','.',$val) : null;
        $this->ValorCondominio    = ($val = $this->ValorCondominio) ? str_replace(',','.',$val) : null;
        $this->ValorAvista        = ($val = $this->ValorAvista) ? str_replace(',','.',$val) : null;

        //Data 1900-01-01 00:00:00
        $this->DataProcuracao       = ($this->DataProcuracao == '1900-01-01 00:00:00' ? null : $this->DataProcuracao);
        $this->CartorioDataRegistro = ($this->CartorioDataRegistro == '1900-01-01 00:00:00' ? null : $this->CartorioDataRegistro);
        $this->DataPublicacao       = ($this->DataPublicacao == '1900-01-01 00:00:00' ? null : $this->DataPublicacao);
        $this->DataPublicacaoAtualizacao = ($this->DataPublicacaoAtualizacao == '1900-01-01 00:00:00' ? null : $this->DataPublicacaoAtualizacao);
        $this->DataPublicacaoFim    = ($this->DataPublicacaoFim == '1900-01-01 00:00:00' ? null : $this->DataPublicacaoFim);
        $this->DivulgacaoPlacaData  = ($this->DivulgacaoPlacaData == '1900-01-01 00:00:00' ? null : $this->DivulgacaoPlacaData);
        $this->DivulgacaoJornalData = ($this->DivulgacaoJornalData == '1900-01-01 00:00:00' ? null : $this->DivulgacaoJornalData);
        $this->ReservadoDataFim     = ($this->ReservadoDataFim == '1900-01-01 00:00:00' ? null : $this->ReservadoDataFim);

        //Outros tratamentos
        $this->Situacao        = ($val = $this->Situacao) ? $val : 'Livre';


    }

}
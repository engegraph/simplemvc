<?php namespace wSGI\Modules\Vendas\Controllers;

use App\Modules\wSGI\Models\Imovel;
use App\Modules\wSGI\Models\PropostaValorParcelas;
use Core\Controller;
use App\Modules\wSGI\Models\Proposta;
use App\Modules\wSGI\Models\PropostaValores;
use App\Modules\wSGI\Models\VinculoPessoaProposta;
use Webpatser\Uuid\Uuid;

class Central extends Controller
{

    public $pageTitle = 'Central de Vendas';


    public function onCarregaPessoas()   {
        $data = $this->model->rawQuery("select Id as id, Nome as value, concat('Nome: ', Nome, ' CPF: ', CpfCnpj) as label from pessoas where nome like '" . post('name_startsWith') . "%'");
        //return ['pessoas'=>$data];
        return $data;
    }

    public function onFormModalVendeImovel()   {
        $Data = post();

        $FormaPgEntrada      = null;
        $DataPossivelEntrada = null;
        $QntParcelasEntrada  = 1;
        $ValorEntrada        = 0;
        $QntParcelasFin      = 1;
        $ValorFin            = 0;

        $ProximaProposta = $this->model->rawQuery("SELECT NEXT VALUE FOR seq_numero_proposta ProximaProsta");

        foreach( $Data['CbTipoPagamentoCad'] as $key => $n ) {

            //Entrada
            if ($Data['CbTipoPagamentoCad'][$key] == '00000001-0000-0000-0000-000000000000')    {
                $FormaPgEntrada      = $Data['CbFormaPagamentoCad'][$key];
                $DataPossivelEntrada = $Data['EdDataVencimentoCad'][$key];
                $QntParcelasEntrada  = $Data['EdParcelasCad'][$key];
                $ValorEntrada        = $Data['EdValorCad'][$key];
            } else {
                $QntParcelasFin      = $Data['EdParcelasCad'][$key];
                $ValorFin            = $Data['EdValorCad'][$key];
            }
        }



//        $DataHoje = date('Ymd', strtotime(date('d/m/Y'). ' + 30 days'));
        $DataValidade = date("Ymd",strtotime("+30 day",strtotime("now")));

        $Proposta = new Proposta();
        $Proposta->Nome                     = 'Proposta ' . str_pad($ProximaProposta->ProximaProsta, 5, "0", STR_PAD_LEFT);
        $Proposta->FormaPagamentoEntrada    = $FormaPgEntrada;
        $Proposta->DataValidade             =  $DataValidade;
        $Proposta->DataPossivelEntrada      = $DataPossivelEntrada;
//        $Proposta->DataMensalidadeParcela =
        $Proposta->DataEmissao              = date('Ymd');
        $Proposta->QtdParcelasFinanciamento = $QntParcelasFin;
        $Proposta->NumParcelasEntrada       = $QntParcelasEntrada;
        $Proposta->NumParcelasFinanciamento = $QntParcelasFin;
        $Proposta->ValorEntrada             = $ValorEntrada;
        $Proposta->ValorFinanciado          = $ValorFin;
        $Proposta->ValorTotalEntrada        = $ValorEntrada;
        $Proposta->ValorTotalFinanciado     = $ValorFin;
        $Proposta->ValorProposta            = $ValorFin + $ValorEntrada;
        $Proposta->ValorParcelaEntrada      = $ValorEntrada / $QntParcelasEntrada;
        $Proposta->ValorParcelaFinanciada   = $ValorFin / $QntParcelasFin;
        //$Proposta->ComissaoTotalPercentual  =
        //$Proposta->ComissaoTotalValor       =
        //$Proposta->UsuarioId =
        $Proposta->ImovelId = $Data['EdIdImovel'];
        //$Proposta->CorretorId =
        $Proposta->StatusPropostaId = '00000001-0000-0000-0000-000000000000';
        $IdProposta = $Proposta->save();


        foreach( $Data['CbTipoPagamentoCad'] as $key => $n ) {

            $PropostaValor = new PropostaValores();
            $PropostaValor->DataVencimento       = $Data['EdDataVencimentoCad'][$key];
            $PropostaValor->Valor                = $Data['EdValorCad'][$key];
            $PropostaValor->ValorParcela         = $Data['EdValorCad'][$key] / $Data['EdParcelasCad'][$key];
            $PropostaValor->QtdParcelas          = $Data['EdParcelasCad'][$key];
            $PropostaValor->PropostaId           = $IdProposta;
            $PropostaValor->TbTipoPagamentoId    = $Data['CbTipoPagamentoCad'][$key];
            $PropostaValor->Periodicidade        = $Data['CbPeriodicidadeCad'][$key];
            $PropostaValor->AtualizacaoMonetaria = $Data['CbAtulizacaoMonetariaCad'][$key];
            $PropostaValor->FormaPagamento       = $Data['CbFormaPagamentoCad'][$key];
            $PropostaValor->save();

        }

        foreach( $Data['EdTipoPartesCad'] as $key => $n ) {
            $PropostaVinculo = new VinculoPessoaProposta();
            $PropostaVinculo->ExibeProposta        = 'S';
            $PropostaVinculo->Percentual           = 100;
            $PropostaVinculo->PropostaId           = $IdProposta;
            $PropostaVinculo->TipoPartePropostaId  = $Data['EdTipoPartesCad'][$key];
            $PropostaVinculo->PessoaId             = $Data['EdIdPessoaCad'][$key];
            $retorno = $PropostaVinculo->save();
        }

        //Muda o status do Imovel
        $Imovel = Imovel::find($Data['EdIdImovel']);
        $Imovel->Situacao = 'Negociação';
        $Imovel->save();



    return [
        'respostaAddContratoVenda'=>'ok',
        'PropostaId'=>$IdProposta
    ];

    }

    public function onFormModalAddPessoasVendeImovel()   {
        $Data = post();


        foreach( $Data['EdTipoPartesCad'] as $key => $n ) {
            $PropostaVinculo = new VinculoPessoaProposta();
            $PropostaVinculo->ExibeProposta        = 'S';
            $PropostaVinculo->Percentual           = 100;
            $PropostaVinculo->PropostaId           = $Data['EdIdProposta'];
            $PropostaVinculo->TipoPartePropostaId  = $Data['EdTipoPartesCad'][$key];
            $PropostaVinculo->PessoaId             = $Data['EdIdPessoaCad'][$key];
            $CodigoVpp = $PropostaVinculo->save();
        }

        $data = $this->model->rawQuery(" SELECT vpp.Id, tpp.Nome as TipoParte, p.Nome, p.CpfCnpj, p.RgInscricaoEstadual, vpp.DataCriacao " .
                                       " FROM VinculoPessoaPropostas vpp join TbTiposParteProposta tpp on vpp.TipoPartePropostaId = tpp.Id " .
                                       "                                 join Pessoas p on vpp.PessoaId = p.Id " .
                                       " where vpp.PropostaId = '" . $Data['EdIdProposta'] . "' ORDER BY tpp.Nome, vpp.DataCriacao " );


    return ['respostaAddPessoasContratoVenda'=>'ok',
            'respostaAddDados' => $data];

    }

    public function onFormModalAddValoresVendeImovel()   {
        $Data = post();

        foreach( $Data['CbTipoPagamentoCad'] as $key => $n ) {

            $PropostaValor = new PropostaValores();
            $PropostaValor->DataVencimento       = $Data['EdDataVencimentoCad'][$key];
            $PropostaValor->Valor                = $Data['EdValorCad'][$key];
            $PropostaValor->ValorParcela         = $Data['EdValorCad'][$key] / $Data['EdParcelasCad'][$key];
            $PropostaValor->QtdParcelas          = $Data['EdParcelasCad'][$key];
            $PropostaValor->PropostaId           = $Data['EdIdProposta'];
            $PropostaValor->TbTipoPagamentoId    = $Data['CbTipoPagamentoCad'][$key];
            $PropostaValor->Periodicidade        = $Data['CbPeriodicidadeCad'][$key];
            $PropostaValor->AtualizacaoMonetaria = $Data['CbAtulizacaoMonetariaCad'][$key];
            $PropostaValor->FormaPagamento       = $Data['CbFormaPagamentoCad'][$key];
            $PropostaValor->save();

        }

        $data = $this->model->rawQuery(" SELECT pv.id, ttp.Descricao as TipoPagamento, pv.Valor, pv.QtdParcelas, pv.ValorParcela, pv.DataVencimento,  " .
                                       "        pv.FormaPagamento, pv.Periodicidade, pv.AtualizacaoMonetaria, pv.DataCriacao " .
                                       " FROM PropostaValores pv join TbTiposPagamento ttp on pv.TbTipoPagamentoId = ttp.id " .
                                       " WHERE pv.PropostaId = '" . $Data['EdIdProposta'] . "'" .
                                       " ORDER BY ttp.Descricao, pv.DataCriacao " );

    return ['respostaAddValoresContratoVenda'=>'ok',
            'respostaAddDados' => $data];

    }


    //Eventos

    public function onRun(): void    {
        $this->addScript('assets/js/centralvendas.js');
    }

    public function teste()
    {
        $data = [
            'Id'              => Uuid::generate(),
            'DataVencimento'  => date('Y-m-d H:i:s'),
            'NumeroParcela'   => 10,
            'DiasAtraso'      => 1,
            'Sequencia'       => 10,
            'Valor'           => 200,
            'ValorMulta'      => 10,
            'ValorPago'       => 100,
            'ValorMora'       => 100,
            'Pago'            => 0,
            'Vencido'         => 0,
            'Cancelado'       => 0,
            'PropostaId'      => '{C34A5CB0-FD30-11E7-B42B-2DB06BE629AC}',
            'PropostaValorId' => '{C3525E10-FD30-11E7-A40B-932818BD71F1}',
            'DataCriacao'     => date('Y-m-d H:i:s'),
        ];

        $obj = new PropostaValorParcelas;
        foreach ($data as $k=>$v)
            $obj->$k = $v;

        try
        {
            $res = $obj->save();
            var_dump($res);
        }
        catch (\Exception $e){
            echo $e->getMessage();
        }
    }

}
<?php namespace wSGI\Modules\Alugueis\Controllers;


use App\Modules\wSGI\Models\CaixaItem;
use Core\Controller;
use App\Modules\wSGI\Models\ImovelContratoParcelas;
use App\Modules\wSGI\Models\Caixa;
use App\Modules\wSGI\Models\ImovelLancamento;

class Pagamentos extends Controller
{
    public $pageTitle = 'Pagamento de aluguel';
    public function index() {
        $this->action = 'Início';
        parent::index();
    }

    public function onImovelPelaPasta() {
        //Pega os dados do imovel
        $dataImovel = $this->model->rawQuery("select i.Id, i.Pasta, i.NumSite, CONCAT('Logradouro: ', e.Logradouro, ' Qd.: ', e.Quadra, ' Lt.: ', e.Lote, ' Nº: ', e.Numero) as Endereco " .
            " from imoveis i join enderecos e on i.EnderecoId = e.Id " .
            " where i.Pasta = '" . Post('pasta') . "'");

        //Paga os dados do contrato ativo
        if ($dataImovel)    {
            $dataContrato = $this->model->rawQuery("select TOP 1 ic.*, p.Nome as Locatario from ImovelContratos ic join pessoas p on ic.PessoaId = p.Id where Ativo = 1 and ImovelId = '" . $dataImovel->Id . "'");


            //Pega os dados das parcelas
            if ($dataContrato) {
                $dataParcelas = $this->model->rawQuery("select * from ImovelContratoParcelas where ImovelContratoId = '" . $dataContrato->Id . "' order by NumeroParcela asc");

                if (!$dataParcelas) {
                  $dataParcelas = ['erro'=>'Parcelas não encontradas'];
                } else {
                    $dataBoleto = $this->model->rawQuery("select b.*, bb.* from Boleto b join BoletoBancos bb on b.BoletoBancoId = bb.Id where b.ParcelaId = '" . $dataParcelas[0]->Id . "' order by b.NumeroParcela asc");

                    if (!$dataBoleto)
                        $dataBoleto = ['erro'=>'Boletos não encontrados'];
                }
            } else {
                $dataContrato = ['erro'=>'Contrato não encontrado'];
                $dataParcelas = ['erro'=>'Contrato não encontrado'];
                $dataBoleto = ['erro'=>'Boletos não encontrados'];
            }
        } else  {
            $dataImovel   = ['erro'=>'Imóvel não encontrado'];
            $dataContrato = ['erro'=>'Imóvel não encontrado'];
            $dataParcelas = ['erro'=>'Imóvel não encontrado'];
            $dataBoleto = ['erro'=>'Boletos não encontrados'];
        }



        $data = ['Imovel'   =>$dataImovel,
                 'Contratos'=>$dataContrato,
                 'Parcelas' =>$dataParcelas,
                 'Boletos'  =>$dataBoleto
                ];

        return $data;


    }

    public function onImovelPelaNumSite() {
        $dataImovel = $this->model->rawQuery("select i.Id, i.Pasta, i.NumSite, CONCAT('Logradouro: ', e.Logradouro, ' Qd.: ', e.Quadra, ' Lt.: ', e.Lote, ' Nº: ', e.Numero) as Endereco " .
            " from imoveis i join enderecos e on i.EnderecoId = e.Id " .
            " where i.NumSite = '" . Post('numsite') . "'");

        //Paga os dados do contrato ativo
        if ($dataImovel)    {
            $dataContrato = $this->model->rawQuery("select TOP 1 ic.*, p.Nome as Locatario from ImovelContratos ic join pessoas p on ic.PessoaId = p.Id where Ativo = 1 and ImovelId = '" . $dataImovel->Id . "'");


            //Pega os dados das parcelas
            if ($dataContrato) {
                $dataParcelas = $this->model->rawQuery("select * from ImovelContratoParcelas where ImovelContratoId = '" . $dataContrato->Id . "' order by NumeroParcela asc");

                if (!$dataParcelas) {
                    $dataParcelas = ['erro'=>'Parcelas não encontradas'];
                } else {
                    $dataBoleto = $this->model->rawQuery("select * from Boleto where ParcelaId = '" . $dataParcelas->Id . "' order by NumeroParcela asc");

                    if (!$dataBoleto)
                        $dataBoleto = ['erro'=>'Boletos não encontrados'];
                }
            } else {
                $dataContrato = ['erro'=>'Contrato não encontrado'];
                $dataParcelas = ['erro'=>'Contrato não encontrado'];
                $dataBoleto = ['erro'=>'Boletos não encontrados'];
            }
        } else  {
            $dataImovel   = ['erro'=>'Imóvel não encontrado'];
            $dataContrato = ['erro'=>'Imóvel não encontrado'];
            $dataParcelas = ['erro'=>'Imóvel não encontrado'];
            $dataBoleto = ['erro'=>'Boletos não encontrados'];
        }



        $data = ['Imovel'   =>$dataImovel,
            'Contratos'=>$dataContrato,
            'Parcelas' =>$dataParcelas,
            'Boletos'  =>$dataBoleto
        ];


        return $data;

    }

    public function onCarregaBoleto()   {
        $dataBoleto = $this->model->rawQuery("select b.*, bb.* from Boleto b join BoletoBancos bb on b.BoletoBancoId = bb.Id where b.ParcelaId = '" . Post('parcelaid') . "' order by b.NumeroParcela asc");

        if (!$dataBoleto)
            $dataBoleto = ['erro'=>'Boletos não encontrados'];

        return ['Boletos'  =>$dataBoleto];

    }

    public function onFormModalPagarAluguel()   {
        // Dados Enviados pelo formulário
        $Data = post();



        $Parcela = ImovelContratoParcelas::find($Data['EdIdParcela']);
        $Parcela->Pago = 1;
        $Parcela->ValorPago = $Parcela->Valor;
        $Parcela->DataPagamento = $Data['EdDataPagamento'];
        $Parcela->save();

        //Verifica se o caixa de hoje ja foi aberto
        $DataHoje = date('Ymd');
        $CaixaHoje = Caixa::whereBetween('DataHoraAbertura',[$DataHoje . " 00:00:00", $DataHoje . " 23:59:59"])->first();



        //Insere no caixa
        $CaixaItem                     = new CaixaItem();
        $CaixaItem->Descricao          = 'Pagamento de aluguel parcela: ' . $Data['EdParcela'];
        $CaixaItem->TipoReceita        = 'E';
        $CaixaItem->Repasse            = 0;
        $CaixaItem->Repassado          = 0;
        $CaixaItem->Transferencia      = (($Data['RbFormaPagamento'] == 'T') ? 1 : 0);
        $CaixaItem->ValorDinheiro      = (($Data['RbFormaPagamento'] == 'D' || $Data['RbFormaPagamento'] == 'T') ? $Parcela->ValorPago : null);
        $CaixaItem->ValorCheque        = (($Data['RbFormaPagamento'] == 'C') ? $Parcela->ValorPago : null);
        $CaixaItem->DataHoraLancamento = date('Ymd H:i:s');
        $CaixaItem->ImovelId           = $Data['EdIdImovel'];

        if ($CaixaHoje['DataHoraAbertura'] <> null && $CaixaHoje['DataHoraFechamento'] == null)
            $CaixaItem->CaixaId = $CaixaHoje['Id'];

        $CaixaItem->save();

        //Insere no imóvel lancamento
        $Lancamento                   = new ImovelLancamento();
        $Lancamento->ImovelId         = $Data['EdIdImovel'];
        $Lancamento->TipoLancamentoId = '00000001-0000-0000-0000-000000000000';
        $Lancamento->Valor            = $Parcela->Valor;
        $Lancamento->Mes              = $Parcela->MesReferencia;
        $Lancamento->Ano              = $Parcela->AnoReferencia;
        $Lancamento->DataLancamento   = date('Ymd H:i:s');
        $Lancamento->EntradaSaida     = 'E';
        $Lancamento->save();




        return  ['respostaPgoAluguel' => $Parcela ];
    }


    public function onRun(): void    {
        $this->addScript('assets/js/pagamentoaluguel.js');
    }

}
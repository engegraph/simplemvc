<?php namespace wSGI\Modules\Alugueis\Controllers;

use Core\Controller;
use App\Modules\wSGI\Models\TipoLancamentoFinanceiro;

class Lancamentos extends Controller
{
    public $pageTitle = 'Lançamento de débitos';

    public function index() {
        $this->action = 'Listagem';

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
                $Sql = " select il.*, tf.nome as NomeTipoLancamento " .
                       " from ImovelLancamentos il join TbTiposLancamentoFinanceiro tf on il.TipoLancamentoId = tf.Id " .
                       " where ImovelId = '" . $dataImovel->Id . "' and Mes = " . date('m') . " and Ano = " . date('Y') . " order by EntradaSaida, DataLancamento asc";
                $dataLancamentos = $this->model->rawQuery($Sql);

                if (!$dataLancamentos) {
                    $dataLancamentos = ['erro'=>'Lancamentos não encontrados'];
                }
            } else {
                $dataContrato    = ['erro'=>'Contrato não encontrado'];
                $dataLancamentos = ['erro'=>'Lancamentos não encontrados'];
            }
        } else  {
            $dataImovel      = ['erro'=>'Imóvel não encontrado'];
            $dataContrato    = ['erro'=>'Contrato não encontrado'];
            $dataLancamentos = ['erro'=>'Lancamentos não encontrados'];
        }

        $data = ['Imovel'      => $dataImovel,
                 'Contratos'   => $dataContrato,
                 'Lancamentos' => $dataLancamentos
        ];

        return $data;


    }

    public function onImovelPelaNumSite() {
        $data = $this->model->rawQuery("select i.Id, i.Pasta, i.NumSite, CONCAT('Logradouro: ', e.Logradouro, ' Qd.: ', e.Quadra, ' Lt.: ', e.Lote, ' Nº: ', e.Numero) as Endereco " .
            " from imoveis i join enderecos e on i.EnderecoId = e.Id " .
            " where i.NumSite = '" . Post('numsite') . "'");

        if ($data === '')
            $data = ['erro'=>'Registro não encontrado'];


        return $data;

    }

    public function onFormAddLancamentoDebitos()    {

        // Dados Enviados pelo formulário
        $Data = post();
        $MesAno = explode('/',$Data['EdMesAno']);
        //Insere no imóvel lancamento
        $Lancamento                   = new \App\Modules\wSGI\Models\ImovelLancamento();
        $Lancamento->ImovelId         = $Data['ImovelId'];
        $Lancamento->TipoLancamentoId = $Data['CbTipoLancamento'];
        $Lancamento->Valor            = str_replace(',','.',$Data['EdValor']);;
        $Lancamento->Mes              = $MesAno[0];
        $Lancamento->Ano              = $MesAno[1];
        $Lancamento->DataLancamento   = date('Ymd H:i:s');
        $Lancamento->EntradaSaida     = 'S';
        $IdLancamento = $Lancamento->save();

        $TipoLancamento = TipoLancamentoFinanceiro::find($Data['CbTipoLancamento']);

        $Data['Id']                       = $IdLancamento;
        $Data['NomeTipoImovelLancamento'] = $TipoLancamento->Nome;
        $Data['EntradaSaida']             = 'S';
        $Data['DataLancamento']           = date('d/m/Y');
        $Data['EdMesAno']                 = $MesAno[0] . ' / ' . $MesAno[1];

        return  ['respostaAddLancamentoDebito' => $Data ];
    }


//Eventos

    public function onRun(): void    {
        $this->addScript('assets/js/imovellancamento.js');
    }
}
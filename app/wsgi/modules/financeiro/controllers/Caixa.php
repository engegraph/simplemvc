<?php namespace wSGI\Modules\Financeiro\Controllers;

use Core\Controller;
use wSGI\Modules\Caixa\Models\Item;

class Caixa extends Controller
{
    public $model = 'caixa';

    public function index()
    {
        $DataHoje = date('Ymd');
        $this->CaixaAberto = $this->model::whereDate('DataHoraAbertura','<>',$DataHoje)
            ->whereNull('DataHoraFechamento')->first();

        //Carrega o caixa do dia
        $this->CaixaDiario = $this->model::whereBetween('DataHoraAbertura',[$DataHoje . " 00:00:00", $DataHoje . " 23:59:59"])->first();


        return parent::index(); // TODO: Change the autogenerated stub
    }

    public function onFecharCaixaMensagem() {
        $Data = post();


        $ValorFechamento = $this->model->rawQuery('select COALESCE((select SUM(COALESCE(ValorDinheiro,0)) + SUM(COALESCE(ValorCheque,0)) from Item where TipoReceita = \'E\' and CaixaId = \'' . $Data['IdCaixa'] . '\') - ' .
                                                  '       (select SUM(COALESCE(ValorDinheiro,0)) + SUM(COALESCE(ValorCheque,0)) from Item where TipoReceita = \'S\' and CaixaId = \'' . $Data['IdCaixa'] . '\'),0) as valor ' .
                                                  'from caixa where id = \'' . $Data['IdCaixa'] . '\'');



        $FechaCaixa = $this->model::find($Data['IdCaixa']);
        $FechaCaixa->DataHoraFechamento = date('Ymd H:i:s');
        $FechaCaixa->ValorFechamento = ($ValorFechamento->valor == null ? 0 : $ValorFechamento->valor);
        $FechaCaixa->save();

        //Verifica se o caixa de hoje ja foi aberto e fechado
        $DataHoje = date('Ymd');
        $CaixaHoje = $this->model::whereBetween('DataHoraAbertura',[$DataHoje . " 00:00:00", $DataHoje . " 23:59:59"])->first();

        if ($CaixaHoje['DataHoraAbertura'] <> null && $CaixaHoje['DataHoraFechamento'] <> null)
            $Retorno["CaixaHojeAbertoFechado"] = 'S';

        $Retorno['retornoonFecharCaixaMensagem'] = 'ok';

        return $Retorno;

    }

    public function onReabrirCaixa() {
        $Data = post();

        $FechaCaixa = $this->model::find($Data['IdCaixa']);
        $FechaCaixa->DataHoraFechamento = null;
        $FechaCaixa->ValorFechamento = null;
        $FechaCaixa->save();

        return ['retornoonReabrirCaixa'=>'ok'];

    }

    public function onFormModalAbrirCaixa() {
        // Dados Enviados pelo formulário
        $Data = post();
        $Caixa = new \wSGI\Modules\Caixa\Models\Caixa();
        $Caixa->LocalSaida       = 'A';
        $Caixa->ValorAbertura    = str_replace(',','.',$Data['EdValorAberturaCaixa']);
        $Caixa->DataHoraAbertura = date('Ymd H:i:s');
        $IdCaixa = $Caixa->save();

        $Retorno['IdCaixa']          = $IdCaixa;
        $Retorno['ValorAbertura']    = $Data['EdValorAberturaCaixa'];
        $Retorno['DataHoraAbertura'] = date('Ymd',strtotime($Caixa->DataHoraAbertura));


        return  ['respostaAbrirCaixa' => $Retorno ];
    }

    public function onAtualizaCaixa()   {
        if (Post('IdCaixa') != '')
            $Sql = "select * from Item where Caixaid = '" . Post('IdCaixa') . "' order by DataHoraLancamento asc";
        else
            $Sql = "select * from caixa where id = (select Id from caixa where DataHoraAbertura between '" . Post('DataCaixa') . "00:00:00' and '" . Post('DataCaixa') . "23:59:59')  order by DataHoraLancamento asc";


        $CaixaItens = $this->model->rawQuery($Sql);

        return ['atualizaCaixa'=>$CaixaItens];
    }

    public function onBaixarRepasse()   {

        //Recupera o valor do repasse
        $ValorRepasse = $this->model->rawQuery('select COALESCE (ValorRepasse,0) as ValorRepasse from Imoveis where id = \'' . Post('EdIdImovel') . '\'');



        //Localiza o Item e atualiza os dados
        $Item = Item::find(Post('EdIdParcela'));
        $Item->Repassado = 1;
        $Item->DataRepasse = Post('EdDataPagamento');
        $Item->ValorRepasse = str_replace(',','.',$ValorRepasse->ValorRepasse);
        $Item->save();


        //Verifica se o caixa de hoje ja foi aberto
        $DataHoje = date('Ymd');
        $CaixaHoje = $this->model::whereBetween('DataHoraAbertura',[$DataHoje . " 00:00:00", $DataHoje . " 23:59:59"])->first();

        //Insere outro item com o repasse
        $NovoItem                     = new Item();
        $NovoItem->Descricao          = 'Repasse ao locador';
        $NovoItem->TipoReceita        = 'S';
        $NovoItem->Repasse            = 1;
        $NovoItem->Repassado          = 0;
        $NovoItem->Transferencia      = ((Post('RbFormaPagamento') == 'T') ? 1 : 0);
        $NovoItem->ValorDinheiro      = ((Post('RbFormaPagamento') == 'D' || Post('RbFormaPagamento') == 'T') ? str_replace(',','.',$ValorRepasse->ValorRepasse) : null);
        $NovoItem->ValorCheque        = ((Post('RbFormaPagamento') == 'C') ? str_replace(',','.',$ValorRepasse->ValorRepasse) : null);
        $NovoItem->ValorRepasse       = 0;
        $NovoItem->DataHoraLancamento = date('Ymd H:i:s');
        $NovoItem->ImovelId           = Post('EdIdImovel');
        $NovoItem->CaixaId            = $CaixaHoje->Id;
        $NovoItem->save();

        return ['retornoonBaixarRepasse'=> 'ok'];


    }

    public function onFormModalAddCaixa()   {

        //Verifica se o caixa de hoje ja foi aberto
        $DataHoje = date('Ymd');
        $CaixaHoje = $this->model::whereBetween('DataHoraAbertura',[$DataHoje . " 00:00:00", $DataHoje . " 23:59:59"])->first();

        // Dados Enviados pelo formulário
        $NovoItem                     = new Item();
        $NovoItem->Descricao          = 'Entrada (' . Post('EdDescricaoEntrada') . ')';
        $NovoItem->TipoReceita        = 'E';
        $NovoItem->Repasse            = 1;
        $NovoItem->Repassado          = 0;
        $NovoItem->Transferencia      = ((Post('RbFormaPagamento') == 'T') ? 1 : 0);
        $NovoItem->ValorDinheiro      = ((Post('RbFormaPagamento') == 'D' || Post('RbFormaPagamento') == 'T') ? str_replace(',','.',Post('EdValorEntrada')) : null);
        $NovoItem->ValorCheque        = ((Post('RbFormaPagamento') == 'C') ? str_replace(',','.',Post('EdValorEntrada')) : null);
        $NovoItem->ValorRepasse       = 0;
        $NovoItem->DataHoraLancamento = date('Ymd H:i:s');
        $NovoItem->CaixaId            = $CaixaHoje->Id;
        $NovoItem->save();


        return  ['respostaAddCaixa' => 'ok'];
    }

//Eventos

    public function onRun(): void    {
        $this->addScript('assets/js/caixa.js');
    }
}
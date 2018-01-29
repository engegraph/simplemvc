<?php namespace wSGI\Modules\Vendas\Controllers;

use Core\Controller;

class Parcelas  extends Controller
{
    public $pageTitle = 'Controle de parcelas';

    public function onCarregaPessoas()   {
        $data = $this->model->rawQuery("select Id as id, Nome as value, concat('Nome: ', Nome, ' CPF: ', CpfCnpj) as label from pessoas where nome like '" . post('name_startsWith') . "%'");
        //return ['pessoas'=>$data];
        return $data;
    }

    public function onCarregaEmpreendimentos()   {
        $data = $this->model->rawQuery("select Id as id, Nome as value, concat('Nome: ', Nome, ' CNPJ: ', CNPJ) as label from Empreendimentos where nome like '" . post('name_startsWith') . "%'");
        //return ['pessoas'=>$data];
        return $data;
    }

    public function onImovelPelaPasta() {
        $data = $this->model->rawQuery("select i.Id, i.Pasta, i.NumSite, CONCAT('Logradouro: ', e.Logradouro, ' Qd.: ', e.Quadra, ' Lt.: ', e.Lote, ' Nº: ', e.Numero) as Endereco " .
            " from imoveis i join enderecos e on i.EnderecoId = e.Id " .
            " where i.Pasta = '" . Post('pasta') . "'");
        if ($data === '')
            $data = ['erro'=>'Registro não encontrado'];

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

    public function onCarregaPesquisa() {

        $Data = post();
        $SqlFiltro = '';
        $SqlFiltroSituacao = '';

        $SqlBase =  " SELECT pe.Nome as Comprador, e.Nome as Empreendimento, en.Quadra, en.Lote, ttp.Descricao," .
                    " pvp.*" .
                    " FROM Propostas p JOIN VinculoPessoaPropostas vpp on p.Id = vpp.PropostaId" .
                    "                  JOIN Pessoas pe on vpp.PessoaId = pe.Id" .
                    "                  JOIN Imoveis i on p.ImovelId = i.Id" .
                    "                  JOIN Enderecos en on i.EnderecoId = en.Id" .
                    "             LEFT JOIN Empreendimentos e on i.EmpreendimentoId = e.Id" .
                    "                  JOIN PropostaValorParcelas pvp on pvp.PropostaId = p.Id" .
                    "   			   JOIN PropostaValores pv on pv.Id = pvp.PropostaValorId " .
                    "                  JOIN TbTiposPagamento ttp on pv.TbTipoPagamentoId = ttp.Id " .
                    " WHERE p.Id is not null ";

        $SqlOrder = " ORDER BY pe.Nome, e.Nome, pv.Id, pvp.NumeroParcela   ";


        if ($Data['EdIdComprador'] != '')
            $SqlFiltro .= " AND pe.Id = '" . $Data['EdIdComprador'] . "' ";

        if ($Data['EdIdEmpreendimento'] != '')
            $SqlFiltro .= " AND e.Id = '" . $Data['EdIdEmpreendimento'] . "' ";

        if ($Data['ImovelId'] != '')
            $SqlFiltro .= " AND i.Id = '" . $Data['ImovelId'] . "' ";

        if (($Data['EdDataVencimentoIni'] != '') && ($Data['EdDataVencimentoFim'] != ''))
            $SqlFiltro .= " AND pvp.DataVencimento BETWEEN '" . $Data['EdDataVencimentoIni'] . "' AND '" . $Data['EdDataVencimentoIni'] . "' ";

        if (($Data['EdDataPgIni'] != '') && ($Data['EdDataPgFim'] != ''))
            $SqlFiltro .= " AND pvp.DataPagamento BETWEEN '" . $Data['EdDataPgIni'] . "' AND '" . $Data['EdDataPgFim'] . "' ";


        if (isset($Data['EdTipoSituacao'])) {
            foreach( $Data['EdTipoSituacao'] as $Valor ) {
                if ($Valor == 'Pagas')
                    $SqlFiltroSituacao .= " AND pvp.Pago = 1 ";

                if ($Valor == 'Não pagas')
                    $SqlFiltroSituacao .= " AND pvp.Pago = 0 ";

                if ($Valor == 'Vencidas')
                    $SqlFiltroSituacao .= " AND pvp.Vencido = 1 ";

                if ($Valor == 'Canceladas')
                    $SqlFiltroSituacao .= " AND pvp.Cancelado = 1 ";

                if ($Valor == 'Baixadas_Manualmente')
                    $SqlFiltroSituacao .= " AND pvp.Cancelado = 1 ";

                if ($Valor == 'Todas')
                    $SqlFiltroSituacao = " ";
            }
        }

        if (isset($Data['EdTipoParcela'])) {
            foreach( $Data['EdTipoParcela'] as $Valor ) {

                if ($Valor == 'Entradas')
                    $SqlFiltro .= " AND ttp.Id = '{00000001-0000-0000-0000-000000000000}' ";

                if ($Valor == 'Financiamentos')
                    $SqlFiltro .= " AND ttp.Id = '{00000002-0000-0000-0000-000000000000}' ";

                if ($Valor == 'Negociacoes')
                    $SqlFiltro .= " AND ttp.Id = '{00000003-0000-0000-0000-000000000000}' ";

            }
        }


        $DadosRetorno = $data = $this->model->rawQuery($SqlBase . $SqlFiltroSituacao . $SqlFiltro . $SqlOrder);


        return ['respostaPesquisa' => $DadosRetorno,
                'Contador' => count($DadosRetorno)];
    }




    public function onRun(): void    {

        $this->addScript('assets/js/controleparcelas.js');
    }
}


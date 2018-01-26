<?php namespace wSGI\Modules\Crm\Controllers;

use Core\Controller;

class Recepcao extends Controller
{
    public $pageTitle = 'CRM - Recepção';

    public function onImovelVerificaPessoaCadastrada()  {
        //Pega os dados do imovel
        $dataPessoa = $this->model->rawQuery("select * " .
                                             " from Pessoas " .
                                             " where Nome like '" . Post('NomePessoa') . "%'");

        //Paga os dados do contrato ativo
        if ($dataPessoa)    {

        } else  {
            $dataPessoa      = ['erro'=>'Pessoa não encontrada'];

        }

        $data = ['Pessoa'      => $dataPessoa];

        return $data;
    }

    public function onImovelVerificaImovelCadastrado()  {
        //Pega os dados do imovel
        $dataImovel = $this->model->rawQuery("select * " .
                                             " from Imoveis " .
                                             " where Pasta = '" . Post('PastaNumSite') . "'" .
                                             " or NumSite  = '" . Post('PastaNumSite') . "'");

        //Paga os dados do contrato ativo
        if ($dataImovel)    {

        } else  {
            $dataImovel      = ['erro'=>'Imóvel não encontrado'];

        }

        $data = ['Imovel'      => $dataImovel];

        return $data;
    }

//Eventos

    public function onRun(): void    {
        $this->addScript('assets/js/crmrecepcao.js');
    }



}
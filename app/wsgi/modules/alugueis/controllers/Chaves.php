<?php namespace wSGI\Modules\Alugueis\Controllers;

use Core\Controller;

class Chaves extends Controller {

    public $pageTitle = 'Controle de chaves';

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


//Eventos

    public function onRun(): void    {
        $this->addScript('assets/js/imovelcontrolechaves.js');
    }

}
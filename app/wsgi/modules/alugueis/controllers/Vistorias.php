<?php namespace wSGI\Modules\Alugueis\Controllers;

use Core\Controller;

class Vistorias extends Controller
{
    public $pageTitle = 'Controle de vistorias';

    public function index() {
        $this->action = 'Listagem';

        parent::index();
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

    public function PostImg()   {

        $PathStorage = path_storage() . 'vistorias/';
        $PathVistoriaAtual = $PathStorage . Post('ImovelVistoriaId') . '/';

        //Verifica se a pasta vistoria existe
        if (file_exists($PathStorage) == false)    {
            mkdir($PathStorage);
        }

        //Verifica se a pasta especifica da vistoria existe
        if (file_exists($PathVistoriaAtual) == false)    {
            mkdir($PathVistoriaAtual);
        }

        $TempExtensao = pathinfo($_FILES['file']['name'], PATHINFO_EXTENSION);
        $TempFileName = md5_file($_FILES['file']['tmp_name']) . '.' . $TempExtensao;


//        move_uploaded_file($_FILES['file']['tmp_name'],$PathVistoriaAtual . $_FILES['file']['name']);
        move_uploaded_file($_FILES['file']['tmp_name'],$PathVistoriaAtual . $TempFileName);
        echo json_encode(["teste"=>$_FILES]);
    }


//Evnetos
    public function onRun(): void    {
        $this->addScript('assets/js/imovelcontrolevistorias.js');
    }

}
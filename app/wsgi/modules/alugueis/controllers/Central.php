<?php namespace wSGI\Modules\Alugueis\Controllers;

use Core\Controller;

class Central extends Controller
{
    public $pageTitle = 'Central de aluguéis';

    public function onCarregaPessoas()   {
        $data = $this->model->rawQuery("select Id as id, Nome as value, concat('Nome: ', Nome, ' CPF: ', CpfCnpj) as label from pessoas where nome like '" . post('name_startsWith') . "%'");
        //return ['pessoas'=>$data];
        return $data;
    }



//Eventos

    public function onRun(): void    {
        $this->addScript('assets/js/centralalugueis.js');
    }

}
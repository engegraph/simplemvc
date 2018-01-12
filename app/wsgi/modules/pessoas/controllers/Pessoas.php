<?php namespace wSGI\Modules\Pessoas\Controllers;

use Core\Classes\Session;
use Core\Controller;
use wSGI\Modules\Pessoas\Models\Estado;
use wSGI\Modules\Pessoas\Models\Pessoa;

class Pessoas extends Controller
{
    public function teste()
    {
        $data = [
            'Pessoa' => [
                'TipoPessoa' => 1,
                'Nome' => 'Airton Lopes',
                'Email' => 'airton.lopes@engegraph.com.br',
/*                'Endereco' => [
                    'Bairro' => 'Bueno',
                    'Cep' => '74223200',
                    'Numero' => '51',
                    'Cidade' => [
                        'Nome' => 'Goiânia',
                        'Estado' => [
                            'Nome' => 'Goiás',
                            'Uf' => 'GO'
                        ],
                        'Capital' => 'Sim'
                    ],
                    'Lote' => '10',
                    'Quadra' => '21',
                    'Complemento' => 'Casa'
                ],*/
                'Nascimento' => '12/12/2015',
                'Cadastro' => '12/01/2018',
                'Atualizacao' => '0000-00-00',
                'CpfCnpj' => '02894606389',
            ]
        ];
        echo '<pre>';
        print_r($data);
       #echo '</pre>';

        $Model = new Pessoa;
        $Model->Nome = 'Marcos';
        $Model->TipoPessoa = 1;
        $Model->CpfCnpj = '2154555';

        try
        {
            if($res = $Model->save())
            {
                var_dump($res);
            }
            else
            {
                var_dump($this->alerts());
            }
        }
        catch (\Exception $e)
        {
            echo $e->getMessage();
        }


    }
}
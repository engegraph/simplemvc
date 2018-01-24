<?php namespace wSGI\Modules\Pessoas\Controllers;

use Core\Controller;
use wSGI\Modules\Pessoas\Models\Pessoa;

class Pessoas extends Controller
{

    public function onRun(): void
    {
        $this->addScript('assets/js/pessoas.js');
    }

    public function teste()
    {
        $Request = [
            'Pessoa' => [
                'Nome'  => 'Airton Lopes',
                'NomeFantasia'  => 'Almeida',
                'Email' => 'airtonlopes_@hotmail.com',
                'TipoPessoa' => 1,
                'Endereco' => [
                    'Logradouro' => 'Rua Mundoca Alvim',
                    'Numero' => '728',
                    'Cidade' => [
                        'Nome' => 'Timbiras',
                        'Estado'=> [
                            'Nome' => 'Maranhão AT',
                            'Uf' => 'MA',
                        ],
                        'Capital' => 1
                    ],
                    'Complemento' => 'Casa de Barro',
                    'Quadra' => '00',
                    'Lote' => '00',
                    'Cep' => '74223200',
                ],
                'CpfCnpj' => '289466389',
                'Mae' => [
                    'Nome'  => 'Rosa Lopes',
                    'NomeFantasia'  => 'Dr Rosa',
                    'Email' => 'rosa.lopes@teste.com',
                    'CpfCnpj' => '887788',
                    'TipoPessoa' => 1,
                ],
                'Pai' => [
                    'Nome'  => 'Otávio Francisco',
                    'NomeFantasia'  => 'Sr. Otávio',
                    'Email' => 'otavio.francisco@teste.com',
                    'CpfCnpj' => '88556',
                    'TipoPessoa' => 1,
                ],
                'Civil' => [
                    'Nome' => 'Junto',
                    'Descricao' => 'Relacionamento sem casamento, apenas juntos e misturado',
                ]
            ]
        ];

        $Obj = Pessoa::find('6E2E4C80-012E-11E8-A8ED-C3C33D872B94');
        $res = $Obj->saveAll($Request);
        echo '<pre>';
        print_r($res);
    }
}
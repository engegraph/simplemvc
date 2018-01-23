<?php namespace wSGI\Modules\Pessoas\Controllers;

use Core\Controller;
use wSGI\Modules\Controles\Models\Cidade;
use wSGI\Modules\Controles\Models\Endereco;
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
                'Email' => 'airton.engegraph.com.br',
                'TipoPessoa' => 1,
                'Endereco' => [
                    'Rua' => 'Praça T-24',
                    'Numero' => '51',
                    'Cidade' => [
                        'Nome' => 'Goiânia',
                        'Estado'=> [
                            'Nome' => 'Goiás',
                            'Uf' => 'GO',
                        ],
                        'User' => [
                            'Nome' => 'Airton Lopes',
                            'Email' => 'airton@hotmail.com',
                            'Password' => '123456'
                        ],
                        'Capital' => 1,
                    ],
                    'Enge' => [
                        'teste'
                    ],
                    'Complemento' => 'Casa',
                    'Quadra' => '21',
                    'Lote' => '04',
                    'Cep ' => '6542000',
                ],
                'CpfCnpj' => '87996666'
            ]
        ];

        $Pessoa = new Pessoa;
        $res = $Pessoa->push($Request);
        var_dump($res);


    }

    private function saveAll(array $Request = []) : array
    {
        $Attr = [];
        foreach ($Request as $Prop => $Val)
        {
            if(is_array($Val))
            {
                if($Prop == $this->getClass())
                    return array_filter($Val, 'is_scalar');

                $Attr = $this->saveAll($Val);
            }
        }

        return $Attr;
    }

    private function getClass()
    {
        return 'Estado';
    }
}
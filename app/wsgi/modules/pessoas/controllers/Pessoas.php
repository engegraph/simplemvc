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
                    'Logradouro' => 'Praça T-24',
                    'Numero' => '51',
                    'Cidade' => [
                        'Nome' => 'Goiânia 8',
                        'Estado'=> [
                            'Nome' => 'Goiás 8',
                            'Uf' => 'G8',
                        ],
                        'Capital' => 1,
                    ],
                    'Complemento' => 'Casa',
                    'Quadra' => '21',
                    'Lote' => '04',
                    'Cep' => '6542000',
                ],
                'CpfCnpj' => '87996666'
            ]
        ];

        $Endereco = new Pessoa;
        $res = $Endereco->push($Request);
        echo '<pre>';
        print_r($res);
    }


    private function dump(array $data, $result = [])
    {
        foreach ($data as $name => $value)
        {
            if(is_array($value))
            {
                $obj = array_filter($value, 'is_scalar');
                $res = $this->dump($value, $obj);
                $f   = $name.'Id';
                $result[$f] = $res;
            }
        }
        return $result;
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
        return 'Pessoa';
    }
}
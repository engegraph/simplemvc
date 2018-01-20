<?php namespace wSGI\Modules\Pessoas\Controllers;

use Core\Controller;
use wSGI\Modules\Controles\Models\Cidade;
use wSGI\Modules\Controles\Models\Endereco;

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
                'Email' => 'airton.engegraph.com.br',
                'Endereco' => [
                    'Rua' => 'Praça T-24',
                    'Numero' => '51',
                    'Cidade' => [
                        'Nome' => 'Goiânia',
                        'Estado'=> [
                            'Nome' => 'Goiás',
                            'Uf' => 'GO',
                        ],
                        'Capital' => 'Sim',
                    ],
                    'Complemento' => 'Casa',
                    'Quadra' => '21',
                    'Lote' => '04'
                ],
                'Nascimento' => '1988',
                'Sexo' => 'Masculino'
            ]
        ];


        $Model = 'User';

        $SearchData = function(array $Request) use (&$SearchData, $Model){
            if(count($Request))
            {
                $Res = [];
                foreach ($Request as $Prop => $Val)
                {
                    if(is_array($Val))
                    {
                        echo $Prop.'<br>';
                        if($Prop == $Model)
                        {
                            $Res = array_filter($Val, 'is_scalar');
                            break;
                        }
                        $SearchData($Val);
                    }
                }

                return $Res;
            }
        };

        #$all = $this->model::$DB->connection('auth')->select('select * from Usuarios WHERE Id=:Id', ['Id'=> 'BA7911E0-FC77-11E7-9931-251E59201CE4']);

        #$Res = $this->saveAll($Request);


        $dump = [
            'Endereco' => [
                'Logradouro' => 'Rosa Mistica 2',
                'Complemento' => 'Casa',
                'Bairro' => 'Bueno',
                'Cep'  => '74223200',
                'Quadra'  => '20',
                'Lote'  => '07',
                'Numero'  => '51',
                'Cidade' => [
                    'Nome' => 'Goiânia_',
                    'Estado' => [
                        'Nome' => '_Goáis',
                        'Uf'   => 'GO'
                    ],
                    'Capital' => '1',
                ],
                'Pessoa' => [
                    'Nome' => 'Airton Lopes',
                    'TipoPessoa' => 1,
                    'CpfCnpj' => 2894606389,
                ]
            ]
        ];


        #$Cidade = Cidade::find('0827DFD0-FDE2-11E7-94D8-CD7C51B59826');
        $Endereco = Endereco::find('B8B0AD60-FDEA-11E7-92E8-D73E9AF406F1');
        $res = $Endereco->dump($dump);
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
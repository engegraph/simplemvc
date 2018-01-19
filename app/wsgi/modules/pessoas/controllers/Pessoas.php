<?php namespace wSGI\Modules\Pessoas\Controllers;

use Core\Controller;

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

        $this->model->dump($Request);


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
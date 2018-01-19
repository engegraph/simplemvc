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
                        'User' => [
                            'Username' => 'web.comcafe',
                            'pass'    => '123456',
                        ],
                        'Praiana' => 'Não'
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

        $SearchData = function(array $Request, $Data = null) use (&$SearchData, $Model){
            if(count($Data))
            {
                if($Data)
                {
                    var_dump($Data);
                    die;
                }

                foreach ($Request as $Prop => $Val)
                {
                    if(is_array($Val))
                    {
                        if($Prop == $Model)
                        {
                            echo 'OPA';
                        }

                        $SearchData($Val, $Data);
                    }
                }
            }
        };

        #$all = $this->model::$DB->connection('auth')->select('select * from Usuarios WHERE Id=:Id', ['Id'=> 'BA7911E0-FC77-11E7-9931-251E59201CE4']);

        $SearchData($Request);

    }
}
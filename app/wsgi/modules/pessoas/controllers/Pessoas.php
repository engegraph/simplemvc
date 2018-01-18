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

        $SearchData = function(array $Data) use (&$SearchData, $Model){
            if(count($Data))
            {
                foreach ($Data as $Name => $Val)
                {
                    if (is_array($Val))
                    {
                        if($Name == $Model)
                        {
                            $Attr = [];
                            foreach ($Val as $K => $V)
                                if(is_scalar($V))
                                    $Attr[$K] = $V;

                            return $Attr;
                        }
                        return $SearchData($Val);
                    }
                    else
                        continue;
                }
            }
        };

        #$all = $this->model::$DB->connection('auth')->select('select * from Usuarios WHERE Id=:Id', ['Id'=> 'BA7911E0-FC77-11E7-9931-251E59201CE4']);

        $Data = $SearchData($Request);

        var_dump($Data);
    }
}
<?php namespace wSGI\Modules\Auth\Controllers;

use Core\Controller;

class Recover extends Controller
{
    use \wSGI\Modules\Auth\Traits\Common;

    public $model = null;

    protected $pageInfo = [
        'headerText' => 'Deseja testar nosso sistema ?',
        'headerLabel' => 'CRIE SUA CONTA',
        'headerLink' => '/auth/register',
    ];

    /**
     * Exibe a página de recuperação
     */
    public function index()
    {
        $this->view('recover', 'auth');
    }


    /**
     * Recebe as informações do usuário e procede com a recuperação
     * @return array
     */
    public function onRecover()
    {
        return [
            'alert' => [
                'type'=>'warning',
                'title'=>'Recuperação',
                'content'=>'Serviço de restauração de dados em implementação'
            ]
        ];
    }
}
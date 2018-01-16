<?php  namespace wSGI\Modules\Auth\Controllers;

use Core\Controller;

/**
 * Class Signin Responsável por proceder com a autenticação do usuário
 * @package wSGI\Modules\Auth\Controllers
 */

class Signin extends Controller
{
    use \wSGI\Modules\Auth\Traits\Common;

    public $model = null;

    protected $pageInfo = [
        'headerText' => 'Deseja testar nosso sistema ?',
        'headerLabel' => 'CRIE SUA CONTA',
        'headerLink' => '/auth/register',
    ];

    /**
     * Exibe a página de login
     */
    public function index()
    {
        $this->view('signin', 'auth');
    }

    /**
     * Recebe os dados da página de login e procedo com a autenticação
     */
    public function onAuth()
    {
        return [
            'alert' => [
                'type'=>'warning',
                'content'=>'Em breve este serviço estará disponível',
                'title'=>'Autenticação'
            ]
        ];
    }
}
<?php  namespace wSGI\Modules\Auth\Controllers;

use Core\Classes\Cookie;
use Core\Classes\Message;
use Core\Classes\Session2 as Sess;
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
     * Recebe os dados da página de login e prossegue com a autenticação
     */
    public function onAuth()
    {
        $v = $this->validator::make(post('Auth'),[
                'username'=>'required|username',
                'password'=>'required'
                ],[
                'username.required' => 'Informe seu email ou nome de usuário',
                'username.username' => 'Nome de usuário inválido',
                'password.required' => 'Informe sua senha',
            ]
        );

        if($v->fails())
        {
            $message = $this->validator->exception($v);
            return ['alert' => ['type'=>'danger', 'content'=>$message, 'title'=>'Dados incorretos']];
        }

        // Executa a autenticação
        return $this->auth->login(post('Auth.username'), post('Auth.password'));
    }

    public function teste()
    {
       /* $user = new \stdClass();
        $user->Nome = 'Airton Lopes';
        $user->Email = 'airton.lopes@engegraph.com.br';
        $user->Logado = true;
        $user->Permissions = [
            'Gravar' => 'Sim',
            'Editar' => 'Sim',
            'Deletar' => 'Não'
        ];
        Sess::set('auth.token', 'sifjsfiwfwfwwfffwq');
        Sess::set('auth.rememver', false);
        Sess::set('auth.user', $user);
       */
        //Sess::trash('auth.rememver');
        Sess::destroy();
        var_dump(Sess::all());
    }
}
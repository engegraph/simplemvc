<?php namespace wSGI\Modules\Auth\Controllers;

use Core\Classes\Message;
use Core\Controller;
use Illuminate\Database\Capsule\Manager as DB;

class Register extends Controller
{
    use \wSGI\Modules\Auth\Traits\Common;

    public $model = 'User';

    protected $pageInfo = [
        'headerText' => 'Já possuie uma conta ?',
        'headerLabel' => 'FAÇA LOGIN',
        'headerLink' => '/auth/signin',
    ];

    /**
     * Exibe a página com formulário de registro
     */
    public function index()
    {
        $this->view('register', 'auth');
    }

    /**
     * Receve os dados do usuário e procedo com o registro do usuário
     * @return array
     */
    public function onRegister()
    {
        try
        {
            if($Res = $this->model->save())
            {
                Message::success('<b>'.$Res->Nome.'</b>, seu cadastro foi realizado com sucesso.');
                return ['redirect'=>backend_url($this->get('headerLink'))];
            }
        }
        catch (\Exception $e)
        {
            return [
                'alert' => [
                    'type'=>'danger',
                    'content'=>$e->getMessage(),
                    'title'=>'Autenticação'
                ]
            ];
        }

    }
}
<?php  namespace wSGI\Modules\Auth\Controllers;

use Core\Controller;

/**
 * Class Signin Responsável por proceder com a autenticação do usuário
 * @package wSGI\Modules\Auth\Controllers
 */

class Signin extends Controller
{
    public $model = null;

    /**
     * Adicionando arqivos
     */
    public function onRun(): void
    {
        $this->addStyle('assets/css/auth.css');
        $this->addScript('assets/js/auth.js');
    }

    /**
     * Exibe a página de login
     */
    public function index()
    {
        $this->view('signin', 'auth');
    }

    public function onAuth()
    {
        die('Autenticação em fase de implementação');
    }
}
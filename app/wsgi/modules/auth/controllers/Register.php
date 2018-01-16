<?php namespace wSGI\Modules\Auth\Controllers;

use Core\Controller;

class Register extends Controller
{
    public $model = null;

    public $headerText  = 'Já possui um cadastro ?';
    public $headerLabel = 'FAÇA LOGIN';
    public $headerLink  = '/auth/signin';

    public function index()
    {
        $this->view('register', 'auth');
    }
}
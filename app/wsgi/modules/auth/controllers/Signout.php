<?php namespace wSGI\Modules\Auth\Controllers;

use Core\Classes\Message;
use Core\Classes\Redirect;
use Core\Controller;

class Signout extends Controller
{
    public $model = null, $crud = null;

    public function index()
    {
        if($this->logout())
        {
            return Redirect::to(backend_url('/auth/signin'))->withAlert('info', 'Você fez logout com sucesso! Até breve :)');
        }
    }

    private function logout()
    {
        return true;
    }
}
<?php  namespace wSGI\Modules\Auth\Controllers;

use Core\Controller;

class Signin extends Controller
{
    public $model = null;

    public function index()
    {
        $this->view('Signin.index');
    }
}
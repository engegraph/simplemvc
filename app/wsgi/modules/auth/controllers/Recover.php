<?php namespace wSGI\Modules\Auth\Controllers;

use Core\Controller;

class Recover extends Controller
{
    public $model = null;

    public function index()
    {
        $this->view('recover', 'auth');
    }
}
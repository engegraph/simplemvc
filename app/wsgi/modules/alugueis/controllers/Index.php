<?php namespace wSGI\Modules\Alugueis\Controllers;

use Core\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->view('index');
    }
}
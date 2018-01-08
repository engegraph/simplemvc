<?php namespace wSGI\Modules\Aluguel\Controllers;

use Core\Controller;

class Index extends Controller
{
    public function index()
    {
        $this->view('index');
    }
}
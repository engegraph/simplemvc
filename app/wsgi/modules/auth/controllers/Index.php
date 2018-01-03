<?php namespace wSGI\Modules\Auth\Controllers;

use Core\Controller;

class Index extends Controller
{

    public function onRun(): void
    {
        $this->addScript('assets/js/auth.js');
        $this->addStyle('assets/css/auth.css');
    }

    public function index()
    {
        $this->view('index');
    }
}
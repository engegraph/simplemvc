<?php namespace wSGI\Modules\Auth\Controllers;

use Core\Controller;

class Index extends Controller
{

    public function onRun(): void
    {
        $this->addStyle('assets/css/auto.css');
        $this->addScript('assets/js/auth.js');
    }

    public function index()
    {
        $this->view('index');
    }
}
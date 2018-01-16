<?php namespace wSGI\Modules\Pessoas\Controllers;

use Core\Controller;

class Pessoas extends Controller
{
    public function onRun(): void
    {
        $this->addScript('assets/js/pessoas.js');
    }
}
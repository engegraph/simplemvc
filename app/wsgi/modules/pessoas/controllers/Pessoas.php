<?php namespace wSGI\Modules\Pessoas\Controllers;

use Core\Classes\Session;
use Core\Controller;

class Pessoas extends Controller
{
    public function onRun(): void
    {
        $this->addScript('assets/js/pessoas.js');
    }

    public function teste()
    {
        echo date_conv('01/01/0000', 'en').'<br>';
    }
}
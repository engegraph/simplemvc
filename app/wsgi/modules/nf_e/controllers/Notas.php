<?php namespace wSGI\Modules\Nf_e\Controllers;

use Core\Controller;

/**
 * Class Notas
 * @package wSGI\Modules\Nf_e\Controllers
 */

class Notas extends Controller
{
    public $model = null;

    public function emitir()
    {
        $this->view('notas.emitir');
    }
}
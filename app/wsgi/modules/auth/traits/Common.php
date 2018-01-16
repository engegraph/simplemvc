<?php namespace wSGI\Modules\Auth\Traits;

trait Common
{
    /**
     * Adicionando arqivos
     */
    public function onRun(): void
    {
        $this->addStyle('assets/css/auth.css');
        $this->addScript('assets/js/auth.js');
    }
}
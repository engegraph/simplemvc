<?php namespace wSGI\Modules\Index;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{
    public function registerNavigation(): array
    {
        return [
            'jesus' => 'salva'
        ];
    }
}
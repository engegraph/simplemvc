<?php namespace wSGI\Modules\Auth;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{
    public function registerNavigation(): array
    {
        return [
            'amor' => 'tudo'
        ];
    }
}
<?php namespace wSGI\Modules\Auth;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{
    public function registerNavigation(): array
    {
        return [
            'auth' => [
                'url' => backend_url('/'),
                'label' => 'Auth',
                'icon' => 'tachometer',
            ]
        ];
    }
}
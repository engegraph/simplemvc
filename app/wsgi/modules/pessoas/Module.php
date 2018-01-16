<?php namespace wSGI\Modules\Pessoas;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{
    public function registerNavigation(): array
    {
        return [
            'pessoas' => [
                'url' => backend_url('/pessoas/pessoas'),
                'label' => 'Pessoas',
                'icon' => 'users',
                'permissions' => ['*'],
            ]
        ];
    }
}
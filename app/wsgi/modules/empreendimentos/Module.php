<?php namespace wSGI\Modules\Empreendimentos;

use Core\Providers\ModuleBase;

class Module extends ModuleBase
{
    public function registerNavigation(): array
    {
        return [
            'empreendimentos' => [
                'url' => backend_url('/empreendimentos/empreendimentos'),
                'label' => 'Empreendimentos',
                'icon' => 'building',
                'permissions' => ['*'],
            ]
        ];
    }
}